<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Product;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        Log::info('Cart Index Debug', [
            'user_id' => $userId,
            'user_name' => Auth::user()->name ?? 'Unknown',
            'total_carts_in_db' => Cart::count(),
            'user_carts_count' => Cart::where('user_id', $userId)->count(),
        ]);

        $carts = Cart::with('product')->asCart()->where('user_id', $userId)->get();
        $subtotal = (float) $carts->sum('total_price');
        
        // Add image from product to each cart item
        $carts = $carts->map(function ($cart): Cart {
            $cart->image = $cart->product->main_image ?? null;
            return $cart;
        });
        
        // Convert to array format that the view expects
        $carts = $carts->toArray();

        return view('cart', compact('carts', 'subtotal'));
    }

    public function quickAdd(Request $request, CartRequest $cartRequest)
    {
        $cartRequest->validated();

        $quantity = $request->get('quantity', 1);
        $productId = $request->get('product_id');

        return DB::transaction(function () use ($productId, $quantity) {
            // Check if item already exists in cart
            $existingCart = Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->whereNull('order_id')
                ->first();

            if ($existingCart) {
                // Update existing cart item
                $existingCart->quantity += $quantity;
                $existingCart->total_price = $existingCart->quantity * $existingCart->unit_price;
                $existingCart->save();
                $message = 'Cart updated successfully! Quantity increased.';
            } else {
                // Create new cart item
                $product = Product::findOrFail($productId);
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'total_price' => $quantity * $product->price,
                ]);
                $message = 'Product added to cart successfully!';
            }

            // Debug info
            Log::info('Cart operation completed', [
                'user_id' => Auth::id(),
                'message' => $message,
                'cart_count' => Auth::user()->cart_count ?? 0
            ]);

            return redirect()->route('welcome')->with('success', $message);
        });
    }

    public function update(CartRequest $request, Cart $cart)
    {
        $validated = $request->validated();
        $product = Product::findOrFail($validated['product_id']);
        $cart->update([
            'quantity' => $validated['quantity'],
            'unit_price' => $product->price,
            'total_price' => $validated['quantity'] * $product->price,
        ]);
        return redirect()->route('cart.index')->with('success', 'Cart item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * This function handles both single item deletion and clearing all cart
     */
    public function destroy($cartId = null)
    {
        if ($cartId === 'clear-all' || $cartId === 'all') {
            // Clear all cart items for current user
            Cart::where('user_id', Auth::id())->whereNull('order_id')->delete();
            return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
        } else {
            // Delete specific cart item
            $cart = Cart::where('id', $cartId)
                       ->where('user_id', Auth::id())
                       ->whereNull('order_id')
                       ->first();
            
            if ($cart) {
                $cart->delete();
                return redirect()->route('cart.index')->with('success', 'Cart item deleted successfully.');
            }
            
            return redirect()->route('cart.index')->with('error', 'Item not found.');
        }
    }
}
