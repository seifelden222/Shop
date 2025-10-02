<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EditOrderRquest;
use App\Http\Requests\Api\StoreOrderRquest;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('orderItems.product')->get();
        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRquest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $cartItems  = Cart::with('product')->asCart()->where('user_id', $user->id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty.'], 400);
        }
        $validated = $request->validated();
        try {

            return DB::transaction(function () use ($validated, $user, $cartItems) {
                $total = $cartItems->sum('total_price');
                $shippingCost = $validated['shipping_cost'] ?? 0;

                // Use shipping_address as default for address if not provided
                if (empty($validated['address'])) {
                    $validated['address'] = $validated['shipping_address'];
                }

                $order = Order::create($validated + [
                    'user_id' => $user->id,
                    'total_price' => $total + $shippingCost,
                    'order_number' => Str::uuid(),
                    'status' => 'pending',
                ]);

                foreach ($cartItems as $item) {
                    $item->update([
                        'order_id' => $order->id
                    ]);
                }

                return response()->json($order, 201);
            });
        } catch (\Exception $e) {
            Log::error('Failed to create order: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'cart_items_count' => $cartItems->count(),
                'validated_data' => $validated
            ]);
            return response()->json(['error' => 'Failed to create order', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order = Order::with('orderItems.product')->find($order->id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditOrderRquest $request, Order $order)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if user owns the order or is admin
        if ($order->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['error' => 'Forbidden - You can only edit your own orders'], 403);
        }

        $validated = $request->validated();
        return DB::transaction(function () use ($validated, $order) {
            $order->update($validated);

            if (isset($validated['items']) && !empty($validated['items'])) {
                $items = $validated['items'];
                
                $order->orderItems()->delete();
                $totalAmount = 0;
                foreach ($items as $item) {
                    $itemTotal = $item['quantity'] * $item['unit_price'];
                    $totalAmount += $itemTotal;
                    $order->orderItems()->create([
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $itemTotal,
                    ]);

            }
                $shippingCost = $validated['shipping_cost'] ?? 0;
                $order->update(['total_price' => $totalAmount + $shippingCost]);
            }
            $order->refresh();
            return response()->json($order->load('orderItems.product'));
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try{

            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            // Check if user owns the order or is admin
            if ($order->user_id !== $user->id && $user->role !== 'admin') {
                return response()->json(['error' => 'Forbidden - You can only delete your own orders'], 403);
            }
            
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }
            $order->delete();
            return response()->json(['message' => 'Order deleted successfully']);
        }catch(\Exception $e){
           
            return response()->json(['error' => 'Failed to delete order', 'message' => $e->getMessage()], 500);
        }
    }
}
