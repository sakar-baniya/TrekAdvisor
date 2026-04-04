<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Apply all schema improvements at once
     * - Add approval_status to users (replaces is_approved)
     * - Remove single image fields from treks/hotels
     */
    public function up(): void
    {
        // 1. Add approval_status to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                      ->default('pending')
                      ->after('role');
            }
        });

        // 2. Remove single image fields - use image tables instead
        Schema::table('treks', function (Blueprint $table) {
            if (Schema::hasColumn('treks', 'image')) {
                $table->dropColumn('image');
            }
        });

        Schema::table('hotels', function (Blueprint $table) {
            if (Schema::hasColumn('hotels', 'image')) {
                $table->dropColumn('image');
            }
        });
    }

    public function down(): void
    {
        // Revert changes
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });

        Schema::table('treks', function (Blueprint $table) {
            if (!Schema::hasColumn('treks', 'image')) {
                $table->string('image')->nullable();
            }
        });

        Schema::table('hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('hotels', 'image')) {
                $table->string('image')->nullable();
            }
        });
    }
};
