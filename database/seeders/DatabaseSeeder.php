<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call all other seeders in order
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            ProductReviewSeeder::class,
            ShoppingCartSeeder::class,
            OrderSeeder::class,
        ]);
    }
}