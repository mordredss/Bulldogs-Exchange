<x-admin.layout css="analytics.css" title="Analytics">
    <div class="Frame1">
        {{-- Header Section --}}
        <div class="Rectangle31">
            <div class="header-texts">
                <div class="ProductManagement">Analytics</div>
                <div class="ManageYourProductCatalogAndInventory">
                    Analytics reveals patterns in collected data and highlights sales trends to guide smarter decisions.
                </div>
            </div>
        </div>

        {{-- Sales Section --}}
        <div class="sales-section">
            <div class="sales-header">
                <span class="Sales">Sales Performance</span>
            </div>
            <div class="sales-content">
                <div class="sales-left">
                    <div class="sales-left-header">
                        <span class="sales-text">Monthly Revenue Trend</span>
                        <select class="month-select" onchange="updateMonth(this.value)">
                            <option value="1" {{ $selectedMonth == 1 ? 'selected' : '' }}>January</option>
                            <option value="2" {{ $selectedMonth == 2 ? 'selected' : '' }}>February</option>
                            <option value="3" {{ $selectedMonth == 3 ? 'selected' : '' }}>March</option>
                            <option value="4" {{ $selectedMonth == 4 ? 'selected' : '' }}>April</option>
                            <option value="5" {{ $selectedMonth == 5 ? 'selected' : '' }}>May</option>
                            <option value="6" {{ $selectedMonth == 6 ? 'selected' : '' }}>June</option>
                            <option value="7" {{ $selectedMonth == 7 ? 'selected' : '' }}>July</option>
                            <option value="8" {{ $selectedMonth == 8 ? 'selected' : '' }}>August</option>
                            <option value="9" {{ $selectedMonth == 9 ? 'selected' : '' }}>September</option>
                            <option value="10" {{ $selectedMonth == 10 ? 'selected' : '' }}>October</option>
                            <option value="11" {{ $selectedMonth == 11 ? 'selected' : '' }}>November</option>
                            <option value="12" {{ $selectedMonth == 12 ? 'selected' : '' }}>December</option>
                        </select>
                    </div>
                    <div class="left-content">
                        <div class="sales-chart-placeholder">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="sales-right">
                    <div class="upper-right">
                        <div class="upper-right-header">
                            <span class="sales-text">Order By Status</span>
                            <div class="upper-right-content">
                                <div class="order-status">
                                    <div class="status-item">
                                        <span class="status-color" style="background-color: #f1c40f;"></span>
                                        <span class="status-label">Placed</span>
                                        <span class="status-count">{{ $orderStatusCounts['placed'] }}</span>
                                    </div>
                                    <div class="status-item">
                                        <span class="status-color" style="background-color: #3498db;"></span>
                                        <span class="status-label">Pending</span>
                                        <span class="status-count">{{ $orderStatusCounts['pending'] }}</span>
                                    </div>
                                    <div class="status-item">
                                        <span class="status-color" style="background-color: #2ecc71;"></span>
                                        <span class="status-label">Shipped</span>
                                        <span class="status-count">{{ $orderStatusCounts['shipped'] }}</span>
                                    </div>
                                    <div class="status-item">
                                        <span class="status-color" style="background-color: #e74c3c;"></span>
                                        <span class="status-label">Completed</span>
                                        <span class="status-count">{{ $orderStatusCounts['completed'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="upper-right-chart-placeholder" style="height: 23vh; width: 23vw;">
                            <canvas id="orderStatusChart"></canvas>
                        </div>

                    </div>
                    <div class="lower-right">
                        <div class="lower-right-left">
                            <div class="Rev-Heading">
                                <h3>Total Revenue: </h3>
                            </div>
                            <div class="Rev-Display">
                                <p>₱{{ number_format($totalRevenue, 2) }}</p>
                            </div>
                        </div>
                        <div class="lower-right-right">
                            <div class="Rev-Heading">
                                <h3>Average Order Value: </h3>
                            </div>
                            <div class="Rev-Display">
                                <p>₱{{ number_format($averageOrderValue, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Analytics --}}
        <div class="analytics-section">

            <div class="analytics-left">
                <div class="analytics-left-header">
                    <span class="analytics-text">Top Products</span>
                </div>
                <hr>
                <div class="TP-container">
                    <div class="table-wrapper">
                        <table class="tp-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Units Sold</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->category_name }}</td>
                                    <td>{{ $product->units_sold }}</td>
                                    <td>₱{{ number_format($product->revenue, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">No sales data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="analytics-right">

                <div class="analytics-right-header">
                    <span class="analytics-text">Sales by Category</span>
                </div>

                <hr>
                <div class="SBC-Container">
                    <div class="SBC-Wrapper">
                        <table class="sbc-table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Items Sold</th>
                                    <th>Total Revenue</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesByCategory as $category)
                                <tr>
                                    <td>{{ $category->category_name }}</td>
                                    <td>{{ $category->items_sold }}</td>
                                    <td>₱{{ number_format($category->total_revenue, 2) }}</td>
                                    <td>{{ $category->percentage }}%</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">No sales data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Chart.js and Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Monthly Revenue Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($weeksInMonth) !!},
                datasets: [{
                    label: '2025 Monthly Sales',
                    data: {!! json_encode($revenueData) !!},
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Order Status Pie Chart
        const ctxPie = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Placed', 'Pending', 'Shipped', 'Completed'],
                datasets: [{
                    label: 'Order Status',
                    data: [
                        {{ $orderStatusCounts['placed'] }},
                        {{ $orderStatusCounts['pending'] }},
                        {{ $orderStatusCounts['shipped'] }},
                        {{ $orderStatusCounts['completed'] }}
                    ],
                    backgroundColor: [
                        '#f1c40f',
                        '#3498db', 
                        '#2ecc71',
                        '#e74c3c'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Month selector function
        function updateMonth(month) {
            window.location.href = `{{ route('admin.analytics.index') }}?month=${month}`;
        }
    </script>


</x-admin.layout>