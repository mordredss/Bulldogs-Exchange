<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        // Calculate statistics using YOUR actual status values
        $totalOrders = Order::count();
        $activeOrders = Order::whereIn('status', ['placed', 'pending', 'shipped'])->count(); // Fixed status names
        $shippedOrders = Order::where('status', 'shipped')->count();
        $completedOrders = Order::where('status', 'completed')->count(); // Fixed status name

        return view('admin.ordermanagement.index', compact(
            'orders',
            'totalOrders',
            'activeOrders', 
            'shippedOrders',
            'completedOrders'
        ));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])
            ->findOrFail($id);
        
        // Decode shipping info
        $shippingInfo = json_decode($order->shipping_info ?? '{}', true);
        
        return view('admin.ordermanagement.view', compact('order', 'shippingInfo'));
    }

    public function updateStatusView(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:placed,pending,shipped,completed'
        ]);

        try {
            DB::beginTransaction();
            
            $order = Order::findOrFail($id);
            $oldStatus = $order->status;
            $newStatus = $request->status;
            
            // Update the status
            $order->status = $newStatus;
            
            // Update timestamps based on status
            switch ($newStatus) {
                case 'shipped':
                    if ($oldStatus !== 'shipped') {
                        $order->shipped_at = now();
                    }
                    break;
                case 'completed':
                    if ($oldStatus !== 'completed') {
                        $order->delivered_at = now();
                    }
                    break;
            }
            
            // Log the status change
            Log::info("Order {$order->order_number} status changed from {$oldStatus} to {$newStatus}");
            
            $order->save();
            
            DB::commit();
            
            // Redirect back with success message
            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Order status updated successfully to ' . ucfirst($newStatus));
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update order status: " . $e->getMessage());
            
            // Redirect back with error message
            return redirect()->route('admin.orders.show', $id)
                ->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:placed,pending,shipped,completed'
        ]);

        try {
            DB::beginTransaction();
            
            $order = Order::findOrFail($id);
            $oldStatus = $order->status;
            $newStatus = $request->status;
            
            // Update the status
            $order->status = $newStatus;
            
            // Update timestamps based on status
            switch ($newStatus) {
                case 'shipped':
                    if ($oldStatus !== 'shipped') {
                        $order->shipped_at = now();
                    }
                    break;
                case 'completed':
                    if ($oldStatus !== 'completed') {
                        $order->delivered_at = now();
                    }
                    break;
            }
            
            // Log the status change
            Log::info("Order {$order->order_number} status changed from {$oldStatus} to {$newStatus}");
            
            $order->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'newStatus' => $newStatus
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update order status: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status: ' . $e->getMessage()
            ], 500);
        }
    }
}
