<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->enum('payment_for', ['trek', 'hotel', 'gear']);
            $table->unsignedBigInteger('reference_id');
            $table->enum('gateway', ['stripe', 'esewa', 'khalti'])->nullable();
            $table->enum('status', ['Pending', 'Success', 'Failed'])->default('Pending');
            $table->text('gateway_response')->nullable();
            $table->timestamps();

            $table->index(['payment_for', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};