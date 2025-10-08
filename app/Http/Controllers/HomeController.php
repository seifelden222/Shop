<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Get latest 8 products for featured section with better filtering
            $featuredProducts = Product::with(['category', 'brand'])
                ->where('status', 'published')
                ->where('stock', '>', 0)
                ->latest()
                ->take(8)
                ->get();

            // Get active categories with products count
            $categories = Category::where('is_active', true)
                ->withCount(['products' => function ($query) {
                    $query->where('status', 'published')->where('stock', '>', 0);
                }])
                ->orderBy('products_count', 'desc')
                ->take(6)
                ->get();

            // Get some stats for display
            $stats = [
                'total_products' => Product::where('status', 'published')->where('stock', '>', 0)->count(),
                'total_categories' => Category::where('is_active', true)->count(),
                'total_brands' => Brand::where('is_active', true)->count(),
            ];

            return view('welcome', compact('featuredProducts', 'categories', 'stats'));
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('HomeController error: ' . $e->getMessage());
            
            // If there's an error, return view with empty data
            $featuredProducts = collect();
            $categories = collect();
            $stats = ['total_products' => 0, 'total_categories' => 0, 'total_brands' => 0];
            
            return view('welcome', compact('featuredProducts', 'categories', 'stats'));
        }
    }
}
