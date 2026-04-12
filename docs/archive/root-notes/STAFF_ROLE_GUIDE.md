# Staff vs Admin - Role Permissions Guide

## Quick Reference

| Feature | Admin | Staff | Customer | Hotel Owner |
|---------|-------|-------|----------|-------------|
| **User Management** | ✅ Full | ❌ No | ❌ No | ❌ No |
| **Approve Users** | ✅ Yes | ❌ No | ❌ No | ❌ No |
| **Create Treks** | ✅ Yes | ✅ Own | ❌ No | ❌ No |
| **Edit Treks** | ✅ All | ✅ Own | ❌ No | ❌ No |
| **Delete Treks** | ✅ All | ✅ Own | ❌ No | ❌ No |
| **Manage Departures** | ✅ All | ✅ Own | ❌ No | ❌ No |
| **View Bookings** | ✅ All | ✅ Own treks | ✅ Own | ✅ Own hotel |
| **Manage Hotels** | ✅ All | ❌ No | ❌ No | ✅ Own |
| **View Payments** | ✅ All | ❌ No | ❌ No | ❌ No |
| **System Settings** | ✅ Yes | ❌ No | ❌ No | ❌ No |
| **Dashboard Access** | ✅ /admin | ✅ /staff | ✅ /customer | ✅ /hotel-owner |

---

## Staff Role Details

### What Staff Can Do:
1. ✅ Create new treks (will set `owner_id` to their user_id)
2. ✅ Edit their own treks (where `owner_id == auth()->id()`)
3. ✅ Delete their own treks
4. ✅ Manage departures for their treks (schedules, pricing)
5. ✅ View all bookings for their treks
6. ✅ Upload images for their treks
7. ✅ See their own staff dashboard

### What Staff CANNOT Do:
1. ❌ Create treks for other staff members
2. ❌ View/edit treks created by other staff
3. ❌ Manage users or approve registrations
4. ❌ View payment information
5. ❌ View other staff's bookings or data
6. ❌ Access admin panel settings
7. ❌ Create hotel listings
8. ❌ View system analytics/reports

### Staff Dashboard Routes:
```
/staff/dashboard              (main dashboard)
/staff/treks                  (list own treks)
/staff/treks/create          (create new trek)
/staff/treks/{trek}/edit     (edit own trek)
/staff/departures            (manage own departures)
/staff/bookings              (view own bookings)
```

---

## Admin Role Details

### What Admin Can Do:
1. ✅ Full access to everything
2. ✅ Create/edit/delete any content
3. ✅ Manage all users (approve, reject, create)
4. ✅ View all bookings, payments, reports
5. ✅ Manage system settings
6. ✅ Assign treks to staff members
7. ✅ Approve hotel listings

### Admin Dashboard Routes:
```
/admin/dashboard              (main admin dashboard)
/admin/treks                  (manage all treks)
/admin/users                  (manage users)
/admin/approvals              (approve users/hotels)
/admin/payments               (view payments)
/admin/reports                (analytics)
/admin/settings               (system config)
```

---

## Implementation Examples

### Checking if User Can Edit Trek
```php
class TrekPolicy
{
    public function update(User $user, Trek $trek): bool
    {
        // Admin can edit any trek
        if ($user->role === 'admin') {
            return true;
        }
        
        // Staff can only edit their own treks
        if ($user->role === 'staff') {
            return $user->id === $trek->owner_id;
        }
        
        // Others cannot edit
        return false;
    }
}
```

### In Controller
```php
class TrekController
{
    public function edit(Trek $trek)
    {
        // Will throw 403 if not authorized
        $this->authorize('update', $trek);
        
        // Or manual check
        if (auth()->user()->role === 'staff' && $trek->owner_id !== auth()->id()) {
            abort(403, 'You can only edit your own treks');
        }
        
        return view('admin.treks.edit', ['trek' => $trek]);
    }
}
```

### Query Own Treks (for Staff)
```php
// Get all treks owned by current staff member
$myTreks = auth()->user()->ownedTreks;

// Get all treks with their departures
$myTreks = auth()->user()->ownedTreks()
                   ->with('departures', 'images')
                   ->paginate();

// In query
$treks = Trek::where('owner_id', auth()->id())
             ->latest()
             ->paginate();
```

