<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true) . ' ' . $this->faker->randomElement(['Store', 'Shop', 'Market', 'Outlet', 'Hub']),
            'description' => $this->faker->paragraph(3),
            'slug' => $this->faker->unique()->slug(),
            'image' => null, // We'll handle images separately if needed
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
