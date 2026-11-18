<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->get('month', now()->month);
        $selectedYear = now()->year;

        // Monthly revenue trend (weekly breakdown)
        $weeksInMonth = [];
        $revenueData = [];
        
        $startOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();
        
        // Get weekly data for the selected month
        for ($week = 1; $week <= 4; $week++) {
            $weekStart = $startOfMonth->copy()->addWeeks($week - 1);
            $weekEnd = $weekStart->copy()->addDays(6);
            
            if ($weekEnd->gt($endOfMonth)) {
                $weekEnd = $endOfMonth;
            }
            
            $weeksInMonth[] = "Week {$week}";
            $weekRevenue = Order::whereBetween('created_at', [$weekStart, $weekEnd])
                ->sum('total_amount');
            $revenueData[] = $weekRevenue;
        }

        // Order status counts
        $orderStatusCounts = [
            'placed' => Order::where('status', 'placed')->count(),
            'pending' => Order::where('status', 'pending')->count(), 
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
        ];

        // Total revenue and average order value
        $totalRevenue = Order::sum('total_amount');
        $totalOrders = Order::count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Top products by units sold
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.name as product_name',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as units_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue')
            )
            ->groupBy('products.id', 'products.name', 'categories.name')
            ->orderBy('units_sold', 'desc')
            ->limit(5)
            ->get();

        // Sales by category
        $salesByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as items_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // Calculate percentages for sales by category
        $totalCategoryRevenue = $salesByCategory->sum('total_revenue');
        $salesByCategory = $salesByCategory->map(function ($item) use ($totalCategoryRevenue) {
            $item->percentage = $totalCategoryRevenue > 0 
                ? round(($item->total_revenue / $totalCategoryRevenue) * 100, 1) 
                : 0;
            return $item;
        });

        return view('admin.analytics.index', compact(
            'weeksInMonth',
            'revenueData',
            'orderStatusCounts',
            'totalRevenue',
            'averageOrderValue',
            'topProducts',
            'salesByCategory',
            'selectedMonth'
        ));
    }
}