<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
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
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->email(),
            'customer_phone' => $this->faker->phoneNumber(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'notes' => $this->faker->optional()->sentence(),
            'total_price' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->optional()->paragraph(),
            'address' => $this->faker->address(),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'shipping_cost' => $this->faker->randomFloat(2, 0, 50),
            'order_number' => $this->faker->uuid(),
            'currency' => 'USD',
            'provider_order_id' => $this->faker->optional()->uuid(),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'stripe', 'cash_on_delivery', 'bank_transfer']),
            'payment_status' => $this->faker->randomElement(['pending', 'paid', 'failed', 'refunded']),
            'transaction_id' => $this->faker->optional()->uuid(),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
