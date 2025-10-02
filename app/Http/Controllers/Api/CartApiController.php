<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Type\TrueType;

class CartApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Authentication required.',
                'note' => 'Use /api/carts-test for testing without auth'
            ], 401);
        }

        $carts = Cart::asCart()->where('user_id', Auth::id())->get();
        $subtotal = (float) $carts->sum('total_price');

        if ($carts->isEmpty() ||  $subtotal == 0) {
            return response()->json([
                'message' => 'Your cart is empty.',
                'carts' => [],
                'subtotal' => 0,
            ]);
        }
        return response()->json([
            'carts' => $carts,
            'subtotal' => $subtotal,
        ]);
    }

    /**
     * Test method without authentication
     */
    public function test()
    {
        $carts = Cart::asCart()->take(10)->get(); // Get first 10 carts for testing
        $subtotal = (float) $carts->sum('total_price');

        return response()->json([
            'message' => 'Test API - No authentication required',
            'carts' => $carts,
            'subtotal' => $subtotal,
            'count' => $carts->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartRequest $request)
    {
        try {
            $validated = $request->validated();
            return DB::transaction(function () use ($validated) {
                $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $validated['product_id'])
                    ->whereNull('order_id')->first();

                if ($cart) {
                    $cart->quantity += $validated['quantity'];
                    $cart->total_price = $cart->quantity * $cart->unit_price;
                    $cart->save();
                    return response()->json([
                        'message' => 'Cart updated successfully.',
                        'cart' => $cart,
                    ], 200);
                }
                $product = Product::findOrFail($validated['product_id']);
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $validated['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $validated['quantity'] * $product->price,
                ]);

                return response()->json([
                    'message' => 'Product added to cart successfully.',
                    'cart' => $cart,
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cart = Cart::asCart()->where('user_id', Auth::id())->find($id);
        if (!$cart) {
            return response()->json([

                'message' => 'Cart not found.',
            ], 404);
        }
        return response()->json([
            'cart' => $cart,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartRequest $request, string $id)
    {
        try {

            $validated = $request->validated();
            return DB::transaction(function () use ($validated, $id) {

                $cart = Cart::asCart()->where('user_id', Auth::id())->find($id);
                if (!$cart) {
                    return response()->json([
                        'message' => 'Cart not found.',
                    ], 404);
                }
                $product = Product::findOrFail($validated['product_id']);
                $cart->update([
                    'quantity' => $validated['quantity'],
                    'unit_price' => $product->price,   // عشان لو السعر اتغير
                    'total_price' => $validated['quantity'] * $product->price,
                ]);
                return response()->json([
                    'message' => 'Cart item updated successfully.',
                    'cart' => $cart,
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cart = Cart::asCart()->where('user_id', Auth::id())->find($id);
        if (!$cart) {
            return response()->json([
                'message' => 'Cart not found.',
            ], 404);
        }
        $cart->delete();
        return response()->json([
            'message' => 'Cart item deleted successfully.',
        ]);
    }
}
