<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('orderItems.product')->get();
        if (!$orders)     return redirect()->back()->with('error', 'No orders found.');

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $products = Product::select('id', 'name', 'price')->get();
        if (!$products) return redirect()->back()->with('error', 'No products found to create an order.');

        return view('orders.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        try {


            $validated = $request->validated();
            // Further processing like creating the order and order items would go here.
            return DB::transaction(function () use ($validated) {
                $order = Order::create($validated);
                $items = $validated['items'] ?? [];
                $totalAmount = 0;
                foreach ($items as $item) {
                    $quantity = (int) ($item['quantity'] ?? 1);
                    $unitPrice = (float) ($item['unit_price'] ?? 0.0);
                    $totalPrice = $quantity * $unitPrice;

                    $order->orderItems()->create([
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
                return redirect()->route('orders.show')->with('success', 'Order created successfully.');
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
        if (!$order) return redirect()->back()->with('error', 'Order not found.');

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
    public function update(OrderRequest $request, Order $order)
    {
        $validated = $request->validated();
        return DB::transaction(function () use ($validated, $order) {
            $order->update([
                'vadlidated' => $validated,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
            ]);
            $items = $validated['items'] ?? [];
            $totalAmount = 0;

            // Delete existing items
            $order->orderItems()->delete();

            foreach ($items as $item) {
                $quantity = (int) ($item['quantity'] ?? 1);
                $unitPrice = (float) ($item['unit_price'] ?? 0.0);
                $totalPrice = $quantity * $unitPrice;

                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'] ?? 'Unknown Product',
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);

                $totalAmount += $totalPrice;
            }
            $shipping_cost = $validated['shipping_cost'] ?? 0.0;
            // format as string to match the model's decimal cast
            $order->total_price = number_format((float) $totalAmount + $shipping_cost, 2, '.', '');
            $order->save();
            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order, $id)
    {

        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
