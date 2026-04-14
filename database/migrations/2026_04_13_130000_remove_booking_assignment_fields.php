<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trek_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_staff_id');
            $table->dropColumn('internal_note');
        });

        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_staff_id');
            $table->dropColumn('internal_note');
        });
    }

    public function down(): void
    {
        Schema::table('trek_bookings', function (Blueprint $table) {
            $table->foreignId('assigned_staff_id')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->text('internal_note')->nullable()->after('assigned_staff_id');
        });

        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->foreignId('assigned_staff_id')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->text('internal_note')->nullable()->after('assigned_staff_id');
        });
    }
};
