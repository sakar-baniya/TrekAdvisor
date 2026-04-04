# Schema Refactoring - APPLIED NOW ✅

## Changes Applied

### 1. ✅ User Model Enhancement
**New Field**: `approval_status` enum
- Values: `pending`, `approved`, `rejected`
- Replaces: `is_approved` (kept for backward compatibility for 2-3 releases)
- New Method: `ownedTreks()` relationship

**Why**: Captures all approval states (pending, approved, rejected) instead of just true/false

### 2. ✅ Trek Ownership
**New Field**: `owner_id` (Foreign Key to users)
- Staff member who manages/created the trek
- Set automatically to `auth()->id()` on creation
- Access via: `$trek->owner()` or `$trek->owner()->with('ownedTreks')`

**Why**: Clear ownership model for staff members managing treks

### 3. ✅ Image Handling Refactored
**Removed**: 
- `treks.image` (single string field)
- `hotels.image` (single string field)

**Now Using**:
- `trek_images` table (already existed)
- `hotel_images` table (already existed)

**New Helper Methods**:
```php
// Get all images ordered by sort_order
$trek->images()          // Returns HasMany relationship
$hotel->images()         // Returns HasMany relationship

// Get featured/hero image (first in sort_order)
$trek->image()           // Returns first image model
$hotel->image()          // Returns first image model
```

### 4. ⚠️ Staff Role Clarification
**Staff Role** (Limited admin for specific resources):
- ✅ Can manage (create/edit/delete) their own treks (via `owner_id`)
- ✅ Can manage departures for their treks
- ✅ Can see booking data for their treks
- ✅ Can upload images for their treks
- ❌ Cannot manage users
- ❌ Cannot manage other staff's treks
- ❌ Cannot manage system-wide settings
- ❌ Cannot approve hotels
- ❌ Cannot view all payments

**Admin Role** (Full system access):
- ✅ Can manage everything
- ✅ Can assign owner_id to any trek
- ✅ Can manage all users (approve/reject)
- ✅ Can manage all bookings and payments

---

## Files Modified

### Database Migrations
- ✅ `2026_04_04_000002_refactor_schema_approval_and_ownership.php` - New migration with all changes

### Models
- ✅ `app/Models/User.php` - Added `approval_status`, `ownedTreks()` relationship
- ✅ `app/Models/Trek.php` - Added `owner()` relationship, `images()`, `image()` methods
- ✅ `app/Models/Hotel.php` - Added `images()`, `image()` methods

### Services
- ✅ `app/Services/Shared/GalleryImageService.php` - Updated to use images table instead of single field
- ✅ `app/Services/Trek/UpsertTrekService.php` - Sets `owner_id` on creation, handles hero image in gallery

### Commands
- ✅ `app/Console/Commands/BackfillSchemaChanges.php` - New command for data migration

---

## Next Steps

### Step 1: Run Migration (When DB is ready)
```bash
php artisan migrate
```

### Step 2: Backfill Existing Data
```bash
php artisan app:backfill-schema-changes
```

This will:
- Convert `users.is_approved = true` → `approval_status = 'approved'`
- Convert `users.is_approved = false` → `approval_status = 'rejected'`
- Assign all treks without owner to first admin/staff user

### Step 3: Update Views/Templates

**Before** (OLD - if you were using single image):
```blade
<img src="{{ $trek->image }}" alt="{{ $trek->title }}" />
```

**After** (NEW - using image table):
```blade
@if($trek->image)
    <img src="{{ $trek->image->path }}" alt="{{ $trek->title }}" />
@else
    <img src="/images/placeholder.png" alt="No image" />
@endif

<!-- Or using null coalescing -->
<img src="{{ $trek->image?->path ?? '/images/placeholder.png' }}" alt="{{ $trek->title }}" />

<!-- For galleries -->
@forelse($trek->images as $image)
    <img src="{{ $image->path }}" alt="Trek image" />
@empty
    <p>No images</p>
@endforelse
```

