<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ShoppingCart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cartId = session()->get('cart_id');
        $selectedIds = $request->get('items', []);
        
        $cartItems = ShoppingCart::where('cart_id', $cartId)
            ->whereIn('id', $selectedIds)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No items selected for checkout');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $user = Auth::user();

        return view('customer.checkout.index', compact('cartItems', 'subtotal', 'user'));
    }

    public function process(Request $request)
    {
        // Debug: Log the request data
        \Log::info('Checkout Request Data:', $request->all());

        $rules = [
            'cart_items' => 'required|array',
            'address_option' => 'required|in:saved,new',
        ];

        // Add conditional validation only if using new address
        if ($request->address_option === 'new') {
            $rules = array_merge($rules, [
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'street_address' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'province' => 'required|string|max:100',
                'country' => 'required|string|max:100',
                'zipcode' => 'nullable|string|max:10',
                'delivery_instructions' => 'nullable|string|max:500',
            ]);
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $cartItems = ShoppingCart::whereIn('id', $request->cart_items)
                ->where('cart_id', session()->get('cart_id'))
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('No valid items found for checkout');
            }

            // Prepare shipping information
            $user = Auth::user();
            
            if ($request->address_option === 'saved') {
                $shippingInfo = [
                    'full_name' => $user->first_name . ' ' . $user->last_name,
                    'phone' => $user->mobile_no,
                    'street_address' => $user->street,
                    'city' => $user->city,
                    'province' => $user->province,
                    'country' => $user->country,
                    'zipcode' => $user->zipcode,
                    'delivery_instructions' => null,
                ];
            } else {
                // Use new address data from form
                $shippingInfo = [
                    'full_name' => $request->full_name,
                    'phone' => $request->phone,
                    'street_address' => $request->street_address,
                    'city' => $request->city,
                    'province' => $request->province,
                    'country' => $request->country,
                    'zipcode' => $request->zipcode,
                    'delivery_instructions' => $request->delivery_instructions,
                ];
            }

            // Debug: Log shipping info
            \Log::info('Shipping Info:', $shippingInfo);

            // Create order
            $order = Order::create([
                'order_number' => 'BDX-' . strtoupper(uniqid()),
                'user_id' => Auth::id(),
                'cart_id' => session()->get('cart_id'),
                'total_amount' => $cartItems->sum(function($item) {
                    return $item->price * $item->quantity;
                }),
                'status' => 'placed',
                'order_date' => now(),
                'shipping_info' => json_encode($shippingInfo),
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'size' => $item->size
                ]);
                
                // Update product stock
                $item->product->decrement('stock_quantity', $item->quantity);
                
                // Remove from cart
                $item->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect' => route('ordertracking', ['order' => $order->order_number])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }
}