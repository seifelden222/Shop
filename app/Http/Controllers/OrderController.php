<?php

namespace App\Http\Controllers;

use App\Events\EventSent;
use App\Http\Requests\EditOrderRquest;
use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user(); 
        $query = Order::with('orderItems.product');
        if ($user && ! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }
        $orders = $query->get();
        if ($orders->isEmpty()) return redirect()->back()->with('error', 'No orders found.');

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create(Request $request)

    public function create()
    {

        // Prepare cart data for checkout view
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $cart = Cart::with('product')->asCart()->where('user_id', $user->id)->get();
        if ($cart->isEmpty()) return redirect()->route('cart.index')->with('error', 'Your cart is empty.');

        // Map image and convert to array for the blade
        $cart = $cart->map(function ($c) {
            $c->image = $c->product->main_image ?? null;
            return $c;
        })->toArray();

        $total = (float) collect($cart)->sum('total_price');
        return view('orders.create', compact('cart', 'total'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        $user = Auth::user();

        $cartItems = Cart::with('product')->asCart()->where('user_id', $user->id)->get();
        if ($cartItems->isEmpty()) return redirect()->route('cart.index')->with('error', 'Your cart is empty.');

        $validated = $request->validated();

        try {

            return DB::transaction(function () use ($validated, $cartItems, $user) {
                // Calculate total first
                $total = $cartItems->sum('total_price');
                $shippingCost = $validated['shipping_cost'] ?? 0;

                $order = Order::create($validated + [
                    'user_id' => $user->id,
                    'total_price' => $total + $shippingCost,
                    'order_number' => Str::uuid(),
                    'status' => 'pending',
                ]);

                foreach ($cartItems as $item) {
                    $item->update(['order_id' => $order->id]);
                }
                if ($order) {
                    $cartItems = Cart::with('product')->where('order_id', $order->id)->get();
                    Event::dispatch(new EventSent($order->user, $order, $cartItems));
                }
                return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully.');
            });
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'An error occurred while creating the order. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        $this->authorize('view', $order);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $products = Product::select('id', 'name', 'price')->get();
        if (!$products) return redirect()->back()->with('error', 'No products found to create an order.');

        return view('orders.edit', compact('order', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditOrderRquest $request, Order $order)
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated, $order) {
            // Update order basic info
            $order->update($validated);

            // Only update items if they are provided
            if (isset($validated['items']) && !empty($validated['items'])) {
                $items = $validated['items'];
                $totalAmount = 0;

                // Delete existing items
                $order->orderItems()->delete();

                foreach ($items as $item) {
                    $quantity = (int) ($item['quantity'] ?? 1);
                    $unitPrice = (float) ($item['unit_price'] ?? 0.0);
                    $totalPrice = $quantity * $unitPrice;

                    $order->orderItems()->create([
                        'user_id' => $order->user_id,
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'] ?? 'Unknown Product',
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                    ]);



                    $totalAmount += $totalPrice;
                }

                $shipping_cost = $validated['shipping_cost'] ?? 0.0;
                $order->total_price = $totalAmount + $shipping_cost;
                $order->save();

                $cartItems = Cart::with('product')->where('order_id', $order->id)->get();
                Event::dispatch(new EventSent($order->user, $order, $cartItems));
            }

            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        if ($order->count() > 0) {
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
        } else {
            return redirect()->route('welcome')->with('success', 'Order deleted successfully.');
        }
    }
}
