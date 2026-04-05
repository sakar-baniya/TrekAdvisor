<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hotel_room_id')->constrained()->cascadeOnDelete();
            $table->string('booking_reference')->unique();
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('num_rooms')->default(1);
            $table->integer('num_nights');
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_bookings');
    }
};
