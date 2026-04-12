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
            $table->string('currency', 10)->default('NPR');
            $table->enum('payable_type', ['trek', 'hotel']);
            $table->unsignedBigInteger('payable_id');
            $table->enum('gateway', ['stripe', 'esewa', 'khalti'])->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->text('gateway_response')->nullable();
            $table->timestamps();

            $table->index(['payable_type', 'payable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

