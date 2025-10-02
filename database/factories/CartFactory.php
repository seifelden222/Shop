<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $unitPrice = $this->faker->randomFloat(2, 5, 200);
        $totalPrice = $quantity * $unitPrice;

        return [
            'order_id' => null, // Will be set by seeder when needed
            'product_id' => function () {
                return \App\Models\Product::inRandomOrder()->first()?->id 
                    ?? \App\Models\Product::factory()->create()->id;
            },
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->first()?->id 
                    ?? \App\Models\User::factory()->create()->id;
            },
            'product_name' => function (array $attributes) {
                $product = \App\Models\Product::find($attributes['product_id']);
                return $product?->name ?? $this->faker->words(3, true);
            },
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
        ];
    }

    /**
     * Cart items that belong to an order
     */
    public function forOrder(\App\Models\Order $order): static
    {
        return $this->state(fn (array $attributes) => [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
        ]);
    }

    /**
     * Cart items that don't belong to an order (shopping cart)
     */
    public function asCart(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_id' => null,
        ]);
    }
}
