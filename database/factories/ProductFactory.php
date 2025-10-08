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

        $faker = $this->faker;

 

        if ($faker) {
            $price = $faker->randomFloat(2, 10, 999);
            $name = $faker->randomElement($productNames);
            $description = $faker->paragraphs(rand(1, 3), true);
            $stock = $faker->numberBetween(0, 100);
            $status = $faker->randomElement(['published', 'published', 'published', 'archived', 'block']);
            $published_at = $faker->optional(0.9)->dateTimeBetween('-1 year', 'now');
            $created_at = $faker->dateTimeBetween('-6 months', 'now');
        } else {
            $price = (float) number_format(rand(1000, 99900) / 100, 2);
            $name = $productNames[array_rand($productNames)];
            $description = 'Description for ' . $name;
            $stock = rand(0, 100);
            $status = 'published';
            $published_at = now();
            $created_at = now();
        }

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
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
            'main_image' => null,
            'images' => null,
            'status' => $status,
            'published_at' => $published_at,
            'name_snapshot' => $name,
            'created_at' => $created_at,
            'updated_at' => now(),
        ];
    }
}
