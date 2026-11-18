<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function landing()
    {
        $featuredProducts = Product::where('is_featured', true)->take(8)->get();
        return view('customer.landing', compact('featuredProducts'));
    }
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Add search functionality if needed
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Add sorting functionality
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(12);

        return view('customer.product.index', compact('products'));
    }

    public function ordertracking()
    {
        return view('customer.ordertracking');
    }

    public function show(Product $product)
    {
        $product->load(['reviews.user']);
        return view('customer.product.view', compact('product')); // This is correct for single product view
    }

    public function reviewForm(Product $product)
    {
        return view('customer.form.index', compact('product'));
    }
}
