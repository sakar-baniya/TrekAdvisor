# Updated Permission System - Admin Full Access, Staff Operations Only

## Overview

Simplified permission model:
- **ADMIN**: Full unrestricted access to entire system
- **STAFF**: Full operational management (no user management, settings, payment gateway)
- **CUSTOMER**: Browse & book (read-only on most)
- **HOTEL_OWNER**: Manage own hotel properties

---

## ADMIN Permissions ✅

Admin has FULL access to everything:

| Feature | Create | View | Edit | Delete | Notes |
|---------|--------|------|------|--------|-------|
| Users | ✅ | ✅ | ✅ | ✅ | Can approve staff/hotel owners |
| Treks | ✅ | ✅ | ✅ | ✅ | Creates all treks |
| Departures | ✅ | ✅ | ✅ | ✅ | Manages departure schedule |
| Trek Bookings | ✅ | ✅ | ✅ | ✅ | Full booking management |
| Passengers | ✅ | ✅ | ✅ | ✅ | Manages trek passengers |
| Hotels | ✅ | ✅ | ✅ | ✅ | Creates all hotels |
| Hotel Rooms | ✅ | ✅ | ✅ | ✅ | Manages rooms |
| Hotel Bookings | ✅ | ✅ | ✅ | ✅ | Full hotel booking control |
| Payments | 🔴 | ✅ | ✅ | 🔴 | Views & refunds (system creates) |
| Reviews | 🔴 | ✅ | ✅ | ✅ | Can delete inappropriate reviews |
| Settings | ✅ | ✅ | ✅ | ✅ | Payment gateway, email, integrations |
| Reports/Dashboard | ✅ | ✅ | ✅ | 🔴 | Full analytics & reporting |
| User Roles | ✅ | ✅ | ✅ | ✅ | Assign admin/staff/customer roles |

---

## STAFF Permissions 🔧

Staff handles all operational work with restrictions on user management and settings.

### ✅ STAFF CAN DO:

| Feature | Create | View | Edit | Delete | Notes |
|---------|--------|------|------|--------|-------|
| Treks | 🔴 | ✅ | ✅ | 🔴 | Manage (but admin creates) |
| Departures | 🔴 | ✅ | ✅ | 🔴 | Manage (but admin creates) |
| Itineraries | 🔴 | ✅ | ✅ | 🔴 | Update trek itinerary details |
| Trek Bookings | 🔴 | ✅ | ✅ | 🔴 | View & update status |
| Passengers | ✅ | ✅ | ✅ | 🔴 | Manage during bookings |
| Hotels | 🔴 | ✅ | ✅ | 🔴 | Manage all hotels |
| Hotel Rooms | 🔴 | ✅ | ✅ | 🔴 | Manage all rooms |
| Hotel Bookings | 🔴 | ✅ | ✅ | 🔴 | View & manage status |
| Payments | 🔴 | ✅ | 🔴 | 🔴 | **View-only** for verification |
| Reviews | 🔴 | ✅ | 🔴 | 🔴 | View & moderate (flag/hide) |

### 🔴 STAFF CANNOT DO:

| Feature | Blocked | Reason |
|---------|---------|--------|
| Create/Delete Admins | ✅ BLOCKED | Prevent privilege escalation |
| Assign/Change User Roles | ✅ BLOCKED | Only admin assigns roles |
| Approve/Reject Users | ✅ BLOCKED | Admin-only approval process |
| Access Website Settings | ✅ BLOCKED | Prevent config changes |
| Access Payment Gateway | ✅ BLOCKED | Prevent payment system tampering |
| Refund Payments | ✅ BLOCKED | View-only access to payments |
| Modify Payment Status | ✅ BLOCKED | Only system + admin can change |
| Change User Approval Status | ✅ BLOCKED | Admin responsibility |
| Access Reports/Dashboard | ✅ BLOCKED | Admin analytics only |
| Delete High-Risk Data | ✅ BLOCKED | Users, settings, payment records |

---

## CUSTOMER Permissions 🛍️

| Feature | Create | View | Edit | Delete |
|---------|--------|------|------|--------|
| Own Profile | 🔴 | ✅ | ✅ | 🔴 |
| Browse Treks | 🔴 | ✅ | 🔴 | 🔴 |
| Create Trek Bookings | ✅ | ✅ | 🔴 | 🔴 |
| View Own Bookings | 🔴 | ✅ | 🔴 | 🔴 |
| Create Reviews | ✅ | ✅ | ✅* | ✅* |
| View Reviews | 🔴 | ✅ | 🔴 | 🔴 |

