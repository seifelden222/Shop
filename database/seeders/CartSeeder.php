<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating shopping cart items...');

        // Get existing users and products
        $users = \App\Models\User::all();
        $products = \App\Models\Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('No users or products found. Please run UserSeeder and ProductSeeder first.');
            return;
        }

        // Create cart items for some users (items not yet ordered)
        foreach ($users->take(8) as $user) {
            $numberOfCartItems = rand(1, 4);
            
            for ($i = 0; $i < $numberOfCartItems; $i++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $unitPrice = $product->price ?? rand(10, 200);
                
                // Check if this product is already in the user's cart
                $existingCartItem = \App\Models\Cart::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->whereNull('order_id')
                    ->first();

                if ($existingCartItem) {
                    // Update quantity if item already exists
                    $existingCartItem->update([
                        'quantity' => $existingCartItem->quantity + $quantity,
                        'total_price' => ($existingCartItem->quantity + $quantity) * $existingCartItem->unit_price,
                    ]);
                } else {
                    // Create new cart item
                    \App\Models\Cart::create([
                        'order_id' => null, // Not yet ordered
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'product_name' => $product->name,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $quantity * $unitPrice,
                    ]);
                }
            }
        }

        $cartItemsCount = \App\Models\Cart::whereNull('order_id')->count();
        $this->command->info("Created {$cartItemsCount} shopping cart items!");
    }
}