### Create Trek (Auto-assigns Owner)
```php
// In UpsertTrekService, we now do:
$payload['owner_id'] = auth()->id();
$trek = Trek::create($payload);

// Or in controller
Trek::create([
    ...$validated,
    'owner_id' => auth()->id(),
]);
```

---

## Middleware for Route Protection

### Staff-only Routes
```php
// In routes/web.php
Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index']);
    Route::resource('/staff/treks', StaffTrekController::class);
});
```

### Middleware: CheckRole
```php
class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (! in_array(auth()->user()?->role, $roles)) {
            abort(403);
        }
        
        return $next($request);
    }
}
```

---

## Database Queries by Role

### For Admin (see everything)
```php
// All users
$users = User::paginate();

// All treks
$treks = Trek::with('owner')->paginate();

// All bookings
$bookings = TrekBooking::with('user', 'departure.trek')->paginate();
```

### For Staff (see only own)
```php
// Only my treks
$treks = auth()->user()->ownedTreks()->with('departures')->paginate();

// Only my booking data
$bookings = TrekBooking::whereHas('departure.trek', function ($q) {
    $q->where('owner_id', auth()->id());
})->paginate();

// Only my departures
$departures = Departure::whereHas('trek', function ($q) {
    $q->where('owner_id', auth()->id());
})->paginate();
```

### For Customer (see only own)
```php
// My bookings
$bookings = auth()->user()->trekBookings()->with('departure.trek')->paginate();

// My hotel bookings
$hotelBookings = auth()->user()->hotelBookings()->with('hotelRoom.hotel')->paginate();
```

---

## New owner_id Field

### What is owner_id?
- Foreign Key to `users.id`
- Identifies which user "owns" the trek
- Set automatically when staff member creates trek
- Used for permission checks

### Set When:
- Trek is created (auto: `auth()->id()`)
- Admin manually assigns to staff member

### Query Examples:
```php
// Find all treks owned by user
Trek::where('owner_id', $user->id)->get();

// Find all treks by specific staff member
$staff = User::where('role', 'staff')->first();
$staff->ownedTreks;  // Returns collection

// Eager load owner
Trek::with('owner')->paginate();

// Filter by owner name
Trek::whereHas('owner', function ($q) {
    $q->where('name', 'John Doe');
})->get();
```

---

## Approval Status Enum

### Values:
- `pending` - Awaiting approval (default for new users)
- `approved` - User is approved and can access system
- `rejected` - User was rejected and cannot access

### Usage:
```php
// Check if approved
if ($user->approval_status === 'approved') {
    // Allow system access
}

// Check if pending
if ($user->approval_status === 'pending') {
    // Show "Awaiting approval" message
}

// Check if rejected
if ($user->approval_status === 'rejected') {
    // Show "Your account was rejected" message
}

// Update after approval
$user->update(['approval_status' => 'approved']);
```

### Migration from is_approved:
```php
// Old way (before refactoring):
if ($user->is_approved) { ... }

// New way (after refactoring):
if ($user->approval_status === 'approved') { ... }
```

---

## Testing Staff Features

### Create Test Trek as Staff
```php
$staff = User::factory()->create(['role' => 'staff']);
$trek = Trek::factory()->create(['owner_id' => $staff->id]);

// Verify ownership
$this->assertTrue($trek->owner->is($staff));
```

### Test Staff Permissions
```php
$staff = User::factory()->create(['role' => 'staff']);
$admin = User::factory()->create(['role' => 'admin']);

$trek = Trek::factory()->create(['owner_id' => $staff->id]);

// Staff can edit their trek
$this->assertTrue($staff->can('update', $trek));

// Staff cannot edit other staff's trek
$otherStaff = User::factory()->create(['role' => 'staff']);
$this->assertFalse($otherStaff->can('update', $trek));

// Admin can edit any trek
$this->assertTrue($admin->can('update', $trek));
```

---

## Summary

**Staff is a "Limited Admin"** for their own treks:
- They manage content they create (treks, schedules)
- They see data related to their content
- They cannot access system-wide management
- Perfect for trek guides/operators who manage their own offerings

**Admin is "Full System Admin":**
- Manages everything
- Approves users and content
- Views all reports and payments
- Manages system configuration
