# TrekAdvisor Database Schema - Senior Architecture Review & Recommendations

## EXECUTIVE SUMMARY

**Overall Assessment**: Your schema is 75% solid. It has good fundamentals but suffers from:
1. Inconsistent image handling
2. Unclear owner/creator relationships for some entities
3. Approval field using boolean instead of enum
4. Minor naming inconsistencies
5. Redundant single-image fields alongside image tables

**Verdict**: Refactor is LOW-RISK, HIGH-VALUE. The changes recommended are mostly cleanup, not restructuring.

---

## PART 1: DEEP ANALYSIS

### What is Already GOOD ✅

1. **Booking transactional design** (trek_bookings, hotel_bookings)
   - Price snapshots (price_per_person, subtotal, discount, total_price)
   - This is CORRECT for historical integrity
   - Should be kept exactly as is
   - Follows real-world booking platforms (Airbnb, Booking.com)

2. **Polymorphic reviews** (reviews table)
   - reviewable_type + reviewable_id is clean
   - Works for Trek, Hotel, GearItem without needing separate tables
   - Well-designed for minimal DB cruft

3. **Departure concept**
   - Separating trek scheduling from trek product is correct
   - Allows multiple departures per trek
   - Proper capacity tracking

4. **User role-based design**
   - Simple enum (admin, staff, customer, hotel_owner)
   - Better than role_id foreign key for this scale
   - No need for complex roles/permissions tables yet

5. **Payment flexibility**
   - payment_for (trek, hotel, gear) + reference_id allows polymorphic payments
   - Clean for a multi-product-type platform
   - Gateway options (stripe, esewa, khalti) shows real-world thinking

6. **Passenger manifest**
   - passengers table cleanly captures group composition
   - Practical for trek booking context
   - Age field is useful (child discounts, group composition, safety)

7. **Image galleries**
   - trek_images and hotel_images tables allow multiple images
   - sort_order field enables custom ordering
   - is_placeholder for placeholder images is smart

### What is INCONSISTENT ❌

| Issue | Where | Problem | Impact |
|-------|-------|---------|--------|
| Image handling | treks & hotels have `image` + trek_images/hotel_images | Confusion: which to use? | Code/query complexity |
| Approval status | users.is_approved (boolean) | Not scalable (what if pending?) | Can't track "rejected" states |
| Status naming | status in trek_bookings is 'Pending/Confirmed/Cancelled' but departures is 'Available/Full/Completed' | Inconsistent language | Confusing for developers |
| Owner field | hotels.owner_id but treks has NO owner | Who manages/created the trek? | Unclear responsibility |
| Image deletion | Single image field might not sync with image table | Orphaned images possible | Data integrity risk |

### What is POORLY NAMED 🏷️

