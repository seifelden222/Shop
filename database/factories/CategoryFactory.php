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
        $faker = $this->faker;


        if ($faker) {
            $name = $faker->unique()->words(2, true) . ' ' . $faker->randomElement(['Store', 'Shop', 'Market', 'Outlet', 'Hub']);
            $description = $faker->paragraph(3);
            $slug = $faker->unique()->slug();
        } else {
            $unique = substr(sha1((string) mt_rand() . microtime(true)), 0, 6);
            $name = 'Category ' . $unique;
            $description = 'Description for ' . $name;
            $slug = 'category-' . $unique;
        }

        return [
            'name' => $name,
            'description' => $description,
            'slug' => $slug,
            'image' => null, // We'll handle images separately if needed
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
