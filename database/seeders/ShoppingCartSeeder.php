<?php

namespace Database\Seeders;

use App\Models\ShoppingCart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShoppingCartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (admin user created in DatabaseSeeder)
        $user = User::first();
        
        // Get all products
        $products = Product::all();

        if (!$user || $products->isEmpty()) {
            return; // Skip if no user or products exist
        }

        $cartItems = [
            [
                'user_id' => $user->id,
                'product_id' => $products->where('slug', 'nu-official-male-polo-shirt')->first()?->id,
                'quantity' => 2,
                'size' => 'L',
                'color' => 'Navy Blue',
                'price' => 450.00,
            ],
            [
                'user_id' => $user->id,
                'product_id' => $products->where('slug', 'nu-bulldogs-t-shirt')->first()?->id,
                'quantity' => 1,
                'size' => 'M',
                'color' => 'Maroon',
                'price' => 199.00, // Using discount price
            ],
            [
                'user_id' => $user->id,
                'product_id' => $products->where('slug', 'nu-official-female-blouse')->first()?->id,
                'quantity' => 1,
                'size' => 'S',
                'color' => 'White',
                'price' => 480.00,
            ],
        ];

        foreach ($cartItems as $item) {
            if ($item['product_id']) { // Only create if product exists
                ShoppingCart::create($item);
            }
        }
    }
}