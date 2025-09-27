<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing categories to assign products to them
        $categories = \App\Models\Category::all();
        
        if ($categories->isEmpty()) {
            // If no categories exist, create some first
            $this->call(CategorySeeder::class);
            $categories = \App\Models\Category::all();
        }

        // Create specific featured products
        $featuredProducts = [
            [
                'category_id' => $categories->where('name', 'Electronics')->first()?->id ?? $categories->first()->id,
                'brand' => 'Apple',
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with advanced camera system, A17 Pro chip, and titanium design.',
                'price' => 999.99,
                'stock' => 50,
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'category_id' => $categories->where('name', 'Electronics')->first()?->id ?? $categories->first()->id,
                'brand' => 'Samsung',
                'name' => 'Galaxy S24 Ultra',
                'description' => 'Premium Android smartphone with S Pen, exceptional camera, and large display.',
                'price' => 899.99,
                'stock' => 30,
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'category_id' => $categories->where('name', 'Electronics')->first()?->id ?? $categories->first()->id,
                'brand' => 'Sony',
                'name' => 'WH-1000XM5 Headphones',
                'description' => 'Industry-leading noise canceling wireless headphones with premium sound quality.',
                'price' => 349.99,
                'stock' => 75,
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'category_id' => $categories->where('name', 'Sports & Outdoors')->first()?->id ?? $categories->first()->id,
                'brand' => 'Nike',
                'name' => 'Air Max 270',
                'description' => 'Comfortable running shoes with Max Air unit and modern design.',
                'price' => 159.99,
                'stock' => 100,
                'status' => 'published',
                'published_at' => now(),
            ],
        ];

        foreach ($featuredProducts as $product) {
            \App\Models\Product::create($product);
        }

        // Create random products for each category
        foreach ($categories as $category) {
            \App\Models\Product::factory(10)->create([
                'category_id' => $category->id,
            ]);
        }
    }
}
