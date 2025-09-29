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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::asCart()->where('user_id', Auth::id())->get();
        $subtotal = (float) $carts->sum('total_price');

        return view('carts.index', compact('carts', 'subtotal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $products = Product::select('id', 'name', 'price')->get();

    //     return view('carts.create', compact('products'));
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'quantity' => 'integer|min:1|max:99'
    //     ]); 

    //     $quantity = $request->get('quantity', 1);
    //     $productId = $request->get('product_id');
        
    //     return DB::transaction(function () use ($productId, $quantity) {
    //         // Check if item already exists in cart
    //         $existingCart = Cart::where('user_id', Auth::id())
    //             ->where('product_id', $productId)
    //             ->whereNull('order_id')
    //             ->first();

    //         if ($existingCart) {
    //             // Update existing cart item
    //             $existingCart->quantity += $quantity;
    //             $existingCart->total_price = $existingCart->quantity * $existingCart->unit_price;
    //             $existingCart->save();
    //             $message = 'Cart updated successfully! Quantity increased.';
    //         } else {
    //             // Create new cart item
    //             $product = Product::findOrFail($productId);
    //             Cart::create([
    //                 'user_id' => Auth::id(),
    //                 'product_id' => $product->id,
    //                 'product_name' => $product->name,
    //                 'quantity' => $quantity,
    //                 'unit_price' => $product->price,
    //                 'total_price' => $quantity * $product->price,
    //             ]);
    //             $message = 'Product added to cart successfully!';
    //         }

    //         // Debug info
    //         Log::info('Cart operation completed', [
    //             'user_id' => Auth::id(),
    //             'message' => $message,
    //             'cart_count' => Auth::user()->cart_count ?? 0
    //         ]);
            
    //         return redirect()->route('welcome')->with('success', $message);
    //     });
    // }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)

    {
        // $carts =Cart::findOr($cart->id);
        return view('carts.show', compact('cart'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Cart $cart)
    // {
    //     $products = Product::select('id', 'name', 'price')->get();
    //     return view('carts.edit', compact('cart', 'products'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartRequest $request, Cart $cart)
    {
        $validated = $request->validated();
        $product = Product::findOrFail($validated['product_id']);
        $cart->update([

            'quantity' => $validated['quantity'],
            'unit_price' => $product->price,
            'total_price' => $validated['quantity'] * $product->price,
        ]);
        return redirect()->route('carts.index')->with('success', 'Cart item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('carts.index')->with('success', 'Cart item deleted successfully.');
    }


        public function quickAdd(Request $request ,CartRequest $cartRequest)
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
}
