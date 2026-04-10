<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE trek_bookings MODIFY status ENUM('pending','confirmed','cancellation_requested','completed','cancelled') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE hotel_bookings MODIFY status ENUM('pending','confirmed','cancellation_requested','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE trek_bookings MODIFY status ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE hotel_bookings MODIFY status ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
