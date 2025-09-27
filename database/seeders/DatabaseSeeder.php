<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user only if doesn't exist
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
            $this->command->info('Test user created successfully!');
        } else {
            $this->command->info('Test user already exists, skipping...');
        }

        // Seed shop data
        $this->call([
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
        ]);

        $this->command->info('Shop data seeded successfully!');
    }
}
