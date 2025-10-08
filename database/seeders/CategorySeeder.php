<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific categories
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Latest electronic devices, gadgets, and accessories for your digital lifestyle.',
                'slug' => 'electronics',
                'image' => 'categories/electronics.svg',
                'is_active' => true,
            ],
            [
                'name' => 'Clothing & Fashion',
                'description' => 'Trendy clothing, shoes, and fashion accessories for men and women.',
                'slug' => 'clothing-fashion',
                'image' => 'categories/fashion.svg',
                'is_active' => true,
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Everything you need for your home, garden, and outdoor spaces.',
                'slug' => 'home-garden',
                'image' => 'categories/home-garden.svg',
                'is_active' => true,
            ],
            [
                'name' => 'Sports & Outdoors',
                'description' => 'Sports equipment, outdoor gear, and fitness accessories.',
                'slug' => 'sports-outdoors',
                'image' => null, // سيظهر placeholder
                'is_active' => true,
            ],
            [
                'name' => 'Books & Media',
                'description' => 'Books, movies, music, and educational materials.',
                'slug' => 'books-media',
                'image' => null, // سيظهر placeholder
                'is_active' => true,
            ],
            [
                'name' => 'Health & Beauty',
                'description' => 'Health products, beauty items, and personal care essentials.',
                'slug' => 'health-beauty',
                'image' => null, // سيظهر placeholder
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::updateOrCreate(
                ['name' => $category['name']], // Search criteria
                $category // Data to create or update
            );
        }

        // Create additional random categories only if we don't have enough
        $currentCount = \App\Models\Category::count();
        if ($currentCount < 10) {
            \App\Models\Category::factory(10 - $currentCount)->create();
        }
    }
}