*Can only edit/delete own reviews

---

## HOTEL_OWNER Permissions 🏨

| Feature | Create | View | Edit | Delete |
|---------|--------|------|------|--------|
| Own Hotels | 🔴 | ✅ | ✅ | 🔴 |
| Own Hotel Rooms | 🔴 | ✅ | ✅ | 🔴 |
| Own Hotel Bookings | 🔴 | ✅ | ✅ | 🔴 |
| Other Hotels | 🔴 | 🔴 | 🔴 | 🔴 |

---

## Key Design Decisions

### 1. Admin Creates Everything
- **ADMIN** creates all treks, departures, hotels
- This ensures data consistency and prevents staff from creating isolated resources
- Staff can manage/edit but not create or delete

### 2. Staff = Full Operations Access
- Staff handles day-to-day operations: bookings, confirmations, guest management
- NO trek owner_id model needed - staff can manage any trek resource
- Simpler than limiting to "own" resources

### 3. Payment View-Only for Staff
- Staff can view payments to **verify** bookings are paid
- Staff CANNOT: refund, change status, access gateway, modify records
- Critical security layer preventing fraud

### 4. Settings = Admin Only  
- No staff access to:
  - Payment gateway configuration
  - Website/app settings
  - Email templates
  - API integrations
  - System configuration

### 5. User Management = Admin Only
- Staff cannot:
  - Create/delete users
  - Assign roles
  - Approve/reject users or hotel owners
  - Change approval status
- Prevents privilege escalation

### 6. Approval Process
- **Only admin** can approve:
  - New staff members
  - Hotel owner registrations
  - User rejections

---

## Implementation Example

```php
// TrekPolicy - Simple, no owner_id checks
class TrekPolicy {
    public function update(User $user, Trek $trek): bool
    {
        // Admin: Can edit any trek
        if ($user->role === 'admin') return true;
        
        // Staff: Can edit any trek (no owner check)
        if ($user->role === 'staff') return true;
        
        return false;
    }

    public function delete(User $user, Trek $trek): bool
    {
        // Only admin can delete
        return $user->role === 'admin';
    }
}

// PaymentPolicy - Critical security
class PaymentPolicy {
    public function view(User $user, Payment $payment): bool
    {
        // Both admin and staff can VIEW
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, Payment $payment): bool
    {
        // Only admin can modify (refund, change status)
        return $user->role === 'admin';
    }
}

// SettingsPolicy - Admin only
class SettingsPolicy {
    public function update(User $user): bool
    {
        return $user->role === 'admin'; // Period.
    }
}
```

---

## Authorization Checklist

10 Policies Created:
- ✅ UserPolicy - User CRUD + approvals (admin only)
- ✅ TrekPolicy - Trek management (staff can edit, only admin creates/deletes)
- ✅ DeparturePolicy - Departure management (staff can edit, admin creates/deletes)
- ✅ TrekBookingPolicy - Trek bookings (staff can manage, only admin creates)
- ✅ ItineraryPolicy - Itinerary management (staff can edit, admin creates/deletes)
- ✅ PassengerPolicy - Passenger management (staff can manage)
- ✅ PaymentPolicy - **VIEW-ONLY for staff** (critical security)
- ✅ HotelPolicy - Hotel management (staff can edit, admin creates/deletes)
- ✅ HotelRoomPolicy - Room management (staff can edit, admin creates/deletes)
- ✅ HotelBookingPolicy - Hotel bookings (staff can manage)
- ✅ ReviewPolicy - Review moderation (staff can moderate, not delete)
- ✅ SettingsPolicy - **ADMIN ONLY** (critical security)

---

## Next Steps

1. Register all policies in `AuthServiceProvider::policies()`
2. Use middleware for route protection:
   ```php
   Route::middleware(['auth', 'check-role:admin,staff'])->group(function () {
       // Staff + admin routes
   });
   ```
3. Use `@can` directive in Blade templates:
   ```blade
   @can('update', $trek)
       <a href="{{ route('treks.edit', $trek) }}">Edit Trek</a>
   @endcan
   ```
4. Protect sensitive operations:
   ```php
   $this->authorize('refund', $payment);
   $this->authorize('accessPaymentGateway', new SettingsPolicy());
   ```

