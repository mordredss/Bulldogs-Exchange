<x-admin.layout css="" title="View Order">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <div class="container-fluid py-4">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                    <div>
                        <h2 class="text-primary mb-1">Order #{{ $order->order_number }}</h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-user me-2"></i>{{ $order->user->first_name }} {{ $order->user->last_name }}
                            <span class="mx-2">•</span>
                            <i class="fas fa-calendar me-2"></i>{{ $order->created_at->format('M d, Y H:i') }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('admin.orders.updateStatusView', $order->id) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="placed" {{ $order->status === 'placed' ? 'selected' : '' }}>Placed</option>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </form>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Status Timeline -->
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Order Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <!-- Order Placed -->
                            <div class="timeline-item {{ $order->created_at ? 'completed' : '' }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Order Placed</h6>
                                    <small class="text-muted">
                                        {{ $order->created_at ? $order->created_at->format('M d, Y H:i') : 'Pending' }}
                                    </small>
                                </div>
                            </div>

                            <!-- Payment Confirmed -->
                            <div class="timeline-item {{ $order->paid_at ? 'completed' : ($order->status !== 'placed' ? 'active' : '') }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Payment Confirmed</h6>
                                    <small class="text-muted">
                                        {{ $order->paid_at ? $order->paid_at->format('M d, Y H:i') : 'Pending confirmation' }}
                                    </small>
                                </div>
                            </div>

                            <!-- Order Shipped -->
                            <div class="timeline-item {{ $order->shipped_at ? 'completed' : ($order->status === 'shipped' || $order->status === 'completed' ? 'active' : '') }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-shipping-fast"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Order Shipped</h6>
                                    <small class="text-muted">
                                        {{ $order->shipped_at ? $order->shipped_at->format('M d, Y H:i') : 'Not shipped yet' }}
                                    </small>
                                </div>
                            </div>

                            <!-- Order Delivered -->
                            <div class="timeline-item {{ $order->delivered_at ? 'completed' : ($order->status === 'completed' ? 'active' : '') }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Order Delivered</h6>
                                    <small class="text-muted">
                                        {{ $order->delivered_at ? $order->delivered_at->format('M d, Y H:i') : 'Not delivered yet' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details Section -->
            <div class="col-lg-7 col-md-6">
                <div class="row">
                    <!-- Order Information -->
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small fw-bold">STATUS</label>
                                        <div>
                                            @if($order->status == 'placed')
                                                <span class="badge bg-info fs-6">
                                                    <i class="fas fa-clock me-1"></i>Placed
                                                </span>
                                            @elseif($order->status == 'pending')
                                                <span class="badge bg-warning fs-6">
                                                    <i class="fas fa-hourglass-half me-1"></i>Pending
                                                </span>
                                            @elseif($order->status == 'shipped')
                                                <span class="badge bg-primary fs-6">
                                                    <i class="fas fa-shipping-fast me-1"></i>Shipped
                                                </span>
                                            @else
                                                <span class="badge bg-success fs-6">
                                                    <i class="fas fa-check-circle me-1"></i>Completed
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small fw-bold">TOTAL AMOUNT</label>
                                        <div class="h4 text-success mb-0">₱{{ number_format($order->total_amount, 2) }}</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small fw-bold">CUSTOMER EMAIL</label>
                                        <div>{{ $order->user->email }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small fw-bold">PHONE</label>
                                        <div>{{ $order->user->mobile_no ?? 'Not provided' }}</div>
                                    </div>
                                </div>

                                @if($order->tracking_number)
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">TRACKING NUMBER</label>
                                    <div class="text-primary fw-bold">{{ $order->tracking_number }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Shipping Address</h5>
                            </div>
                            <div class="card-body">
                                @if($shippingInfo)
                                    <div class="mb-3">
                                        <label class="text-muted small fw-bold">RECIPIENT</label>
                                        <div>{{ $shippingInfo['full_name'] ?? 'N/A' }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-muted small fw-bold">PHONE</label>
                                        <div>{{ $shippingInfo['phone'] ?? 'N/A' }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-muted small fw-bold">ADDRESS</label>
                                        <div>
                                            {{ $shippingInfo['street_address'] ?? 'N/A' }}<br>
                                            {{ $shippingInfo['city'] ?? 'N/A' }}, {{ $shippingInfo['province'] ?? 'N/A' }}<br>
                                            {{ $shippingInfo['country'] ?? 'N/A' }} {{ $shippingInfo['zipcode'] ?? '' }}
                                        </div>
                                    </div>

                                    @if(isset($shippingInfo['delivery_instructions']) && $shippingInfo['delivery_instructions'])
                                        <div>
                                            <label class="text-muted small fw-bold">DELIVERY INSTRUCTIONS</label>
                                            <div class="text-muted">{{ $shippingInfo['delivery_instructions'] }}</div>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-exclamation-triangle fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">No shipping information available</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">PAYMENT STATUS</label>
                                    <div>
                                        @if($order->paid_at)
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">PAYMENT METHOD</label>
                                    <div>Cash on Delivery</div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">SUBTOTAL</label>
                                    <div>₱{{ number_format($order->total_amount, 2) }}</div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">SHIPPING FEE</label>
                                    <div>₱0.00</div>
                                </div>

                                <hr>
                                <div>
                                    <label class="text-muted small fw-bold">TOTAL</label>
                                    <div class="h5 text-success mb-0">₱{{ number_format($order->total_amount, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Order Items</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3">Product</th>
                                        <th class="px-4 py-3">SKU</th>
                                        <th class="px-4 py-3 text-center">Size</th>
                                        <th class="px-4 py-3 text-center">Quantity</th>
                                        <th class="px-4 py-3 text-end">Unit Price</th>
                                        <th class="px-4 py-3 text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : asset('images/admin/material-symbols-light_image-outline.svg') }}"
                                                         alt="{{ $item->product->name }}" 
                                                         class="rounded border" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                    <small class="text-muted">{{ $item->product->category->name ?? 'Uncategorized' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-light text-dark">{{ $item->product->sku }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            {{ $item->size ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="badge bg-primary">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            ₱{{ number_format($item->price, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-end fw-bold">
                                            ₱{{ number_format($item->price * $item->quantity, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-end fw-bold">Grand Total:</td>
                                        <td class="px-4 py-3 text-end fw-bold text-success h5 mb-0">
                                            ₱{{ number_format($order->total_amount, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-list me-2"></i>All Orders
                    </a>
                    <button type="button" class="btn btn-primary btn-lg" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Order
                    </button>
                    @if($order->status !== 'completed')
                        <form method="POST" action="{{ route('admin.orders.updateStatusView', $order->id) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $order->status === 'shipped' ? 'completed' : 'shipped' }}">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-{{ $order->status === 'shipped' ? 'check' : 'truck' }} me-2"></i>
                                Mark as {{ $order->status === 'shipped' ? 'Completed' : 'Shipped' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles for Timeline -->
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-marker {
            position: absolute;
            left: -22px;
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
        }

        .timeline-item.completed .timeline-marker {
            background: #28a745;
        }

        .timeline-item.active .timeline-marker {
            background: #007bff;
        }

        .timeline-content h6 {
            margin-bottom: 5px;
            font-weight: 600;
        }

        @media print {
            .btn, .card-header { display: none !important; }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-admin.layout>