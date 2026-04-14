<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trek_bookings', function (Blueprint $table) {
            $table->foreignId('assigned_staff_id')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->text('internal_note')->nullable()->after('assigned_staff_id');
        });

        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->foreignId('assigned_staff_id')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->text('internal_note')->nullable()->after('assigned_staff_id');
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('message');
            $table->timestamp('read_at')->nullable()->after('is_read');
            $table->text('staff_response')->nullable()->after('read_at');
            $table->foreignId('responded_by_staff_id')->nullable()->after('staff_response')->constrained('users')->nullOnDelete();
            $table->timestamp('responded_at')->nullable()->after('responded_by_staff_id');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('responded_by_staff_id');
            $table->dropColumn(['responded_at', 'staff_response', 'read_at', 'is_read']);
        });

        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_staff_id');
            $table->dropColumn('internal_note');
        });

        Schema::table('trek_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_staff_id');
            $table->dropColumn('internal_note');
        });
    }
};
