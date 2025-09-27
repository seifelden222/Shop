<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company() . ' ' . $this->faker->randomElement(['Inc', 'Corp', 'Ltd', 'Co', 'Group']),
            'description' => $this->faker->paragraph(2),
            'slug' => $this->faker->unique()->slug(),
            'image' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
