<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Simplify gear rental to basic CRUD system.
     * Payment and physical logistics handled separately.
     */
    public function up(): void
    {
        // Modify gear_items table to simplify
        Schema::table('gear_items', function (Blueprint $table) {
            if (Schema::hasColumn('gear_items', 'available_stock')) {
                $table->dropColumn('available_stock');
            }
            if (!Schema::hasColumn('gear_items', 'description')) {
                $table->text('description')->nullable()->after('type');
            }
            if (!Schema::hasColumn('gear_items', 'status')) {
                $table->enum('status', ['Active', 'Inactive'])->default('Active')->after('image');
            }
        });

        // Modify gear_rentals table - simplification for basic CRUD
        Schema::table('gear_rentals', function (Blueprint $table) {
            // Drop complex fields
            if (Schema::hasColumn('gear_rentals', 'start_date')) {
                $table->dropColumn(['start_date', 'end_date', 'num_days', 'daily_price', 'total_price']);
            }
            
            // Add simplified fields
            if (!Schema::hasColumn('gear_rentals', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('gear_rentals', 'expected_return_date')) {
                $table->date('expected_return_date')->nullable();
            }
            
            // Update status enum to include Returned and Completed
            if (Schema::hasColumn('gear_rentals', 'status')) {
                $table->dropColumn('status');
            }
            $table->enum('status', ['Pending', 'Active', 'Returned', 'Cancelled'])
                ->default('Pending')
                ->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert gear_items
        Schema::table('gear_items', function (Blueprint $table) {
            if (!Schema::hasColumn('gear_items', 'available_stock')) {
                $table->integer('available_stock')->after('total_stock');
            }
            if (Schema::hasColumn('gear_items', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('gear_items', 'status')) {
                $table->dropColumn('status');
            }
        });

        // Revert gear_rentals
        Schema::table('gear_rentals', function (Blueprint $table) {
            if (Schema::hasColumn('gear_rentals', 'notes')) {
                $table->dropColumn(['notes', 'expected_return_date']);
            }
            if (Schema::hasColumn('gear_rentals', 'status')) {
                $table->dropColumn('status');
            }
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('num_days');
            $table->decimal('daily_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['Pending', 'Active', 'Returned', 'Cancelled'])->default('Pending');
        });
    }
};
