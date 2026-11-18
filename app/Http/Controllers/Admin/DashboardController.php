<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Calculate stats
        $monthlySales = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $activeOrders = Order::whereIn('status', ['placed', 'pending', 'shipped'])->count();
        
        $totalProducts = Product::count();
        
        $lowStockItems = Product::where('stock_quantity', '<=', 10)->count();

        // Recent orders for table
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Low stock products for inventory table
        $lowStockProducts = Product::where('stock_quantity', '<=', 20)
            ->orderBy('stock_quantity', 'asc')
            ->limit(3)
            ->get();

        // Order summary by period
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())->sum('total_amount');

        $yesterdayOrders = Order::whereDate('created_at', today()->subDay())->count();
        $yesterdayRevenue = Order::whereDate('created_at', today()->subDay())->sum('total_amount');

        $weekOrders = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $weekRevenue = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount');

        // Chart data for Sales Analytics (last 7 days)
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('M d');
            $dailyRevenue = Order::whereDate('created_at', $date)->sum('total_amount');
            $chartData[] = $dailyRevenue;
        }

        return view('admin.dashboard', compact(
            'monthlySales',
            'activeOrders', 
            'totalProducts',
            'lowStockItems',
            'recentOrders',
            'lowStockProducts',
            'todayOrders',
            'todayRevenue',
            'yesterdayOrders',
            'yesterdayRevenue',
            'weekOrders',
            'weekRevenue',
            'chartLabels',    // Add this
            'chartData'       // Add this
        ));
    }

    public function profile()
    {
        return view('dashboard');
    }
}