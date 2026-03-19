<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('treks', function (Blueprint $table) {
            $table->unsignedInteger('duration_days')->nullable()->after('difficulty');
            $table->unsignedInteger('max_altitude')->nullable()->after('duration_days');
        });
    }

    public function down(): void
    {
        Schema::table('treks', function (Blueprint $table) {
            $table->dropColumn(['duration_days', 'max_altitude']);
        });
    }
};
