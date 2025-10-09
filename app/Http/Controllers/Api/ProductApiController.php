<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductApiController extends Controller
{
    /**
     * Display a listing of products.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Product::with(['category', 'brandRelation'])
                           ->where('status', 'published');

            // Filter by category
            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            // Filter by brand
            if ($request->has('brand_id') && $request->brand_id) {
                $query->where('brand_id', $request->brand_id);
            }

            // Search by name
            if ($request->has('search') && $request->search) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
            }

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
            $perPage = min($request->get('per_page', 15), 50); // Max 50 items per page
            $products = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully',
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Products API Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display the specified product.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        try {
            // Check if product is published
            if ($product->status !== 'published') {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found or not available'
                ], 404);
            }

            // Load relationships
            $product->load(['category', 'brandRelation']);

            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully',
                'data' => $product
            ], 200);

        } catch (\Exception $e) {
            Log::error('Product Show API Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product',
                'error' => 'Internal server error'
            ], 500);
        }
    }
}