<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->with(['items.product', 'user'])
            ->firstOrFail();

        $cartItems = $order->items;
        $subtotal = $order->total_amount;

        return view('customer.ordertracking', compact('order', 'cartItems', 'subtotal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function tracking($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id()) // Ensure user can only see their own orders
            ->with(['items.product', 'user'])
            ->firstOrFail();

        // Get cart items from the order items
        $cartItems = $order->items;
        $subtotal = $order->total_amount;

        return view('customer.ordertracking', compact('order', 'cartItems', 'subtotal'));
    }

    public function confirmDelivery(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'shipped')
            ->firstOrFail();
        
        $order->update([
            'status' => 'completed',
            'delivered_at' => now()
        ]);
        
        return redirect()->route('customer.orders.show', $order->order_number)
            ->with('success', 'Order marked as delivered! You can now leave reviews for your products.');
    }

    public function userOrders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }
}
