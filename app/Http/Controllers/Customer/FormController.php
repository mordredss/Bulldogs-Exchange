<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index()
    {
        return view('customer.form.index');
    }

    public function create(Request $request)
    {
        $productId = $request->get('product_id');
        $orderId = $request->get('order_id');
        
        if (!$productId) {
            return redirect()->route('customer.products.index')
                ->with('error', 'Product not found.');
        }
        
        $product = \App\Models\Product::findOrFail($productId);
        
        // If order_id is provided, verify the user purchased this product in this order
        if ($orderId) {
            $order = \App\Models\Order::where('id', $orderId)
                ->where('user_id', auth()->id())
                ->whereHas('items', function($query) use ($productId) {
                    $query->where('product_id', $productId);
                })->first();
                
            if (!$order) {
                return redirect()->route('customer.orders.index')
                    ->with('error', 'You can only review products you have purchased.');
            }

            // Check if already reviewed
            $hasReviewed = \App\Models\ProductReview::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->where('order_id', $orderId)
                ->exists();

            if ($hasReviewed) {
                return redirect()->route('customer.orders.show', $order->order_number)
                    ->with('error', 'You have already reviewed this product.');
            }
        }
        
        return view('customer.form.index', compact('product', 'orderId'));
    }
}
