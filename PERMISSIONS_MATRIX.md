# TrekAdvisor - Complete Permission Matrix

## Role Overview

| Role | Purpose | Dashboard | System Access |
|------|---------|-----------|----------------|
| **ADMIN** | Full system control | `/admin` | All features |
| **STAFF** | Operational management | `/staff` | Treks, bookings, gear, reviews |
| **CUSTOMER** | User/booking | `/customer` | Browse & book |
| **HOTEL_OWNER** | Hotel management | `/hotel-owner` | Manage own hotel |

---

## ADMIN Permissions ✅ FULL SYSTEM ACCESS

### Users
- ✅ View all users
- ✅ Create users
- ✅ Edit users
- ✅ Delete users
- ✅ Assign roles (admin → staff → customer)
- ✅ Approve/reject user registrations
- ✅ Manage approval_status

### Treks & Operations
- ✅ Create treks
- ✅ Edit all treks
- ✅ Delete treks
- ✅ View all trek bookings
- ✅ Manage booking status (confirm, cancel)
- ✅ Manage passengers
- ✅ Create/edit/delete departures
- ✅ Manage captain/guide assignments

### Hotels
- ✅ Create hotels
- ✅ Edit all hotels
- ✅ Delete hotels
- ✅ Approve hotel owner applications
- ✅ View all hotel bookings
- ✅ Manage hotel rooms

### Payments
- ✅ View all payments
- ✅ View payment status, transaction_id, gateway
- ✅ View gateway responses (technical details)
- ✅ ❌ Process refunds (manual, requires documented process)
- ✅ ❌ Modify completed payments

### Gear
- ✅ Create gear items
- ✅ Edit gear items
- ✅ Delete gear items
- ✅ View all gear rentals
- ✅ Confirm/cancel rentals
- ✅ Mark items as returned

### Reviews
- ✅ View all reviews
- ✅ Delete reviews (moderation)
- ✅ Flag/unflag reviews

### Settings & Reports
- ✅ System settings
- ✅ Payment gateway configuration
- ✅ Email templates
- ✅ View analytics/reports
- ✅ Manage roles
- ✅ Access logs & audit trail

---

## STAFF Permissions ✅ OPERATIONAL MANAGEMENT

### What Staff CAN Do

#### Treks ✅
- ✅ Create new treks
- ✅ Edit all treks
- ✅ Delete treks
- ✅ View all treks (for operational overview)
- ✅ Manage itineraries for all treks
- ✅ Upload images for all treks

#### Departures ✅
- ✅ Create departures for all treks
- ✅ Edit departures for all treks
- ✅ Delete departures for all treks
- ✅ Change status (Available → Full → Completed)
- ✅ Update pricing for own departures

#### Trek Bookings ✅
- ✅ View bookings for trek departures
- ✅ Confirm/cancel trek bookings
- ✅ Update booking status (Pending → Confirmed → Cancelled)
- ✅ View passenger list for own bookings
- ✅ Manage passengers (add/remove)
- ✅ View booking notes/requests

#### Gear Management ✅
- ✅ Create gear items
- ✅ Edit gear items
- ✅ View all gear rentals
- ✅ Confirm rental requests (Pending → Active)
- ✅ Mark rents as returned
- ✅ Cancel rentals if needed

#### Hotels ✅
- ✅ View hotels (for operational context)
- ✅ Edit hotel details
- ✅ Manage hotel rooms
- ✅ View hotel bookings
- ✅ Manage hotel booking status

#### Payments - VIEW ONLY ✅
- ✅ View payment status (Success, Pending, Failed)
- ✅ View transaction_id (for reconciliation)
- ✅ Verify booking is paid
- ✅ Check payment gateway used (stripe/esewa/khalti)
- ✅ View created_at timestamp

#### Reviews ✅
- ✅ View all reviews
- ✅ Moderate reviews (flag/unflag)
- ✅ Delete flagged reviews
- ✅ See comments on own trek/gear reviews

### What Staff CANNOT Do ❌

#### Users ❌
- ❌ View user list
- ❌ Create users
- ❌ Edit other users
- ❌ Assign roles
- ❌ Approve/reject users
- ❌ Delete users
- ❌ Change other user's approval_status

#### Trek Ownership Restrictions ❌
- ❌ Trek ownership restrictions do not apply anymore
- ❌ No trek owner transfer flow is needed

#### Payments - RESTRICTED ❌
- ❌ View full financial data/reports
- ❌ View earnings analytics (yet)
- ❌ Change payment status
- ❌ View gateway response details (API keys, etc.)
- ❌ Process refunds
- ❌ Manually alter payment records
- ❌ Change gateway configuration
- ❌ View other user's payment method details

#### System ❌
- ❌ Access settings
- ❌ Create/modify roles
- ❌ View audit logs
- ❌ Manage email templates
- ❌ Configure payment gateway

---

## CUSTOMER Permissions ✅ USER LEVEL

### Browse & Book ✅
- ✅ View active treks
- ✅ View trek details, images, itinerary
- ✅ Book trek (create booking)
- ✅ View own trek bookings
- ✅ View own gear rentals
- ✅ Cancel own booking (if Pending/Confirmed)
- ✅ Cancel own gear rental (if Pending/Active)

### Browse Hotels ✅
- ✅ View active hotels
- ✅ View hotel details, rooms, images
- ✅ Book hotel room
- ✅ View own hotel bookings
- ✅ Cancel own booking (if Pending)

