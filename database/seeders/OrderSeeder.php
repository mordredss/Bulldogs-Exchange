<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
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

        // Create first order
        $order1 = Order::create([
            'user_id' => $user->id,
            'order_number' => 'NU-' . str_pad(1, 6, '0', STR_PAD_LEFT),
            'status' => 'completed',
            'total_amount' => 899.00,
            'shipping_address' => '123 Samson Road, Caloocan City, Metro Manila',
            'billing_address' => '123 Samson Road, Caloocan City, Metro Manila',
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'paid',
            'notes' => 'Please handle with care.',
            'created_at' => now()->subDays(7),
            'updated_at' => now()->subDays(7),
        ]);

        // Create order items for order 1
        $order1Items = [
            [
                'order_id' => $order1->id,
                'product_id' => $products->where('slug', 'nu-official-male-polo-shirt')->first()?->id,
                'quantity' => 1,
                'price' => 450.00,
                'size' => 'L',
            ],
            [
                'order_id' => $order1->id,
                'product_id' => $products->where('slug', 'nu-official-female-blouse')->first()?->id,
                'quantity' => 1,
                'price' => 480.00,
                'size' => 'M',
            ],
        ];

        foreach ($order1Items as $item) {
            if ($item['product_id']) {
                OrderItem::create($item);
            }
        }

        // Create second order (pending)
        $order2 = Order::create([
            'user_id' => $user->id,
            'order_number' => 'NU-' . str_pad(2, 6, '0', STR_PAD_LEFT),
            'status' => 'pending',
            'total_amount' => 648.00,
            'shipping_address' => '123 Samson Road, Caloocan City, Metro Manila',
            'billing_address' => '123 Samson Road, Caloocan City, Metro Manila',
            'payment_method' => 'gcash',
            'payment_status' => 'pending',
            'notes' => 'Rush order - needed for orientation.',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        // Create order items for order 2
        $order2Items = [
            [
                'order_id' => $order2->id,
                'product_id' => $products->where('slug', 'nu-bulldogs-t-shirt')->first()?->id,
                'quantity' => 2,
                'price' => 199.00, // Using discount price
                'size' => 'M',
            ],
            [
                'order_id' => $order2->id,
                'product_id' => $products->where('slug', 'nu-official-male-polo-shirt')->first()?->id,
                'quantity' => 1,
                'price' => 450.00,
                'size' => 'XL',
            ],
        ];

        foreach ($order2Items as $item) {
            if ($item['product_id']) {
                OrderItem::create($item);
            }
        }

        // Create third order (processing)
        $order3 = Order::create([
            'user_id' => $user->id,
            'order_number' => 'NU-' . str_pad(3, 6, '0', STR_PAD_LEFT),
            'status' => 'processing',
            'total_amount' => 450.00,
            'shipping_address' => '456 M.H. Del Pilar Street, Malolos, Bulacan',
            'billing_address' => '456 M.H. Del Pilar Street, Malolos, Bulacan',
            'payment_method' => 'bank_transfer',
            'payment_status' => 'paid',
            'notes' => 'Gift wrapping requested.',
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subHours(12),
        ]);

        // Create order items for order 3
        $order3Items = [
            [
                'order_id' => $order3->id,
                'product_id' => $products->where('slug', 'nu-official-male-polo-shirt')->first()?->id,
                'quantity' => 1,
                'price' => 450.00,
                'size' => 'S',
            ],
        ];

        foreach ($order3Items as $item) {
            if ($item['product_id']) {
                OrderItem::create($item);
            }
        }
    }
}