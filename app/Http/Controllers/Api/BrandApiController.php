<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BrandApiController extends Controller
{
    /**
     * Display a listing of brands.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Brand::where('is_active', true);

            // Search by name
            if ($request->has('search') && $request->search) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
            }

            $brands = $query->orderBy('name', 'asc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Brands retrieved successfully',
                'data' => $brands
            ], 200);

        } catch (\Exception $e) {
            Log::error('Brands API Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve brands',
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display the specified brand.
     *
     * @param Brand $brand
     * @return JsonResponse
     */
    public function show(Brand $brand): JsonResponse
    {
        try {
            // Check if brand is active
            if (!$brand->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Brand not found or not active'
                ], 404);
            }

            // Load products
            $brand->load(['products' => function($query) {
                $query->where('status', 'published');
            }]);

            return response()->json([
                'success' => true,
                'message' => 'Brand retrieved successfully',
                'data' => $brand
            ], 200);

        } catch (\Exception $e) {
            Log::error('Brand Show API Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve brand',
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get total products and pricing analytics for all brands.
     *
     * @return JsonResponse
     */
    public function analytics(): JsonResponse
    {
        try {
            // Get all active brands with their published products
            $brands = Brand::where('is_active', true)
                ->with(['products' => function($query) {
                    $query->where('status', 'published');
                }])
                ->get();

            $brandsData = [];
            $grandTotalProducts = 0;
            $grandTotalValue = 0;

            foreach ($brands as $brand) {
                $totalProducts = $brand->products->count();
                $totalValue = $brand->products->sum('price');
                
                $brandData = [
                    'brand_id' => $brand->id,
                    'brand_name' => $brand->name,
                    'total_products' => $totalProducts,
                    'total_value' => round($totalValue, 2),
                    'average_price' => $totalProducts > 0 ? round($totalValue / $totalProducts, 2) : 0,
                    'is_active' => $brand->is_active
                ];

                $brandsData[] = $brandData;
                $grandTotalProducts += $totalProducts;
                $grandTotalValue += $totalValue;
            }

            // Sort by total value descending
            usort($brandsData, function($a, $b) {
                return $b['total_value'] <=> $a['total_value'];
            });

            $analytics = [
                'brands_analytics' => $brandsData,
                'summary' => [
                    'total_active_brands' => count($brands),
                    'grand_total_products' => $grandTotalProducts,
                    'grand_total_value' => round($grandTotalValue, 2),
                    'average_products_per_brand' => count($brands) > 0 ? round($grandTotalProducts / count($brands), 2) : 0,
                    'average_value_per_brand' => count($brands) > 0 ? round($grandTotalValue / count($brands), 2) : 0
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => 'Brand analytics retrieved successfully',
                'data' => $analytics
            ], 200);

        } catch (\Exception $e) {
            Log::error('Brand Analytics API Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve brand analytics',
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get products for a specific brand.
     *
     * @param Brand $brand
     * @param Request $request
     * @return JsonResponse
     */
    public function products(Brand $brand, Request $request): JsonResponse
    {
        try {
            if (!$brand->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Brand not found or not active'
                ], 404);
            }

            $query = $brand->products()->where('status', 'published');

            // Price range filter
            if ($request->has('min_price') && $request->min_price) {
                $query->where('price', '>=', $request->min_price);
            }

            if ($request->has('max_price') && $request->max_price) {
                $query->where('price', '<=', $request->max_price);
            }

            // Sort options
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $allowedSorts = ['name', 'price', 'created_at', 'stock'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = min($request->get('per_page', 15), 50);
            $products = $query->with('category')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Brand products retrieved successfully',
                'brand' => [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'total_products' => $brand->products()->where('status', 'published')->count()
                ],
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Brand Products API Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve brand products',
                'error' => 'Internal server error'
            ], 500);
        }
    }
}