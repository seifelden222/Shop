<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Prefer the framework-provided faker. If it's not available (dev deps
        // not installed), fall back to a simple unique string generator so
        // seeds can still run in CI or production-like environments.
        $faker = $this->faker;

        if ($faker) {
            $name = $faker->name();
            $email = $faker->unique()->safeEmail();
        } else {
            $unique = substr(sha1((string) mt_rand() . microtime(true)), 0, 8);
            $name = 'User ' . $unique;
            $email = 'user+' . $unique . '@example.com';
        }

        return [
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
