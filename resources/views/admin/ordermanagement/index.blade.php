<x-admin.layout css="ordermanagement.css" title="Order Management">
    <div class="Frame1">
        <!-- Header Section -->
        <div class="Rectangle31">
            <div class="header-texts">
                <div class="ProductManagement">Order Management</div>
                <div class="ManageYourProductCatalogAndInventory">Monitor orders and modify their status</div>
            </div>
        </div>
        
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number total">{{ number_format($totalOrders) }}</div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-number active">{{ number_format($activeOrders) }}</div>
                <div class="stat-label">Active Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-number low-stock">{{ number_format($shippedOrders) }}</div>
                <div class="stat-label">Shipped Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-number out-of-stock">{{ number_format($completedOrders) }}</div>
                <div class="stat-label">Completed Orders</div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="main-content-section">
            <div class="main-content-header">
                <span class="Products">Orders</span>
                <div class="search-filter-row">
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="filter-form">
                        <div class="search-box">
                            <input type="text" name="search" placeholder="Search by Order Number or Customer..." 
                                class="search-input" value="{{ request('search') }}">
                        </div>
                        <div class="filter-box">
                            <select name="date_filter" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Dates</option>
                                <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ request('date_filter') === 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ request('date_filter') === 'month' ? 'selected' : '' }}>This Month</option>
                            </select>
                        </div>
                        <div class="filter-box">
                            <select name="status" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="placed" {{ request('status') === 'placed' ? 'selected' : '' }}>Placed</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <button type="submit" class="search-btn">
                            <img src="{{ asset('images/admin/search-icon.svg') }}" alt="Search">
                            Search
                        </button>
                        @if(request()->hasAny(['search', 'date_filter', 'status']))
                            <a href="{{ route('admin.orders.index') }}" class="clear-btn">Clear</a>
                        @endif
                    </form>
                </div>
            </div>
        
            <!-- Products Table -->
            <table class="products-table">
                <thead>
                    <tr>
                        <th>PRODUCT</th>
                        <th>DATE</th>
                        <th>ORDER ID</th>
                        <th>STATUS</th>
                        <th>AMOUNT</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        <div class="product-image">
                                            @if($item->product->main_image)
                                                <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                                                     alt="{{ $item->product->name }}">
                                            @else
                                                <img src="{{ asset('images/admin/material-symbols-light_image-outline.svg') }}" 
                                                     alt="No image">
                                            @endif
                                        </div>
                                        <span>{{ $item->product->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $order->created_at->format('m-d-Y') }}</td>
                                <td>{{ $order->order_number }}</td>
                                <td>
                                    <select class="status-select" data-order-id="{{ $order->id }}" onchange="updateOrderStatus(this)">
                                        <option value="placed" {{ $order->status === 'placed' ? 'selected' : '' }}>Placed</option>
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </td>
                                <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <div class="actions-cell">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="action-icon view-icon" title="View Order Details">
                                            <img src="{{ asset('images/admin/mdi_eye.svg') }}" alt="View">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="pagination-row">
                <div class="pagination-info">Showing 1-3 of 200 products</div>
                <div class="pagination-controls">
                    <button class="pagination-btn" aria-label="Previous page">
                        <img src="{{ asset('images/admin/mingcute_left-line.svg') }}" alt="">
                    </button>
                    <button class="pagination-btn" aria-label="Next page">
                        <img src="{{ asset('images/admin/mingcute_right-line.svg') }}" alt="">
                    </button>
                </div>
            </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const updateOrderStatus = (selectElement) => {
                const orderId = selectElement.dataset.orderId;
                const newStatus = selectElement.value;

                fetch(`/admin/orders/${orderId}/status`, {
                    method: 'POST', // Changed to POST to work with Laravel's form handling
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'PUT', // Method spoofing for Laravel
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Status updated successfully');
                        selectElement.className = `status-select ${newStatus}-status`;
                        // Optionally reload the page or update the UI
                        location.reload();
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update order status');
                    // Reset select to previous value
                    selectElement.value = selectElement.getAttribute('data-original-status');
                });
            };

            // Add event listeners to all status selects
            document.querySelectorAll('.status-select').forEach(select => {
                // Store original status
                select.setAttribute('data-original-status', select.value);
                select.addEventListener('change', () => updateOrderStatus(select));
            });
        });
    </script>

    <style>
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 4px;
            color: white;
            z-index: 1000;
            animation: slideIn 0.3s ease-in-out;
        }

        .notification.success {
            background-color: #28a745;
        }

        .notification.error {
            background-color: #dc3545;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .status-select {
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            cursor: pointer;
        }

        .placed-status { color: #007bff; }
        .pending-status { color: #ffc107; }
        .shipped-status { color: #17a2b8; }
        .completed-status { color: #28a745; }
    </style>
</x-admin.layout>