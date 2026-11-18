<?php

namespace Database\Seeders;

use App\Models\ProductReview;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
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

        $reviews = [
            [
                'product_id' => $products->where('slug', 'nu-official-male-polo-shirt')->first()?->id,
                'user_id' => $user->id,
                'rating' => 5,
                'comment' => 'Excellent quality polo shirt! Very comfortable and the fit is perfect. Highly recommended for all male students.',
            ],
            [
                'product_id' => $products->where('slug', 'nu-official-male-polo-shirt')->first()?->id,
                'user_id' => $user->id,
                'rating' => 4,
                'comment' => 'Good quality material, but sizing runs a bit large. Overall satisfied with the purchase.',
            ],
            [
                'product_id' => $products->where('slug', 'nu-official-female-blouse')->first()?->id,
                'user_id' => $user->id,
                'rating' => 5,
                'comment' => 'Beautiful blouse! Very professional looking and comfortable to wear all day. Love the NU emblem design.',
            ],
            [
                'product_id' => $products->where('slug', 'nu-bulldogs-t-shirt')->first()?->id,
                'user_id' => $user->id,
                'rating' => 5,
                'comment' => 'Amazing t-shirt! The Bulldogs logo looks great and the cotton is so soft. Perfect for casual wear.',
            ],
            [
                'product_id' => $products->where('slug', 'nu-bulldogs-t-shirt')->first()?->id,
                'user_id' => $user->id,
                'rating' => 4,
                'comment' => 'Good quality t-shirt. The design is nice but the color faded slightly after a few washes.',
            ],
        ];

        foreach ($reviews as $review) {
            if ($review['product_id']) { // Only create if product exists
                ProductReview::create($review);
            }
        }
    }
}