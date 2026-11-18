<x-admin.layout css="admindashboard.css" title="Dashboard">
    <div class="dashboard-container">
        <!-- Header Section -->
        <div class="header-section">
            <h1 class="header-title">Dashboard Overview</h1>
            <p class="header-subtitle">Welcome back! Here's a quick overview of your store today.</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Monthly Sales</div>
                <div class="stat-value">₱{{ number_format($monthlySales, 2) }}</div>
                <div class="stat-icon">
                    <img src="{{ asset('images/admin/healthicons_money-bag-outline.svg') }}" alt="">
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Active Orders</div>
                <div class="stat-value">{{ number_format($activeOrders) }}</div>
                <div class="stat-icon">
                    <img src="{{ asset('images/admin/Folder(b).svg') }}" alt="">
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Products</div>
                <div class="stat-value">{{ number_format($totalProducts) }}</div>
                <div class="stat-icon">
                    <img src="{{ asset('images/admin/briefcase(b).svg') }}" alt="">
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Low Stock Items</div>
                <div class="stat-value">{{ number_format($lowStockItems) }}</div>
                <div class="stat-icon">
                    <img src="{{ asset('images/admin/material-symbols_warning-outline-rounded.svg') }}" alt="">
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Sales Analytics -->
            <div class="content-card analytics-card">
                <h2 class="card-title">Sales Analytics</h2>
                <div class="chart-container">
                    <canvas id="dashboardSalesChart"></canvas>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="content-card orders-card">
                <h2 class="card-title orders-title">Recent Orders</h2>
                <div class="table-container">
                    <div class="table-header orders-header">
                        <div class="table-header-cell">ORDER ID</div>
                        <div class="table-header-cell">STATUS</div>
                        <div class="table-header-cell">AMOUNT</div>
                    </div>
                    @forelse($recentOrders as $order)
                        <div class="table-row orders-row">
                            <div class="table-cell left">{{ $order->order_number }}</div>
                            <div class="table-cell left">{{ ucfirst($order->status) }}</div>
                            <div class="table-cell amount left">₱{{ number_format($order->total_amount, 2) }}</div>
                        </div>
                    @empty
                        <div class="table-row orders-row">
                            <div class="table-cell left" colspan="3">No recent orders</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- Bottom Grid -->
        <div class="bottom-grid">
            <!-- Low Stock Products -->
            <div class="bottom-card">
                <h2 class="card-title orders-title">Low Stock Alert</h2>
                <div class="table-container">
                    <div class="table-header inventory-header">
                        <div class="table-header-cell">PRODUCT</div>
                        <div class="table-header-cell center">STOCK</div>
                        <div class="table-header-cell center">STATUS</div>
                    </div>
                    @forelse($lowStockProducts as $product)
                        <div class="table-row inventory-row">
                            <div class="table-cell left">{{ $product->name }}</div>
                            <div class="table-cell">{{ $product->stock_quantity }}</div>
                            <div class="table-cell">
                                @if($product->stock_quantity <= 5)
                                    Critical
                                @elseif($product->stock_quantity <= 10)
                                    Very Low
                                @else
                                    Low Stock
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="table-row inventory-row">
                            <div class="table-cell left" colspan="3">All products well stocked</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bottom-card">
                <h2 class="card-title orders-title">Order Summary</h2>
                <div class="table-container">
                    <div class="table-header summary-header">
                        <div class="table-header-cell">PERIOD</div>
                        <div class="table-header-cell center">ORDERS</div>
                        <div class="table-header-cell center">REVENUE</div>
                    </div>
                    <div class="table-row summary-row">
                        <div class="table-cell left">Today</div>
                        <div class="table-cell">{{ $todayOrders }}</div>
                        <div class="table-cell">₱{{ number_format($todayRevenue, 2) }}</div>
                    </div>
                    <div class="table-row summary-row">
                        <div class="table-cell left">Yesterday</div>
                        <div class="table-cell">{{ $yesterdayOrders }}</div>
                        <div class="table-cell">₱{{ number_format($yesterdayRevenue, 2) }}</div>
                    </div>
                    <div class="table-row summary-row">
                        <div class="table-cell left">This Week</div>
                        <div class="table-cell">{{ $weekOrders }}</div>
                        <div class="table-cell">₱{{ number_format($weekRevenue, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dashboard Sales Chart (7-day trend)
        const dashboardCtx = document.getElementById('dashboardSalesChart').getContext('2d');
        const dashboardSalesChart = new Chart(dashboardCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Daily Sales (Last 7 Days)',
                    data: {!! json_encode($chartData) !!},
                    borderColor: 'rgba(53, 64, 142, 1)',
                    backgroundColor: 'rgba(53, 64, 142, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgba(53, 64, 142, 1)',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            color: '#374151'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: ₱' + context.parsed.y.toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#6b7280'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#6b7280',
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    </script>
</x-admin.layout>