| Current Name | Issue | Better Name |
|--------------|-------|------------|
| `reviewable_type` / `reviewable_id` | Polymorphic - less explicit | Keep as-is (Laravel convention) |
| `booking_reference` | Good but inconsistent with `rental_reference` | INCONSISTENT - see below |
| `total_passengers` | Could be ambiguous (what's "total"?) | KEEP - this is clear |
| `is_approved` | Boolean doesn't capture all states | `approval_status` (enum) |
| `remember_token` | Laravel default - keep it | KEEP |

### What is DUPLICATED UNNECESSARILY ⚠️

1. **Single image fields + image tables**
   ```
   treks.image (single string path)
   trek_images (table with multiple images)
   
   hotels.image (single string path)  
   hotel_images (table with multiple images)
   ```
   - Which is the "featured" image?
   - Both get out of sync?
   - **Decision**: Remove single `image` fields, use trek_images/hotel_images only

2. **Price fields in departures + trek_bookings**
   - departures.price (base price for departure)
   - trek_bookings.price_per_person (actual charged)
   - **Decision**: Keep both - departures.price is reference, trek_bookings.price_per_person is snapshot ✅

### What SHOULD Stay Unchanged (Critical Design Decisions)

1. ✅ **All price snapshot fields in trek_bookings and hotel_bookings**
   - This is transactional integrity - NEVER remove
   - Prevents historical booking data corruption

2. ✅ **Separate departures table**
   - Trek = product, Departure = scheduled instance
   - Correct modeling

3. ✅ **passengers.age field**
   - Useful for child discounts, safety regulations
   - Non-PII (just age, not DOB)
   - Keep it

4. ✅ **gear_items.total_stock**
   - You simplified gear system correctly
   - total_stock is good reference

5. ✅ **User role as enum, not role_id**
   - At this scale, enum is more practical
   - If you need complex role hierarchies later, migrate then

### What Should Be Simplified 🎯

1. **Remove duplicate image fields from parent tables**
   - Delete: `treks.image`
   - Delete: `hotels.image`
   - Reason: Single source of truth in image tables

2. **Improve approval_status for users**
   - Change: `is_approved` (boolean)
   - To: `approval_status` (enum: 'pending', 'approved', 'rejected')
   - Why: Captures rejected state, more descriptive, future-proof

3. **Standardize status enumeration across the board**
   - See Part 3 below

### What Should Be ADDED ⭐

1. **treks.owner_id or guide_id**
   - Currently no way to know who manages a trek
   - Option A: `owner_id` (staff member who created it)
   - Option B: `guide_id` (guide leading the trek)
   - **Recommendation**: Add `owner_id` → Foreign Key to users (role = 'staff')
   - Reasoning: Clear ownership for content management

2. **timestamps for booking price snapshots**
   - Already have created_at/updated_at
   - But consider: `booked_at` timestamp explicitly
   - **Recommendation**: Keep as-is, created_at serves this purpose

3. **Payment reconciliation fields**
   - Consider: `reconciled_at` timestamp
   - Consider: `reconciliation_notes` text
   - **For Later**: Don't add now, defer to v2
   - Reasoning: Over-engineering for current stage

### What Should Be RENAMED 🔤

| Table/Field | Current | Recommended | Reason |
|-------------|---------|-------------|--------|
| users | role: enum | role: enum | KEEP - good |
| users | is_approved | approval_status | More descriptive |
| treks | image | (remove) | Use trek_images |
| hotels | image | (remove) | Use hotel_images |
| hotel_bookings | num_nights | num_nights | KEEP - clear |
| hotel_bookings | price_per_night | price_per_night | KEEP - clear |
| hotel_bookings | num_rooms | num_rooms | KEEP - clear |

### What Should Be NORMALIZED Better 📊

Current state: Mostly good (3NF mostly followed)

Minor issue:
- **hotel_rooms.total_rooms**: Is this "total available" or "total in system"?
  - If it's inventory: Should sync with bookings
  - If it's definition: It's fine
  - **Keep as-is** with clear business logic around it

---

## PART 2: THE IDEAL PRACTICAL SCHEMA

### Table-by-Table Recommendations

#### 1. **users** ✏️ MODIFY
```
KEEP:
  - id, name, email, email_verified_at, password, remember_token
  - role (enum: 'admin', 'staff', 'customer', 'hotel_owner')
  - phone, created_at, updated_at

RENAME:
  - is_approved → approval_status (enum: 'pending', 'approved', 'rejected')
    Reason: Captures all states, future-proof, more descriptive

REMOVE:
  - Nothing critical

ADD:
  - Nothing essential
```

**Migration Strategy:**
```php
// Add new approval_status column alongside is_approved
Schema::table('users', function (Blueprint $table) {
    $table->enum('approval_status', ['pending', 'approved', 'rejected'])
          ->default('pending')
          ->after('is_approved');
});

// Backfill: is_approved=true → approval_status='approved'
// Then remove is_approved in Phase 2
```

---

#### 2. **treks** ✏️ MODIFY
```
KEEP:
  - id, title, slug, description
  - base_price (as reference price)
  - difficulty (enum: 'Easy', 'Moderate', 'Difficult', 'Extreme')
  - status (enum: 'Active', 'Inactive')
  - created_at, updated_at

REMOVE:
  - image (use trek_images table instead)
    Reason: Single source of truth, featured image can be first in trek_images

ADD:
  - owner_id (Foreign Key → users.id, staff member who created/manages)
    Reason: Clear content ownership, auditing, soft delete capability

RENAME:
  - Nothing

Why:
  - Clearer ownership model
  - No duplicate image handling
  - Trek becomes "product" managed by staff member
```

**Migration Strategy:**
```php
// Step 1: Add owner_id (nullable initially)
Schema::table('treks', function (Blueprint $table) {
    $table->foreignId('owner_id')
          ->nullable()
          ->constrained('users')
          ->cascadeOnDelete()
          ->after('id');
});

// Step 2: Backfill with admin user or actual owner if you have that data
Trek::whereNull('owner_id')->update(['owner_id' => 1]); // adjust as needed

// Step 3: Make not nullable
Schema::table('treks', function (Blueprint $table) {
    $table->foreignId('owner_id')->change();
    $table->dropColumn('image');
});
```

---

#### 3. **trek_bookings** ✅ NO CHANGE
```
EXCELLENT - TRANSACTIONAL DESIGN
Keep exactly:
  - id, user_id, departure_id, booking_reference
  - total_passengers
  - price_per_person (SNAPSHOT - critical)
  - subtotal (SNAPSHOT - critical)
  - discount_percent, discount_amount (SNAPSHOT - critical)
  - total_price (SNAPSHOT - critical)
  - status (enum: 'Pending', 'Confirmed', 'Cancelled')
  - created_at, updated_at

Why no change:
  - Historical price integrity is essential
  - Status values are clear
  - Relationship structure is correct
```

---

#### 4. **hotel_bookings** ✅ NO CHANGE
```
EXCELLENT - TRANSACTIONAL DESIGN
Keep exactly:
  - id, user_id, hotel_room_id, booking_reference
  - check_in, check_out, num_rooms, num_nights
  - price_per_night (SNAPSHOT - critical)
  - total_price (SNAPSHOT - critical)
  - status (enum: 'Pending', 'Confirmed', 'Cancelled')
  - created_at, updated_at

Why no change:
  - Same reasoning as trek_bookings
  - Price snapshots protect data integrity
  - Clear, practical transactional design
```

---

#### 5. **hotels** ✏️ MODIFY
```
KEEP:
  - id, owner_id (correct ownership model)
  - name, location, description
  - status (enum: 'Active', 'Inactive', 'Pending')
  - created_at, updated_at

REMOVE:
  - image (use hotel_images table instead)
    Reason: Consistency with trek handling, single source of truth

ADD:
  - Nothing essential

Why:
  - No duplicate image handling
  - Featured image = first in hotel_images with sort_order=1
```

**Migration:**
```php
Schema::table('hotels', function (Blueprint $table) {
    $table->dropColumn('image');
});
```

---

#### 6. **departures** ✅ NO CHANGE
```
Keep exactly:
  - id, trek_id, start_date, end_date
  - price (snapshot/reference price)
  - capacity, booked_seats
  - status (enum: 'Available', 'Full', 'Completed')
  - created_at, updated_at

Good design - schedule + capacity management separate from product
```

---

#### 7. **payments** ✏️ MINOR MODIFY
```
KEEP:
  - id, user_id, transaction_id
  - amount, currency
  - payment_for (enum: 'trek', 'hotel', 'gear')
  - reference_id (polymorphic ID)
  - gateway (enum: 'stripe', 'esewa', 'khalti')
  - status (enum: 'Pending', 'Success', 'Failed')
  - created_at, updated_at

MODIFY:
  - gateway_response: Keep as TEXT for now
    (Could be JSON in future, but text works fine)

ADD:
  - Nothing now (reconciliation fields can be v2)

Why:
  - Polymorphic payment design is clean and practical
  - Covers multi-type payments well
```

**Note**: gateway_response being TEXT is fine. If you need to query inside it, migrate to JSON column in v2.

---

#### 8. **reviews** ✅ NO CHANGE
```
Keep exactly:
  - id, user_id
  - reviewable_type, reviewable_id (polymorphic)
  - rating (unsigned tinyint 1-5)
  - comment (text, nullable)
  - created_at, updated_at

Perfect polymorphic design for Trek/Hotel/GearItem reviews
```

---

#### 9. **itineraries** ✅ KEEP, OPTIONAL ENHANCE
```
KEEP:
  - id, trek_id, day_number, title, description
  - created_at, updated_at

OPTIONAL (don't do now, v2):
  - location (string, where you stop that day)
  - elevation (int, for mountain treks)
  - distance (decimal, km traveled)
  - highlights (text, what to see)

Recommendation: Keep simple for now. Add details in v2 if needed.
```

---

#### 10. **hotel_rooms** ✅ KEEP WITH CAUTION
```
KEEP:
  - id, hotel_id, room_type, price_per_night, total_rooms
  - created_at, updated_at

CLARIFY BUSINESS LOGIC:
  - What is "total_rooms"?
    - Is it "total units of this type in hotel"? (static definition)
    - Or "available inventory"? (dynamic)
  
Recommendation: Treat as STATIC (units of this type in hotel)
  - Then "booked rooms" is tracked by hotel_bookings count
  - Don't try to sync inventory in this table

If you need reservation system inventory:
  - Query: COUNT(hotel_bookings) WHERE hotel_room_id=X AND check_in <= DATE AND check_out >= DATE
  - Don't duplicate in total_rooms
```

---

#### 11. **passengers** ✅ NO CHANGE
```
Keep exactly:
  - id, trek_booking_id, name, passport_no, age
  - created_at, updated_at

Why:
  - Age is practical (child discounts, group composition)
  - Passport is useful for international treks
  - Clear relationship to trek_booking
```

---

#### 12. **gear_items** ✅ NO CHANGE
```
Keep exactly:
  - id, name, type, description, daily_price, total_stock
  - image, status (enum: 'Active', 'Inactive')
  - created_at, updated_at

Good simplified design for basic gear rental
```

---

#### 13. **gear_rentals** ✅ NO CHANGE
```
Keep exactly:
  - id, user_id, gear_item_id, rental_reference
  - quantity, status, notes, expected_return_date
  - created_at, updated_at

Good fit with simplified gear booking model
```

---

#### 14. **trek_images** ✅ NO CHANGE
```
Keep exactly:
  - id, trek_id, path, is_placeholder, sort_order
  - created_at, updated_at

Good design. Single source of truth for trek images.
Featured image = First image in sort_order
```

---

#### 15. **hotel_images** ✅ NO CHANGE
```
Keep exactly:
  - id, hotel_id, path, sort_order
  - created_at, updated_at

Good design. Consistent with trek_images.
Featured image = First image in sort_order
```

---

## PART 3: STATUS VALUE STANDARDIZATION

### Current Inconsistencies
Different tables use different status values. This causes confusion and bugs.

### STANDARDIZED STATUS ENUMS BY TABLE

```php
// 1. User Approval (RENAMED FIELD)
users.approval_status = enum('pending', 'approved', 'rejected')

// 2. Content Status (Use for all publishable content)
treks.status = enum('Active', 'Inactive')
hotels.status = enum('Active', 'Inactive', 'Pending')
gear_items.status = enum('Active', 'Inactive')

// 3. Booking Status (Use for all bookings)
trek_bookings.status = enum('Pending', 'Confirmed', 'Cancelled')
hotel_bookings.status = enum('Pending', 'Confirmed', 'Cancelled')
gear_rentals.status = enum('Pending', 'Active', 'Returned', 'Cancelled')

// 4. Schedule Status (Events/departures)
departures.status = enum('Available', 'Full', 'Completed')

// 5. Payment Status
payments.status = enum('Pending', 'Success', 'Failed')
```

### Consistency Rules
1. **Content**: Active/Inactive/Pending
2. **Bookings**: Pending → Confirmed → Delivered (or Cancelled)
3. **Rentals**: Pending → Active → Returned/Cancelled
4. **Payments**: Pending → Success/Failed
5. **Users**: pending → approved/rejected

---

## PART 4: BEFORE vs AFTER SUMMARY

### Schema Changes Summary

| Table | Change | Impact | Risk |
|-------|--------|--------|------|
| users | is_approved → approval_status | Captures all approval states | LOW - add alongside, migrate later |
| treks | Add owner_id | Clear content ownership | LOW - foreign key, nullable initially |
| treks | Remove image | Single source of truth | LOW - migrate path to trek_images |
| hotels | Remove image | Consistency with treks | LOW - migrate path to hotel_images |
| All others | Keep as-is | Stability | NONE |

### Total Changes
- **4 tables with changes (users, treks, hotels, gear_items concept)**
- **1 table with field removal (image fields)**
- **1 table with field addition (treks.owner_id)**
- **11 tables completely stable**

---

## PART 5: NAMING CONVENTIONS TO FOLLOW

### Consistency Rules for Future Development

#### 1. **Foreign Keys**
```php
// Rule: {table_singular}_id
Foreign key to users → user_id
Foreign key to treks → trek_id
Foreign key to hotels → hotel_id
```

#### 2. **Timestamps**
```php
ALWAYS use: created_at, updated_at
Optional: deleted_at (for soft deletes)
Never: created_on, updated_on, date_created
```

#### 3. **Status Fields**
```php
Always use: status (never: state, flag, type)
Always use ENUM (never: string without constraint)
Values: PascalCase (Available, Pending) or lowercase (pending, active)
PICK ONE AND STICK: Use PascalCase for consistency
```

#### 4. **Boolean Fields**
```php
Prefix with: is_, has_, can_
Examples: is_approved ✗ (use approval_status instead)
          has_discount ✓
          can_book ✓
          
Rule: Use enums for multi-state (pending/approved/rejected)
      Use boolean ONLY for true/false binary states
```

#### 5. **References/Codes**
```php
booking_reference ✓
transaction_id ✓
rental_reference ✓
(clear naming, prefix with entity type)
```

#### 6. **Decimal Fields**
```php
Always use: decimal(10, 2) for money
           decimal(8, 2) for measurements
Examples: price_per_person, total_price, daily_price
```

#### 7. **Relationships**
```php
One-to-Many: table_id (user_id, hotel_id)
Many-to-Many: Use pivot table (if needed)
Polymorphic: {type}_type, {type}_id (reviewable_type, reviewable_id)
```

#### 8. **Route Model Binding**
```php
Use: id (primary key)
Route: /treks/{trek}
Slug routes: /treks/{slug} (document clearly)
```

---

## PART 6: FINAL RECOMMENDED SCHEMA

### Clean, Production-Ready Schema

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255),
    remember_token VARCHAR(100) NULL,
    role ENUM('admin', 'staff', 'customer', 'hotel_owner') DEFAULT 'customer',
    approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    phone VARCHAR(20) NULL,
    created_at TIMESTAMP, updated_at TIMESTAMP
);

