<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropForeign(['responded_by_staff_id']);
            $table->dropColumn(['staff_response', 'responded_by_staff_id', 'responded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->text('staff_response')->nullable();
            $table->foreignId('responded_by_staff_id')->nullable()->constrained('users');
            $table->timestamp('responded_at')->nullable();
        });
    }
};
