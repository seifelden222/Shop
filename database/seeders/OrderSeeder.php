<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating orders...');

        // Create users if they don't exist
        $users = \App\Models\User::all();
        if ($users->count() < 5) {
            \App\Models\User::factory(5)->create();
            $users = \App\Models\User::all();
        }

        // Create products if they don't exist
        $products = \App\Models\Product::all();
        if ($products->count() < 10) {
            \App\Models\Product::factory(10)->create();
            $products = \App\Models\Product::all();
        }

        // Create 20 orders with different statuses and dates
        $orders = \App\Models\Order::factory(20)->create([
            'user_id' => fn() => $users->random()->id,
        ]);

        $this->command->info('Creating order items (cart items with orders)...');

        // Create cart items for each order
        foreach ($orders as $order) {
            $numberOfItems = rand(1, 5);
            
            for ($i = 0; $i < $numberOfItems; $i++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $unitPrice = $product->price ?? rand(10, 200);
                
                \App\Models\Cart::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'user_id' => $order->user_id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $quantity * $unitPrice,
                ]);
            }

            // Update order total price based on cart items
            $totalPrice = $order->cart()->sum('total_price');
            $order->update([
                'total_price' => $totalPrice + $order->shipping_cost,
            ]);
        }

        $this->command->info('Orders and order items created successfully!');
    }
}
