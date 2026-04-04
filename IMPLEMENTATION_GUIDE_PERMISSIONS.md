# Permission System - Implementation Guide

## How Policies Work in Laravel

### 1. Register Policies in AuthServiceProvider

```php
// app/Providers/AuthServiceProvider.php
protected $policies = [
    User::class => UserPolicy::class,
    Trek::class => TrekPolicy::class,
    Departure::class => DeparturePolicy::class,
    TrekBooking::class => TrekBookingPolicy::class,
    Itinerary::class => ItineraryPolicy::class,
    Passenger::class => PassengerPolicy::class,
    Payment::class => PaymentPolicy::class,
    Hotel::class => HotelPolicy::class,
    HotelRoom::class => HotelRoomPolicy::class,
    HotelBooking::class => HotelBookingPolicy::class,
    GearItem::class => GearItemPolicy::class,
    GearRental::class => GearRentalPolicy::class,
    Review::class => ReviewPolicy::class,
];
```

### 2. Protect Routes with Authorization

```php
// routes/web.php
Route::middleware(['auth'])->group(function () {
    
    // Trek management
    Route::get('admin/treks', [TrekController::class, 'index'])->can('viewAny', Trek::class);
    Route::get('admin/treks/{trek}/edit', [TrekController::class, 'edit'])->can('update', 'trek');
    Route::post('admin/treks', [TrekController::class, 'store'])->can('create', Trek::class);
    Route::delete('admin/treks/{trek}', [TrekController::class, 'destroy'])->can('delete', 'trek');
    
    // Payment management
    Route::get('admin/payments', [PaymentController::class, 'index'])->can('viewAny', Payment::class);
    Route::post('admin/payments/{payment}/refund', [PaymentController::class, 'refund'])
        ->can('refund', 'payment');
    
    // Settings (admin only)
    Route::get('admin/settings', [SettingsController::class, 'show'])
        ->can('view', new SettingsPolicy());
    Route::post('admin/settings', [SettingsController::class, 'update'])
        ->can('update', new SettingsPolicy());
});
```

### 3. Use in Controllers

```php
// app/Http/Controllers/TrekController.php
public class TrekController extends Controller
{
    public function edit($id)
    {
        $trek = Trek::findOrFail($id);
        
        // Check authorization
        $this->authorize('update', $trek);
        
        return view('treks.edit', compact('trek'));
    }
    
    public function update(Request $request, Trek $trek)
    {
        // Check authorization
        $this->authorize('update', $trek);
        
        // Update logic
        $trek->update($request->validated());
        
        return redirect()->route('treks.show', $trek);
    }
    
    public function destroy(Trek $trek)
    {
        // Check authorization
        $this->authorize('delete', $trek);
        
        $trek->delete();
        
        return redirect()->route('treks.index');
    }
}
```

### 4. Use in Blade Templates

```blade
{{-- app/resources/views/treks/show.blade.php --}}

<h1>{{ $trek->name }}</h1>

{{-- Only show edit button if user can edit --}}
@can('update', $trek)
    <a href="{{ route('treks.edit', $trek) }}" class="btn btn-primary">Edit Trek</a>
@endcan

{{-- Only show delete button if user can delete --}}
@can('delete', $trek)
    <form action="{{ route('treks.destroy', $trek) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete Trek</button>
    </form>
@endcan

{{-- Staff can view payments --}}
@can('view', $trek->bookings->first()->payment ?? null)
    <div class="payment-status">
        Payment Status: {{ $trek->booking->payment->status }}
    </div>
@endcan
```

---

## Permission Checking Patterns

### Pattern 1: Check if User Can View

```php
// In controller
if ($user->can('view', $trek)) {
    // Show trek details
}

// In policy
public function view(User $user, Trek $trek): bool
{
    if ($user->role === 'admin') return true;
    if ($user->role === 'staff') return true;
    
    return false;
}
```

### Pattern 2: Check if User Can Edit

```php
// Check before showing edit form
@can('update', $trek)
    <a href="{{ route('treks.edit', $trek) }}">Edit</a>
@endcan

// In policy
public function update(User $user, Trek $trek): bool
{
    if ($user->role === 'admin') return true;
    if ($user->role === 'staff') return true;
    
    return false;
}
```

### Pattern 3: Check if User Can Delete

```php
// Only admin can delete
@can('delete', $trek)
    <button onclick="deleteTrek({{ $trek->id }})">Delete</button>
@endcan

// In policy
public function delete(User $user, Trek $trek): bool
{
    return $user->role === 'admin';
}
```

### Pattern 4: Check Sensitive Operations

```php
// Only admin can access payment gateway
$this->authorize('accessGateway', new SettingsPolicy());

// Or in policy directly
public function accessGateway(User $user): bool
{
    return $user->role === 'admin';
}
```

---

## Real-World Examples

### Example 1: Trek Management

**Admin**: Can create, edit, delete any trek
**Staff**: Can edit any trek, but not create or delete

