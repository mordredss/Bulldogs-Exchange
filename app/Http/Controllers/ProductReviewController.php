<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
            'order_id' => 'nullable|exists:orders,id'
        ]);

        // If order_id is provided, verify the user purchased this product in this order
        if ($request->order_id) {
            $order = Order::where('id', $request->order_id)
                ->where('user_id', auth()->id())
                ->whereHas('items', function($query) use ($product) {
                    $query->where('product_id', $product->id);
                })->first();

            if (!$order) {
                return back()->with('error', 'You can only review products you have purchased.');
            }

            // Check if user already reviewed this product for this order
            $existingReview = ProductReview::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->where('order_id', $request->order_id)
                ->first();

            if ($existingReview) {
                return back()->with('error', 'You have already reviewed this product for this order.');
            }
        } else {
            // For regular reviews (not order-specific), check if user has purchased the product
            $hasPurchased = Order::where('user_id', auth()->id())
                ->whereHas('items', function($query) use ($product) {
                    $query->where('product_id', $product->id);
                })->exists();

            if (!$hasPurchased) {
                return back()->with('error', 'You can only review products you have purchased.');
            }

            // Check if user already reviewed this product (without order context)
            $existingReview = ProductReview::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->whereNull('order_id')
                ->first();

            if ($existingReview) {
                return back()->with('error', 'You have already reviewed this product.');
            }
        }

        ProductReview::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Determine where to redirect
        if ($request->order_id) {
            $order = Order::find($request->order_id);
            return redirect()->route('customer.orders.show', $order->order_number)
                ->with('success', 'Thank you for your review!');
        }

        return redirect()->route('customer.products.show', $product->id)
            ->with('success', 'Thank you for your review!');
    }

    public function destroy(ProductReview $review)
    {
        // Ensure user can only delete their own reviews
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}