### Payments ✅
- ✅ View own payment status
- ✅ View own transaction details
- ✅ See payment history

### Profile ✅
- ✅ View own profile
- ✅ Edit own name, phone, email
- ✅ Change password
- ✅ View booking history

### Reviews ✅
- ✅ View all reviews
- ✅ Create review (after booking)
- ✅ Edit own review
- ✅ Delete own review

### What Customers CANNOT Do ❌
- ❌ View other user's bookings
- ❌ View staff/admin content
- ❌ Create treks
- ❌ Manage any content
- ❌ Access admin/staff dashboards

---

## HOTEL_OWNER Permissions ✅ HOTEL MANAGEMENT

### Own Hotel ✅
- ✅ View own hotel
- ✅ Edit own hotel details
- ✅ Upload hotel images
- ✅ View own hotel bookings
- ✅ Manage own hotel rooms
- ✅ Add/remove room types
- ✅ Update room pricing

### Reviews ✅
- ✅ View reviews for own hotel
- ✅ Respond to reviews (if implemented)

### What Hotel Owners CANNOT Do ❌
- ❌ View other hotels
- ❌ Create new hotel (start with one assigned)
- ❌ View guest bookings for other hotels
- ❌ Manage payments
- ❌ Manage users

---

## Permission Policies Implementation

### Laravel Policies Location
```
app/Policies/
  ├── UserPolicy.php           (user management)
  ├── TrekPolicy.php           (trek CRUD)
  ├── DeparturePolicy.php      (departure CRUD)
  ├── TrekBookingPolicy.php    (booking management)
  ├── HotelPolicy.php          (hotel CRUD)
  ├── HotelBookingPolicy.php   (hotel booking)
  ├── PaymentPolicy.php        (payment VIEW-ONLY for staff)
  ├── GearItemPolicy.php       (gear CRUD)
  ├── GearRentalPolicy.php     (rental management)
  ├── ReviewPolicy.php         (review moderation)
  └── GearRentalPolicy.php     (gear rentals)
```

### Usage in Controllers
```php
// Authorization
$this->authorize('update', $trek);          // Check TrekPolicy::update()
$this->authorize('view', $payment);         // Check PaymentPolicy::view()
$this->authorize('manageUsers', auth()->user()); // Admin only

// In routes
Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::resource('treks', TrekController::class);
});
```

---

## Key Rules Summary

### Rule 1: Admin = Full Access
Everything is allowed. No restrictions.

### Rule 2: Staff = Trek Operations
``` 
Can manage: all treks, all bookings, gear, reviews
Cannot: manage users, change settings, process payments
```

### Rule 3: Payments = VIEW ONLY for Staff
```
Can see: status, transaction_id, verification
Cannot: change, refund, access settings
```

### Rule 4: User Approval = Admin Only
```
Staff NO: cannot approve/reject users
Admin YES: approves applications
```

### Rule 5: Ownership Model
Each resource has an owner_id:
```
Hotel owner_id = hotel_owner or admin
```

---

## Route Protection Examples

### Admin Routes
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', AdminUserController::class);
    Route::resource('approvals', AdminApprovalController::class);
    Route::resource('settings', AdminSettingsController::class);
});
```

### Staff Routes
```php
Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::resource('treks', StaffTrekController::class);
    Route::resource('departures', DepartureController::class);
    Route::resource('bookings', StaffBookingController::class);
    Route::get('payments', [PaymentController::class, 'staffView']);
});
```

### Customer Routes
```php
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::resource('bookings', CustomerBookingController::class);
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel']);
});
```

### Hotel Owner Routes
```php
Route::middleware(['auth', 'role:hotel_owner'])->group(function () {
    Route::resource('hotel', HotelOwnerController::class)->only(['show', 'edit', 'update']);
    Route::resource('rooms', RoomController::class);
    Route::resource('bookings', HotelBookingController::class);
});
```

---

## Testing Permissions

```php
// Test Admin can do everything
$admin->can('update', $trek);              // true
$admin->can('refund', $payment);           // NO - refund not implemented

// Test Staff can do operations
$staff->can('update', $ownTrek);           // true
$staff->can('update', $otherStaffTrek);    // true
$staff->can('view', $payment);             // true
$staff->can('refund', $payment);           // false

// Test Customer limitations
$customer->can('update', $trek);           // false
$customer->can('create', Trek::class);     // false
$customer->can('view', $payment);          // Only own payment
```

---

## Summary Table

| Feature | Admin | Staff | Customer | Hotel Owner |
|---------|-------|-------|----------|-------------|
| Manage Users | ✅ | ❌ | ❌ | ❌ |
| Approve Users | ✅ | ❌ | ❌ | ❌ |
| Create Trek | ✅ | ✅ | ❌ | ❌ |
| Edit Own Trek | ✅ | ✅ | ❌ | ❌ |
| Edit Other Trek | ✅ | ✅ | ❌ | ❌ |
| View Bookings | ✅ | ✅ own | ✅ own | ✅ own |
| Confirm Booking | ✅ | ✅ own | ❌ | ✅ own |
| View Payment | ✅ | ✅ limited | ✅ own | ❌ |
| Process Refund | ❌ Manual | ❌ | ❌ | ❌ |
| Manage Settings | ✅ | ❌ | ❌ | ❌ |
| Moderate Reviews | ✅ | ✅ | ❌ | ❌ |
| Manage Gear | ✅ | ✅ | ❌ | ❌ |
| Manage Hotel | ✅ | ✅ | ❌ | ✅ own |