```php
class TrekPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'admin'; // Only admin creates
    }

    public function update(User $user, Trek $trek): bool
    {
        return in_array($user->role, ['admin', 'staff']); // Both can edit
    }

    public function delete(User $user, Trek $trek): bool
    {
        return $user->role === 'admin'; // Only admin deletes
    }
}
```

**Usage in Controller**:
```php
public function store(CreateTrekRequest $request)
{
    // Authorize is called automatically if using policy binding
    $this->authorize('create', Trek::class);
    
    Trek::create($request->validated());
}

public function update(UpdateTrekRequest $request, Trek $trek)
{
    // Will throw AuthorizationException if not authorized
    $this->authorize('update', $trek);
    
    $trek->update($request->validated());
}
```

### Example 2: Payment Viewing (Critical Security)

**Admin**: Can view and refund payments
**Staff**: Can ONLY view (for verification), cannot refund
**Customer**: Can view own payments

```php
class PaymentPolicy
{
    public function view(User $user, Payment $payment): bool
    {
        // Admin and staff can view ANY payment
        // Customer can only view their own
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }
        
        return $user->id === $payment->user_id; // Customers see own only
    }

    public function refund(User $user, Payment $payment): bool
    {
        // ONLY admin can refund - strict access control
        return $user->role === 'admin';
    }
}
```

**Why This Security Layer**?
- Staff views payment to confirm booking is paid ✅
- Staff CANNOT refund (prevents fraud) ✅
- Staff CANNOT change payment status ✅
- Only admin can process refunds ✅

### Example 3: Settings Access (Most Restricted)

```php
class SettingsPolicy
{
    public function update(User $user): bool
    {
        return $user->role === 'admin'; // PERIOD.
    }
    
    public function accessPaymentGateway(User $user): bool
    {
        return $user->role === 'admin'; // STRICT
    }
}
```

**Usage**:
```php
// In SettingsController
public function update(Request $request)
{
    // Throws AuthorizationException if not admin
    $this->authorize('update', new SettingsPolicy());
    
    // Update settings...
}

// In view
@can('update', new SettingsPolicy())
    <!-- Show settings form -->
@else
    <p>Only admins can access settings.</p>
@endcan
```

---

## Throwing Authorization Exceptions

When a user tries to do something they're not authorized to do:

```php
// 1. Automatic (using authorize middleware)
Route::post('treks', [TrekController::class, 'store'])
    ->can('create', Trek::class); // Throws AuthorizationException if false

// 2. Manual (in controller)
public function destroy(Trek $trek)
{
    $this->authorize('delete', $trek);
    // Will throw AuthorizationException if policy returns false
    
    $trek->delete();
}

// 3. Error Response
// When AuthorizationException is thrown:
// - Returns 403 status code
// - Laravel shows "This action is unauthorized" by default
// - Can customize in app/Exceptions/Handler.php
```

---

## Testing Authorization

```php
// tests/Feature/TrekAuthorizationTest.php
class TrekAuthorizationTest extends TestCase
{
    public function test_admin_can_delete_trek()
    {
        $admin = User::factory(['role' => 'admin'])->create();
        $trek = Trek::factory()->create();
        
        $this->actingAs($admin)
            ->delete(route('treks.destroy', $trek))
            ->assertStatus(302); // Redirect on success
    }
    
    public function test_staff_cannot_delete_trek()
    {
        $staff = User::factory(['role' => 'staff'])->create();
        $trek = Trek::factory()->create();
        
        $this->actingAs($staff)
            ->delete(route('treks.destroy', $trek))
            ->assertStatus(403); // Forbidden
    }
    
    public function test_staff_can_edit_trek()
    {
        $staff = User::factory(['role' => 'staff'])->create();
        $trek = Trek::factory()->create();
        
        $this->actingAs($staff)
            ->put(route('treks.update', $trek), [
                'name' => 'Updated Trek Name'
            ])
            ->assertStatus(302); // Success
    }
}
```

---

## Checklist for Implementation

- [ ] Register all policies in `AuthServiceProvider`
- [ ] Add `$this->authorize()` calls to controllers
- [ ] Add `@can` directives to Blade templates
- [ ] Test authorization for each role
- [ ] Verify 403 errors show for unauthorized access
- [ ] Test edge cases (e.g., staff trying to delete)
- [ ] Verify admin can still do everything
- [ ] Document authorization rules in comments

---

## Troubleshooting

**Issue**: Getting "This action is unauthorized" for staff
**Solution**: Check that TrekPolicy::update() allows staff role

**Issue**: Staff sees delete button but request is denied
**Solution**: Add `@can('delete', $trek)` check in Blade template

**Issue**: Policy method not being called
**Solution**: Make sure policy is registered in AuthServiceProvider

**Issue**: Customer can see other customers' bookings
**Solution**: Add `$user->id === $booking->user_id` check in view policy

