<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\Cart;

class CompleteShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating complete shop data...');

        // Create additional users if needed
        $usersCount = User::count();
        if ($usersCount < 15) {
            User::factory(15 - $usersCount)->create();
            $this->command->info('Created ' . (15 - $usersCount) . ' additional users');
        }

        // Create orders with different payment statuses
        $paidOrders = Order::factory(15)->create([
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        $pendingOrders = Order::factory(8)->create([
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        $failedOrders = Order::factory(3)->create([
            'payment_status' => 'failed',
            'status' => 'cancelled',
        ]);

        $this->command->info('Created additional orders with various statuses');

        // Add cart items to the new orders
        $allNewOrders = $paidOrders->concat($pendingOrders)->concat($failedOrders);
        
        foreach ($allNewOrders as $order) {
            $itemsCount = rand(1, 6);
            
            for ($i = 0; $i < $itemsCount; $i++) {
                $product = \App\Models\Product::inRandomOrder()->first();
                $quantity = rand(1, 4);
                $unitPrice = $product?->price ?? rand(10, 300);
                
                Cart::create([
                    'order_id' => $order->id,
                    'product_id' => $product?->id,
                    'user_id' => $order->user_id,
                    'product_name' => $product?->name ?? 'Sample Product',
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $quantity * $unitPrice,
                ]);
            }

            // Update order total
            $totalPrice = $order->cart()->sum('total_price');
            $order->update([
                'total_price' => $totalPrice + ($order->shipping_cost ?? 0),
            ]);
        }

        // Create more shopping cart items for active users
        $activeUsers = User::inRandomOrder()->take(12)->get();
        foreach ($activeUsers as $user) {
            $cartItemsCount = rand(1, 5);
            
            for ($i = 0; $i < $cartItemsCount; $i++) {
                $product = \App\Models\Product::where('status', 'published')->inRandomOrder()->first();
                if ($product) {
                    Cart::create([
                        'order_id' => null,
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'product_name' => $product->name,
                        'quantity' => rand(1, 3),
                        'unit_price' => $product->price,
                        'total_price' => rand(1, 3) * $product->price,
                    ]);
                }
            }
        }

        $this->command->info('Complete shop data created successfully!');
        
        // Display summary
        $this->command->table(
            ['Entity', 'Count'],
            [
                ['Users', User::count()],
                ['Products', \App\Models\Product::count()],
                ['Categories', \App\Models\Category::count()],
                ['Brands', \App\Models\Brand::count()],
                ['Orders', Order::count()],
                ['Cart Items (Orders)', Cart::whereNotNull('order_id')->count()],
                ['Cart Items (Shopping)', Cart::whereNull('order_id')->count()],
                ['Total Revenue', '$' . number_format(Order::where('payment_status', 'paid')->sum('total_price'), 2)],
            ]
        );
    }
}