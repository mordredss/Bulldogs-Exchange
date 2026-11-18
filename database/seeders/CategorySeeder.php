<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // Main Categories
            [
                'name' => 'Uniforms',
                'slug' => 'uniforms',
                'description' => 'Official NU student and faculty uniforms',
                'parent_id' => null,
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Wearables',
                'slug' => 'wearables',
                'description' => 'NU branded clothing and accessories',
                'parent_id' => null,
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'NU branded accessories and items',
                'parent_id' => null,
                'sort_order' => 3,
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Get parent categories for subcategories
        $uniformsCategory = Category::where('slug', 'uniforms')->first();
        $wearablesCategory = Category::where('slug', 'wearables')->first();
        $accessoriesCategory = Category::where('slug', 'accessories')->first();

        $subCategories = [
            // Uniform subcategories (matching ProductSeeder expectations)
            [
                'name' => 'Male Uniforms',
                'slug' => 'male-uniforms',
                'description' => 'Official uniforms for male students',
                'parent_id' => $uniformsCategory->id,
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Female Uniforms',
                'slug' => 'female-uniforms',
                'description' => 'Official uniforms for female students',
                'parent_id' => $uniformsCategory->id,
                'sort_order' => 2,
                'is_active' => true
            ],

            // Wearables subcategories (matching ProductSeeder expectations)
            [
                'name' => 'T-Shirts',
                'slug' => 't-shirts',
                'description' => 'NU branded t-shirts and casual wear',
                'parent_id' => $wearablesCategory->id,
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Jackets',
                'slug' => 'jackets',
                'description' => 'NU branded jackets, hoodies and outerwear',
                'parent_id' => $wearablesCategory->id,
                'sort_order' => 2,
                'is_active' => true
            ],

            // Additional accessories as standalone (since it's already a main category)
            // No need to create subcategories for accessories if products directly use the main category
        ];

        foreach ($subCategories as $subCategory) {
            Category::create($subCategory);
        }
    }
}
