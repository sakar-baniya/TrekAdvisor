<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'approval_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                    ->default('pending')
                    ->after('role');
            });
        }

        if (Schema::hasColumn('users', 'is_approved')) {
            DB::table('users')
                ->where('is_approved', true)
                ->update(['approval_status' => 'approved']);

            DB::table('users')
                ->where('is_approved', false)
                ->update(['approval_status' => 'pending']);

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_approved');
            });
        }

        if (Schema::hasColumn('payments', 'payment_for')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->renameColumn('payment_for', 'payable_type');
            });
        }

        if (Schema::hasColumn('payments', 'reference_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->renameColumn('reference_id', 'payable_id');
            });
        }

        if (Schema::hasColumn('trek_images', 'is_placeholder')) {
            DB::statement("UPDATE trek_images SET is_placeholder = CASE WHEN sort_order = 0 THEN 1 ELSE 0 END");

            Schema::table('trek_images', function (Blueprint $table) {
                $table->renameColumn('is_placeholder', 'is_primary');
            });
        }

        if (Schema::hasColumn('passengers', 'name')) {
            Schema::table('passengers', function (Blueprint $table) {
                $table->renameColumn('name', 'full_name');
            });
        }

        if (Schema::hasColumn('passengers', 'passport_no')) {
            Schema::table('passengers', function (Blueprint $table) {
                $table->renameColumn('passport_no', 'passport_number');
            });
        }

        DB::table('treks')->where('status', 'Active')->update(['status' => 'active']);
        DB::table('treks')->where('status', 'Inactive')->update(['status' => 'inactive']);
        DB::table('treks')->where('difficulty', 'Easy')->update(['difficulty' => 'easy']);
        DB::table('treks')->where('difficulty', 'Moderate')->update(['difficulty' => 'moderate']);
        DB::table('treks')->where('difficulty', 'Difficult')->update(['difficulty' => 'difficult']);
        DB::table('treks')->where('difficulty', 'Extreme')->update(['difficulty' => 'extreme']);

        DB::table('departures')->where('status', 'Available')->update(['status' => 'available']);
        DB::table('departures')->where('status', 'Full')->update(['status' => 'full']);
        DB::table('departures')->where('status', 'Completed')->update(['status' => 'completed']);

        DB::table('trek_bookings')->where('status', 'Pending')->update(['status' => 'pending']);
        DB::table('trek_bookings')->where('status', 'Confirmed')->update(['status' => 'confirmed']);
        DB::table('trek_bookings')->where('status', 'Cancelled')->update(['status' => 'cancelled']);

        DB::table('hotels')->where('status', 'Active')->update(['status' => 'active']);
        DB::table('hotels')->where('status', 'Inactive')->update(['status' => 'inactive']);
        DB::table('hotels')->where('status', 'Pending')->update(['status' => 'pending']);

        DB::table('hotel_bookings')->where('status', 'Pending')->update(['status' => 'pending']);
        DB::table('hotel_bookings')->where('status', 'Confirmed')->update(['status' => 'confirmed']);
        DB::table('hotel_bookings')->where('status', 'Cancelled')->update(['status' => 'cancelled']);



        DB::table('payments')->where('status', 'Pending')->update(['status' => 'pending']);
        DB::table('payments')->where('status', 'Success')->update(['status' => 'success']);
        DB::table('payments')->where('status', 'Failed')->update(['status' => 'failed']);

        DB::statement("ALTER TABLE treks MODIFY difficulty ENUM('easy','moderate','difficult','extreme') NOT NULL DEFAULT 'moderate'");
        DB::statement("ALTER TABLE treks MODIFY status ENUM('active','inactive') NOT NULL DEFAULT 'active'");
        DB::statement("ALTER TABLE departures MODIFY status ENUM('available','full','completed') NOT NULL DEFAULT 'available'");
        DB::statement("ALTER TABLE trek_bookings MODIFY status ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE hotels MODIFY status ENUM('active','inactive','pending') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE hotel_bookings MODIFY status ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE payments MODIFY status ENUM('pending','success','failed') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'is_approved')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_approved')->default(true)->after('phone');
            });
        }

        DB::table('users')
            ->where('approval_status', 'approved')
            ->update(['is_approved' => true]);

        DB::table('users')
            ->whereIn('approval_status', ['pending', 'rejected'])
            ->update(['is_approved' => false]);

        if (Schema::hasColumn('payments', 'payable_type')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->renameColumn('payable_type', 'payment_for');
            });
        }

        if (Schema::hasColumn('payments', 'payable_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->renameColumn('payable_id', 'reference_id');
            });
        }

        if (Schema::hasColumn('trek_images', 'is_primary')) {
            Schema::table('trek_images', function (Blueprint $table) {
                $table->renameColumn('is_primary', 'is_placeholder');
            });
        }

        if (Schema::hasColumn('passengers', 'full_name')) {
            Schema::table('passengers', function (Blueprint $table) {
                $table->renameColumn('full_name', 'name');
            });
        }

        if (Schema::hasColumn('passengers', 'passport_number')) {
            Schema::table('passengers', function (Blueprint $table) {
                $table->renameColumn('passport_number', 'passport_no');
            });
        }
    }
};

