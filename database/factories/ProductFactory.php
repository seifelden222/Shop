<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productNames = [
            'Wireless Headphones', 'Smart Watch', 'Bluetooth Speaker', 'Laptop Computer',
            'Gaming Mouse', 'Mechanical Keyboard', 'USB-C Cable', 'Phone Case',
            'Power Bank', 'Webcam HD', 'Monitor 24 inch', 'Graphics Card',
            'SSD Drive', 'Router WiFi', 'Smartphone', 'Tablet',
            'Smart TV', 'Gaming Console', 'VR Headset', 'Drone Camera'
        ];

        $price = $this->faker->randomFloat(2, 10, 999);
        $name = $this->faker->randomElement($productNames);

        return [
            'category_id' => function () {
                return \App\Models\Category::inRandomOrder()->first()?->id 
                    ?? \App\Models\Category::factory()->create()->id;
            },
            'brand_id' => function () {
                return \App\Models\Brand::inRandomOrder()->first()?->id 
                    ?? \App\Models\Brand::factory()->create()->id;
            },
            'brand' => function (array $attributes) {
                $brand = \App\Models\Brand::find($attributes['brand_id']);
                return $brand?->name ?? 'Generic Brand';
            },
            'name' => $name,
            'description' => $this->faker->paragraphs(rand(1, 3), true),
            'price' => $price,
            'stock' => $this->faker->numberBetween(0, 100),
            'main_image' => null,
            'images' => null,
            'status' => $this->faker->randomElement(['published', 'published', 'published', 'archived', 'block']), // More published
            'published_at' => $this->faker->optional(0.9)->dateTimeBetween('-1 year', 'now'),
            'name_snapshot' => $name,
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }
}