**For Hotels** (Same pattern):
```blade
<img src="{{ $hotel->image?->path ?? '/images/placeholder.png' }}" alt="{{ $hotel->name }}" />
```

### Step 4: Test the Changes
```php
// In tinker or tests:
$trek = Trek::first();
echo $trek->owner->name;        // Display staff member name
echo $trek->image?->path;        // Display hero image
foreach ($trek->images as $img) {
    echo $img->path;             // Display all images
}

$user = User::find(1);
echo $user->approval_status;     // pending, approved, or rejected
echo $user->ownedTreks->count(); // Number of treks managed by user
```

### Step 5: Authorization

Add to your Staff middleware/policy:

```php
// In controller
public function edit(Trek $trek)
{
    // Only staff can edit their own treks
    if (auth()->user()->role === 'staff' && $trek->owner_id !== auth()->id()) {
        abort(403);
    }
    
    return view('admin.treks.edit', ['trek' => $trek]);
}

// Or use a Policy
public function update(User $user, Trek $trek)
{
    return $user->id === $trek->owner_id || $user->role === 'admin';
}
```

---

## Backward Compatibility

**Old Code NOT Broken**:
- `$user->is_approved` still works (field not removed yet)
- `$trek->gallery()` relationship still works
- `$hotel->gallery()` relationship still works

**Deprecation Timeline**:
- ✅ Now: Use new `approval_status` in new code
- ✅ Next 2-3 releases: Support both old and new
- ⏳ Later (6+ months): Remove `is_approved` field

**Transition Guide**:
```php
// OLD (still works)
if ($user->is_approved) { ... }

// NEW (preferred)
if ($user->approval_status === 'approved') { ... }

// FUTURE (after v2.0)
// Only new way will work
```

---

## Summary Table

| Change | Before | After | Impact |
|--------|--------|-------|--------|
| User Approval | `is_approved` (T/F) | `approval_status` (enum) | Better state tracking |
| Trek Owner | None | `owner_id` (staff) | Clear ownership |
| Trek Image | `trek.image` field | `trek_images` table | Cleaner data |
| Hotel Image | `hotel.image` field | `hotel_images` table | Cleaner data |
| Staff Role | Unclear scope | Defined permissions | Better UX |

---

## Database Schema After Changes

```sql
users:
  id, name, email, ..., role, approval_status, phone, ...

treks:
  id, owner_id (FK), title, slug, ..., status, ...
  -- NO image field anymore

trek_images:
  id, trek_id, path, is_placeholder, sort_order, ...

hotels:
  id, owner_id (FK), name, location, ..., status, ...
  -- NO image field anymore

hotel_images:
  id, hotel_id, path, sort_order, ...
```

---

## Testing Checklist

- [ ] Run migration successfully
- [ ] Run backfill command
- [ ] Check users have `approval_status` set
- [ ] Check new treks have `owner_id` assigned
- [ ] Create a new trek (auto-assigns `owner_id`)
- [ ] Upload hero image (goes to trek_images with sort_order=0)
- [ ] Upload gallery images
- [ ] Display trek with `$trek->image->path`
- [ ] Display gallery with `@foreach($trek->images)`
- [ ] Test staff can only edit own treks
- [ ] Test admin can edit all treks

---

## Questions on Staff Role?

Staff members (role='staff'):
- Are trek creators and managers
- Each trek has ONE owner_id (staff user)
- Can only modify/delete their own treks
- Cannot access admin panel (separate dashboard)
- Cannot manage other staff or users

Admin users (role='admin'):
- Can manage everything
- Can assign any trek to any staff member
- Can approve/reject users
- Full system access

---

## Rollback (If Needed)

```bash
php artisan migrate:rollback
```

This will:
- Remove `approval_status` from users
- Remove `owner_id` from treks  
- Restore `image` fields to treks and hotels
- Restore database to previous state

Data is preserved; operation is reversible.
