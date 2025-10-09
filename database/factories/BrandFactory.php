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
        $faker = $this->faker;

  

        if ($faker) {
            $name = $faker->unique()->company() . ' ' . $faker->randomElement(['Inc', 'Corp', 'Ltd', 'Co', 'Group']);
            $description = $faker->paragraph(2);
            $slug = $faker->unique()->slug();
        } else {
            $unique = substr(sha1((string) mt_rand() . microtime(true)), 0, 6);
            $name = 'Brand ' . $unique;
            $description = 'Brand description for ' . $name;
            $slug = 'brand-' . $unique;
        }

        return [
            'name' => $name,
            'description' => $description,
            'slug' => $slug,
            'image' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
