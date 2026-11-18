<?php

namespace App\View\Components;  // Changed from App\View\Composers

use Illuminate\View\View;
use App\Models\Category;

class CategoryComposer
{
    public function compose(View $view)
    {
        $categories = Category::where('is_active', true)
                            ->orderBy('name')
                            ->take(5) // Limit to 5 main categories
                            ->get();
        
        $view->with('navCategories', $categories);
    }
}