CREATE TABLE treks (
    id BIGINT UNSIGNED PRIMARY KEY,
    owner_id BIGINT UNSIGNED NOT NULL, -- Staff member
    title VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    description TEXT,
    base_price DECIMAL(10, 2),
    difficulty ENUM('Easy', 'Moderate', 'Difficult', 'Extreme') DEFAULT 'Moderate',
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE trek_images (
    id BIGINT UNSIGNED PRIMARY KEY,
    trek_id BIGINT UNSIGNED NOT NULL,
    path VARCHAR(255),
    is_placeholder BOOLEAN DEFAULT false,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (trek_id) REFERENCES treks(id) ON DELETE CASCADE
);

CREATE TABLE itineraries (
    id BIGINT UNSIGNED PRIMARY KEY,
    trek_id BIGINT UNSIGNED NOT NULL,
    day_number INT,
    title VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (trek_id) REFERENCES treks(id) ON DELETE CASCADE
);

CREATE TABLE departures (
    id BIGINT UNSIGNED PRIMARY KEY,
    trek_id BIGINT UNSIGNED NOT NULL,
    start_date DATE,
    end_date DATE,
    price DECIMAL(10, 2),
    capacity INT,
    booked_seats INT DEFAULT 0,
    status ENUM('Available', 'Full', 'Completed') DEFAULT 'Available',
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (trek_id) REFERENCES treks(id) ON DELETE CASCADE
);

CREATE TABLE trek_bookings (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    departure_id BIGINT UNSIGNED NOT NULL,
    booking_reference VARCHAR(255) UNIQUE,
    total_passengers INT,
    price_per_person DECIMAL(10, 2),
    subtotal DECIMAL(10, 2),
    discount_percent INT DEFAULT 0,
    discount_amount DECIMAL(10, 2) DEFAULT 0,
    total_price DECIMAL(10, 2),
    status ENUM('Pending', 'Confirmed', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (departure_id) REFERENCES departures(id) ON DELETE CASCADE
);

CREATE TABLE passengers (
    id BIGINT UNSIGNED PRIMARY KEY,
    trek_booking_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255),
    passport_no VARCHAR(255) NULL,
    age INT,
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (trek_booking_id) REFERENCES trek_bookings(id) ON DELETE CASCADE
);

CREATE TABLE hotels (
    id BIGINT UNSIGNED PRIMARY KEY,
    owner_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255),
    location VARCHAR(255),
    description TEXT,
    status ENUM('Active', 'Inactive', 'Pending') DEFAULT 'Pending',
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE hotel_images (
    id BIGINT UNSIGNED PRIMARY KEY,
    hotel_id BIGINT UNSIGNED NOT NULL,
    path VARCHAR(255),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
);

CREATE TABLE hotel_rooms (
    id BIGINT UNSIGNED PRIMARY KEY,
    hotel_id BIGINT UNSIGNED NOT NULL,
    room_type VARCHAR(255),
    price_per_night DECIMAL(10, 2),
    total_rooms INT,
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
);

CREATE TABLE hotel_bookings (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    hotel_room_id BIGINT UNSIGNED NOT NULL,
    booking_reference VARCHAR(255) UNIQUE,
    check_in DATE,
    check_out DATE,
    num_rooms INT DEFAULT 1,
    num_nights INT,
    price_per_night DECIMAL(10, 2),
    total_price DECIMAL(10, 2),
    status ENUM('Pending', 'Confirmed', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (hotel_room_id) REFERENCES hotel_rooms(id) ON DELETE CASCADE
);

CREATE TABLE gear_items (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(255),
    type VARCHAR(255),
    description TEXT,
    daily_price DECIMAL(10, 2),
    total_stock INT,
    image VARCHAR(255) NULL,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP, updated_at TIMESTAMP
);

CREATE TABLE gear_rentals (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    gear_item_id BIGINT UNSIGNED NOT NULL,
    rental_reference VARCHAR(255) UNIQUE,
    quantity INT DEFAULT 1,
    status ENUM('Pending', 'Active', 'Returned', 'Cancelled') DEFAULT 'Pending',
    notes TEXT NULL,
    expected_return_date DATE NULL,
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (gear_item_id) REFERENCES gear_items(id) ON DELETE CASCADE
);

CREATE TABLE payments (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    transaction_id VARCHAR(255) UNIQUE,
    amount DECIMAL(10, 2),
    currency VARCHAR(10) DEFAULT 'USD',
    payment_for ENUM('trek', 'hotel', 'gear'),
    reference_id BIGINT UNSIGNED,
    gateway ENUM('stripe', 'esewa', 'khalti') NULL,
    status ENUM('Pending', 'Success', 'Failed') DEFAULT 'Pending',
    gateway_response TEXT NULL,
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX payment_reference (payment_for, reference_id)
);

CREATE TABLE reviews (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    reviewable_type VARCHAR(255),
    reviewable_id BIGINT UNSIGNED,
    rating TINYINT UNSIGNED,
    comment TEXT NULL,
    created_at TIMESTAMP, updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## PART 7: MIGRATION IMPLEMENTATION PLAN

### Phase 1: SAFE ADDITIONS (Do Now - Zero Risk)
```php
// 1. Add approval_status to users (keep is_approved for backward compatibility)
Schema::table('users', function (Blueprint $table) {
    $table->enum('approval_status', ['pending', 'approved', 'rejected'])
          ->default('pending')
          ->after('is_approved');
});

// 2. Add owner_id to treks
Schema::table('treks', function (Blueprint $table) {
    $table->foreignId('owner_id')
          ->nullable()
          ->constrained('users')
          ->cascadeOnDelete()
          ->after('id');
});

// Backfill with default admin user or NULL
Trek::whereNull('owner_id')->update(['owner_id' => 1]);

// Then make NOT NULL
Schema::table('treks', function (Blueprint $table) {
    $table->foreignId('owner_id')->change();
});
```

### Phase 2: IMAGE CLEANUP (After 2-3 releases)
```php
// After verifying no code uses treks.image or hotels.image
Schema::table('treks', function (Blueprint $table) {
    $table->dropColumn('image');
});

Schema::table('hotels', function (Blueprint $table) {
    $table->dropColumn('image');
});
```

### Phase 3: REMOVE OLD APPROVAL FIELD (After 6 months)
```php
// After all code uses approval_status
Schema::table('users', function (Blueprint $table) {
    $table->dropColumn('is_approved');
});
```

---

## PART 8: WHAT TO DO NOW vs WHAT TO DEFER

### DO NOW ✅ (Next sprint)
1. Add `users.approval_status` enum (non-breaking addition)
2. Add `treks.owner_id` foreign key (non-breaking addition)
3. Backfill data safely
4. Update all code to use new fields instead of old ones
5. Keep old fields for 1-2 releases (backward compatibility)

### DEFER TO v2 ⏳
1. Removing `users.is_approved` (do in 6+ months)
2. Removing `treks.image` and `hotels.image` (do in 2-3 releases)
3. Image gallery management UI improvements
4. Soft deletes for treks/hotels (add `deleted_at` when ready)
5. Role-permission tables (if you need complex ACL later)
6. Audit logs (payment, booking, user actions)
7. JSON gateway_response column migration (if needed)
8. Caching strategy for read-heavy operations

### DO NOT DO
1. Don't add microservice-level separation
2. Don't add event sourcing (premature)
3. Don't add complex permission tables (not needed yet)
4. Don't denormalize further (you're good)
5. Don't add new status values without updating standardization docs

---

## PART 9: FINAL RECOMMENDATION FOR PRODUCTION

### If I Were Designing This NOW for Small-to-Medium Production:

**What I'd Keep Exactly:**
- All booking tables (trek_bookings, hotel_bookings, gear_rentals) - transactional design is solid
- Departure concept - correct separation of concerns
- Polymorphic reviews - clean and practical
- Payment design - handles multi-product-type well
- User role enum - perfect for this stage
- Passenger manifest - practical for groups
- Image table structure - allows galleries

**What I'd Change Right Now:**
1. ✅ Remove `treks.image` and `hotels.image` fields
   - Use trek_images and hotel_images as single source of truth
   - Add convenience method: `Trek::image()` returns first image

2. ✅ Add `treks.owner_id`
   - Staff member who manages the trek
   - Enables ownership-based queries: `Trek::where('owner_id', auth()->id())`

3. ✅ Change `users.is_approved` → `approval_status` enum
   - Captures pending/approved/rejected states
   - More future-proof
   - Better for business logic

4. ✅ Standardize all status enums (listed above)
   - Create enum files in your Laravel app
   - Use them consistently
   - Makes code cleaner and more maintainable

5. ✅ Add indexes for common queries:
   ```php
   user_id, trek_id, hotel_id, departure_id
   (payment_for, reference_id)
   ```

**What I'd NOT Do Yet:**
- No role-permission tables (overkill for 4 roles)
- No audit tables (add when compliance is required)
- No event sourcing (premature optimization)
- No denormalization beyond current price snapshots
- No JSON columns yet (text works fine)

---

## PART 10: IMPLEMENTATION CHECKLIST

### Code Changes Needed

1. **Models** (Update relationships)
   ```php
   // Trek model
   public function owner() {
       return $this->belongsTo(User::class);
   }
   
   public function images() {
       return $this->hasMany(TrekImage::class)->orderBy('sort_order');
   }
   
   public function image() {
       return $this->images()->first();
   }
   
   // User model
   public function approval_status = 'approval_status'; // enum cast
   ```

2. **Controllers** (Update queries)
   ```php
   // No more using trek.image, use trek.images()
   // Update $trek->image to $trek->images()->first()
   ```

3. **Views/Templates**
   ```blade
   {{-- Remove: <img src="{{ $trek->image }}" /> --}}
   {{-- Use: <img src="{{ $trek->images()->first()->path }}" /> --}}
   ```

4. **Database Factory/Seeding**
   ```php
   // Update factories to include owner_id and image in trek_images
   ```

5. **API responses**
   ```php
   // If you have API, update to include all images array
   ```

---

## FINAL VERDICT ✅

**Your schema is 75% production-ready.** The 5 changes recommended are **LOW-RISK, HIGH-VALUE cleanup** that will:

✅ Improve clarity (owner_id, approval_status)
✅ Prevent bugs (single image source of truth)
✅ Increase consistency (standardized enums)
✅ Future-proof (approval states, role models)
✅ Maintain backward compatibility (add alongside, remove later)

**Timeline**: You can implement all changes in 1-2 sprints without breaking anything.

**After these changes**, your schema will be **excellent for production** at scale 1-100k users.

When you reach scale or need advanced features, you'll know exactly where to add:
- Audit logging
- Complex permissions
- Event sourcing
- Caching strategies
- Denormalization patterns
- Multitenancy

**But for right now? Simple, clean, maintainable. That's the goal.** ✨
