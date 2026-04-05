<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trek_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price', 10, 2);
            $table->integer('capacity');
            $table->integer('booked_seats')->default(0);
            $table->enum('status', ['available', 'full', 'completed'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departures');
    }
};
