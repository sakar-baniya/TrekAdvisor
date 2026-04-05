<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            'amount' => fake()->randomFloat(2, 50, 5000),
            'currency' => 'USD',
            'payable_type' => fake()->randomElement(['trek', 'hotel', 'gear']),
            'payable_id' => 1, // Override in seeder
            'gateway' => fake()->randomElement(['stripe', 'esewa', 'khalti']),
            'status' => 'Success',
            'gateway_response' => json_encode(['status' => 'success', 'message' => 'Payment authorized']),
        ];
    }
}

