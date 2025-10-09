<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific popular brands
        $brands = [
            [
                'name' => 'Apple',
                'description' => 'Premium technology products including iPhones, iPads, MacBooks, and accessories.',
                'slug' => 'apple',
                'is_active' => true,
            ],
            [
                'name' => 'Samsung',
                'description' => 'Leading manufacturer of smartphones, TVs, home appliances, and electronic devices.',
                'slug' => 'samsung',
                'is_active' => true,
            ],
            [
                'name' => 'Sony',
                'description' => 'Entertainment and technology company known for PlayStation, cameras, and audio equipment.',
                'slug' => 'sony',
                'is_active' => true,
            ],
            [
                'name' => 'Nike',
                'description' => 'World-renowned sportswear and athletic equipment brand.',
                'slug' => 'nike',
                'is_active' => true,
            ],
            [
                'name' => 'Adidas',
                'description' => 'Global sports brand offering footwear, apparel, and accessories.',
                'slug' => 'adidas',
                'is_active' => true,
            ],
            [
                'name' => 'Microsoft',
                'description' => 'Technology corporation known for Windows, Xbox, Surface, and software products.',
                'slug' => 'microsoft',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            \App\Models\Brand::firstOrCreate(
                ['name' => $brand['name']], 
                $brand
            );
        }

        // Create additional random brands only if we don't have enough
        $currentCount = \App\Models\Brand::count();
        if ($currentCount < 16) {
            \App\Models\Brand::factory(16 - $currentCount)->create();
        }
    }
}
