<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Get latest 6 products for featured section
            $featuredProducts = Product::with('category')
                ->where('status', 'published')
                ->latest()
                ->take(6)
                ->get();

            // Get all categories for category section
            $categories = Category::where('is_active', true)
                ->take(6)
                ->get();

            // Get some stats for display
            $stats = [
                'total_products' => Product::where('status', 'published')->count(),
                'total_categories' => Category::where('is_active', true)->count(),
                'total_brands' => Brand::where('is_active', true)->count(),
            ];

            return view('welcome', compact('featuredProducts', 'categories', 'stats'));
            
        } catch (\Exception $e) {
            // If there's an error, return view with empty data
            $featuredProducts = collect();
            $categories = collect();
            $stats = ['total_products' => 0, 'total_categories' => 0, 'total_brands' => 0];
            
            return view('welcome', compact('featuredProducts', 'categories', 'stats'));
        }
    }
}
