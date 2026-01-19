<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gear_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gear_item_id')->constrained()->cascadeOnDelete();
            $table->string('rental_reference')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('quantity')->default(1);
            $table->integer('num_days');
            $table->decimal('daily_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['Pending', 'Active', 'Returned', 'Cancelled'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gear_rentals');
    }
};