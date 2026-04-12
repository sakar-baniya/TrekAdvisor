# Quick Implementation Guide - Schema Changes

## VISUAL: What Changes

### ❌ REMOVE
```
treks.image (string)        → Use trek_images.path instead
hotels.image (string)       → Use hotel_images.path instead
users.is_approved (boolean) → Use approval_status enum instead
```

### ✅ ADD
```
treks.owner_id → FK to users (staff member managing trek)
users.approval_status → enum('pending', 'approved', 'rejected')
```

### ✅ KEEP UNCHANGED (11 tables)
```
trek_bookings, hotel_bookings, departures, payments, reviews,
itineraries, hotel_rooms, passengers, gear_items, gear_rentals,
trek_images, hotel_images
```

---

## Step-by-Step Implementation

### Step 1: Create New Migration (TODAY)
```bash
php artisan make:migration add_approval_status_and_owner_id
```

### Step 2: In Migration File
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Phase 1: Add new columns (non-breaking)
        
        // 1. Add approval_status to users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                      ->default('pending')
                      ->after('is_approved');
            }
        });

        // 2. Add owner_id to treks
        Schema::table('treks', function (Blueprint $table) {
            if (!Schema::hasColumn('treks', 'owner_id')) {
                $table->foreignId('owner_id')
                      ->nullable()
                      ->constrained('users')
                      ->cascadeOnDelete()
                      ->first();
            }
        });
    }

    public function down(): void
    {
        // Keep old fields intact, only remove new ones
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });

        Schema::table('treks', function (Blueprint $table) {
            if (Schema::hasColumn('treks', 'owner_id')) {
                $table->dropForeignKeyIfExists(['owner_id']);
                $table->dropColumn('owner_id');
            }
        });
    }
};
```

### Step 3: Run Migration
```bash
php artisan migrate
```

### Step 4: Backfill Data
```php
// In tinker or seeder
use App\Models\User;
use App\Models\Trek;

// Backfill approval_status based on is_approved
User::where('is_approved', true)
    ->update(['approval_status' => 'approved']);

User::where('is_approved', false)
    ->update(['approval_status' => 'rejected']);

// Backfill owner_id for treks (assign to admin)
$admin = User::where('role', 'admin')->first();
Trek::whereNull('owner_id')->update(['owner_id' => $admin->id]);

// Or assign to first staff user if exists
$staff = User::where('role', 'staff')->first();
if ($staff) {
    Trek::whereNull('owner_id')->update(['owner_id' => $staff->id]);
}
```

### Step 5: Update Models

#### User Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsEnumCollection;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone',
        'is_approved', 'approval_status' // BOTH for now
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'approval_status' => 'string', // enum: pending, approved, rejected
    ];

    // Use in code like:
    // if ($user->approval_status === 'approved') { ... }
    
    public function ownedTreks()
    {
        return $this->hasMany(Trek::class, 'owner_id');
    }
}
```

#### Trek Model
```php
<?php

namespace App\Models;

class Trek extends Model
{
    protected $fillable = [
        'owner_id',      // NEW
        'title', 'slug', 'description', 'base_price',
        'difficulty', 'status'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(TrekImage::class)->orderBy('sort_order');
    }

    /**
     * Get featured/primary image
     * Replaces old single image field
     */
    public function image()
    {
        return $this->images()->first();
    }
}
```

### Step 6: Update Views

#### Before (OLD - using single image field)
```blade
<img src="{{ $trek->image }}" alt="{{ $trek->title }}" />
```

#### After (NEW - using image table)
```blade
@if($trek->image)
    <img src="{{ $trek->image->path }}" alt="{{ $trek->title }}" />
@else
    <img src="/images/placeholder.png" alt="No image" />
@endif

<!-- OR better: use image() helper -->
<img src="{{ $trek->image?->path ?? '/images/placeholder.png' }}" alt="{{ $trek->title }}" />
```

#### Multiple Images (Gallery)
```blade
@forelse($trek->images as $image)
    <img src="{{ $image->path }}" alt="{{ $trek->title }}" />
@empty
    <p>No images available</p>
@endforelse
```

### Step 7: Update Controllers Where Needed

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Models\Trek;

