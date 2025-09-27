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
            'Wireless Headphones',
            'Smart Watch',
            'Bluetooth Speaker',
            'Laptop Computer',
            'Gaming Mouse',
            'Mechanical Keyboard',
            'USB-C Cable',
            'Phone Case',
            'Power Bank',
            'Webcam HD',
            'Monitor 24 inch',
            'Graphics Card',
            'SSD Drive',
            'Router WiFi',
            'Smartphone',
            'Tablet',
            'Smart TV',
            'Gaming Console',
            'VR Headset',
            'Drone Camera'
        ];

        return [
            'category_id' => \App\Models\Category::factory(),
            'brand' => $this->faker->randomElement([
                'Apple', 'Samsung', 'Sony', 'Dell', 'HP', 'Asus', 'Logitech', 
                'Razer', 'Corsair', 'Anker', 'Belkin', 'JBL', 'Bose'
            ]),
            'name' => $this->faker->randomElement($productNames),
            'description' => $this->faker->paragraphs(2, true),
            'price' => $this->faker->randomFloat(2, 10, 999),
            'stock' => $this->faker->numberBetween(0, 100),
            'main_image' => null,
            'images' => json_encode([]), // Empty JSON array
            'status' => $this->faker->randomElement(['published', 'archived', 'block']),
            'published_at' => $this->faker->optional(0.8)->dateTimeBetween('-1 year', 'now'),
            'name_snapshot' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