class TrekController extends Controller
{
    public function index()
    {
        // Query changes
        $treks = Trek::with('owner', 'images')  // Eager load
                     ->latest()
                     ->paginate();

        return view('admin.treks.index', ['treks' => $treks]);
    }

    public function create()
    {
        // Owner is auto-set to current user or staff member
        return view('admin.treks.create', [
            'trek' => new Trek(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => 'required|string|unique:treks',
            'description' => 'required|string',
            'base_price' => 'required|numeric',
            'difficulty' => 'required|in:Easy,Moderate,Difficult,Extreme',
            'status' => 'required|in:Active,Inactive',
        ]);

        // NEW: Auto-assign owner
        $validated['owner_id'] = auth()->id();

        Trek::create($validated);

        return redirect()->route('admin.treks.index')
                       ->with('success', 'Trek created');
    }

    public function uploadImage(Request $request, Trek $trek)
    {
        $request->validate(['image' => 'required|image|max:2048']);

        $path = $request->file('image')->store('treks', 'public');

        // Create in trek_images, not single image field
        $trek->images()->create([
            'path' => '/storage/' . $path,
            'sort_order' => $trek->images()->max('sort_order') + 1,
        ]);

        return back()->with('success', 'Image added');
    }
}
```

### Step 8: Seeders/Factories

```php
<?php

namespace Database\Factories;

use App\Models\Trek;
use App\Models\User;

class TrekFactory extends Factory
{
    protected $model = Trek::class;

    public function definition()
    {
        return [
            'owner_id' => User::factory()->create(['role' => 'staff'])->id,
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'base_price' => $this->faker->numberBetween(100, 5000),
            'difficulty' => $this->faker->randomElement(['Easy', 'Moderate', 'Difficult', 'Extreme']),
            'status' => 'Active',
        ];
    }
}
```

---

## Testing the Changes

### Unit Test Example
```php
<?php

namespace Tests\Unit;

use App\Models\Trek;
use App\Models\TrekImage;
use App\Models\User;
use Tests\TestCase;

class TrekTest extends TestCase
{
    public function test_trek_has_owner()
    {
        $user = User::factory()->create(['role' => 'staff']);
        $trek = Trek::factory()->create(['owner_id' => $user->id]);

        $this->assertTrue($trek->owner->is($user));
    }

    public function test_trek_image_helper_returns_first_image()
    {
        $trek = Trek::factory()->create();
        
        $trek->images()->createMany([
            ['path' => '/images/trek1.jpg', 'sort_order' => 1],
            ['path' => '/images/trek2.jpg', 'sort_order' => 2],
        ]);

        $this->assertEquals('/images/trek1.jpg', $trek->image()->path);
    }

    public function test_user_approval_status()
    {
        $user = User::factory()->create(['approval_status' => 'pending']);

        $this->assertEquals('pending', $user->approval_status);
    }
}
```

---

## Deployment Checklist

- [ ] Create migration
- [ ] Run `php artisan migrate`
- [ ] Backfill data (approval_status, owner_id)
- [ ] Update User model
- [ ] Update Trek model
- [ ] Update Trek views/templates
- [ ] Update Trek controllers
- [ ] Update factories/seeders
- [ ] Write/update tests
- [ ] Test locally
- [ ] Commit & push
- [ ] Deploy to staging
- [ ] Test in staging
- [ ] Deploy to production
- [ ] Monitor for errors
- [ ] Keep is_approved in code for 2-3 releases
- [ ] Remove is_approved in Phase 2 (later)

---

## FAQ

**Q: When should I remove the old fields?**
A: Keep `is_approved` for 2-3 releases. After all code uses `approval_status`, remove it in a minor version bump.

**Q: Do I need to migrate existing image paths?**
A: If you have data in `treks.image`, you should migrate those to `trek_images` as a data migration before removing the column.

**Q: Can customers be trek owners?**
A: Currently, assign owner_id to 'staff' users. If customers need to be trek owners later, update the role enum and add a migration.

**Q: What about hotels?**
A: Hotels already have correct `owner_id` design. Remove `hotels.image` field in Phase 2, same as treks.

**Q: Should I rename status values to be consistent?**
A: Yes, but in Phase 2. Create enums in your app, update status values gradually.
