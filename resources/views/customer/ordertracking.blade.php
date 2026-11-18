<x-customer.layout css="ordertracking.css" title="Order Tracking">

    <div class="desktop-container">
        <!-- Back Button -->
        <div class="back-button" onclick="goBack()">
            <div class="back-icon"></div>
            <span class="back-text">Back</span>
        </div>

        <!-- Page Header -->
        <h1 class="page-header">ORDER TRACKING</h1>

        <!-- Order Tracking Progress -->
        <div class="progress-container">
            @php
                $statuses = [
                    'placed' => 1,
                    'pending' => 2,
                    'shipped' => 3,
                    'completed' => 4
                ];
                $currentStep = $statuses[$order->status] ?? 0;
                // Adjust progress calculation to end at 100% when completed
                $progressWidth = $currentStep >= 4 ? 100 : (($currentStep - 1) / 3) * 100;
                $progressWidth = max(0, $progressWidth); // Ensure it's not negative
            @endphp
            
            <div class="progress-line-background"></div>
            <div class="progress-line-active" style="width: {{ $progressWidth }}%"></div>

            <!-- Step 1: Order Placed -->
            <div class="progress-step {{ $currentStep >= 1 ? 'completed' : 'pending' }}" data-step="1">
                <div class="step-circle">
                    <div class="step-icon order-icon"></div>
                </div>
                <div class="step-info">
                    <div class="step-title">Order Placed</div>
                    <div class="step-date">
                        {{ optional($order->order_date)->format('d/m/Y H:i') ?: 'Pending' }}
                    </div>
                </div>
            </div>

            <!-- Step 2: Order Paid -->
            <div class="progress-step {{ $currentStep >= 2 ? 'completed' : 'pending' }}" data-step="2">
                <div class="step-circle">
                    <div class="step-icon payment-icon"></div>
                </div>
                <div class="step-info">
                    <div class="step-title">Order Paid (₱{{ number_format($order->total_amount, 2) }})</div>
                    <div class="step-date">{{ $order->paid_at ? $order->paid_at->format('d/m/Y H:i') : '' }}</div>
                </div>
            </div>

            <!-- Step 3: To be Shipped -->
            <div class="progress-step {{ $currentStep >= 3 ? 'completed' : ($currentStep == 2 ? 'active' : 'pending') }}" data-step="3">
                <div class="step-circle">
                    <div class="step-icon shipping-icon"></div>
                </div>
                <div class="step-info">
                    <div class="step-title">To be Shipped</div>
                    <div class="step-date">{{ $order->shipped_at ? $order->shipped_at->format('d/m/Y H:i') : '' }}</div>
                </div>
            </div>

            <!-- Step 4: Order Received -->
            <div class="progress-step {{ $currentStep >= 4 ? 'completed' : 'pending' }}" data-step="4">
                <div class="step-circle">
                    <div class="step-icon receive-icon"></div>
                </div>
                <div class="step-info">
                    <div class="step-title">Order Received</div>
                    <div class="step-date">{{ $order->delivered_at ? $order->delivered_at->format('d/m/Y H:i') : '' }}</div>
                </div>
            </div>
        </div>
        <div class="shipping-container">
            <div class="section-header">
                <h2 class="section-title">Shipping Information</h2>
            </div>
            <div class="section-content">
                <div class="two-column">
                    <div class="info-group">
                        <div class="info-item">
                            <span class="info-label">Shipping Address:</span>
                            <span class="info-value">
                                @php
                                    $shipping = json_decode($order->shipping_info ?? '{}', true);
                                @endphp
                                @if($shipping && isset($shipping['street_address']))
                                    {{ $shipping['street_address'] }}, {{ $shipping['city'] }}, {{ $shipping['province'] }}, {{ $shipping['country'] }} {{ $shipping['zipcode'] }}
                                @else
                                    No shipping information available
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Recipient Name:</span>
                            <span class="info-value">
                                {{ $shipping['full_name'] ?? ($order->user->first_name . ' ' . $order->user->last_name) }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Contact Number:</span>
                            <span class="info-value">
                                {{ $shipping['phone'] ?? $order->user->mobile_no ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Shipping Provider:</span>
                            <span class="info-value">J&T Express</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tracking Number:</span>
                            <span class="info-value">{{ $order->tracking_number ?? 'JT' . strtoupper(substr($order->order_number, -8)) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Estimated Delivery:</span>
                            <span class="info-value">
                                @if($order->status === 'shipped' && $order->shipped_at)
                                    {{ $order->shipped_at->addDays(3)->format('M d') }}-{{ $order->shipped_at->addDays(5)->format('d, Y') }}
                                @elseif($order->status === 'completed')
                                    Delivered
                                @else
                                    {{ now()->addDays(3)->format('M d') }}-{{ now()->addDays(5)->format('d, Y') }}
                                @endif
                            </span>
                        </div>
                        @if(isset($shipping['delivery_instructions']) && $shipping['delivery_instructions'])
                        <div class="info-item">
                            <span class="info-label">Delivery Instructions:</span>
                            <span class="info-value">{{ $shipping['delivery_instructions'] }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="info-group">
                        <!-- Update order information section -->
                        <div class="info-item">
                            <span class="info-label">Order Number:</span>
                            <span class="info-value">{{ $order->order_number }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Order Date:</span>
                            <span class="info-value">{{ optional($order->order_date)->format('M d, Y') ?: 'Processing' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="info-value status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Total Amount:</span>
                            <span class="info-value">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        @if($order->paid_at)
                        <div class="info-item">
                            <span class="info-label">Payment Date:</span>
                            <span class="info-value">{{ $order->paid_at->format('M d, Y H:i') }}</span>
                        </div>
                        @endif
                        @if($order->shipped_at)
                        <div class="info-item">
                            <span class="info-label">Shipped Date:</span>
                            <span class="info-value">{{ $order->shipped_at->format('M d, Y H:i') }}</span>
                        </div>
                        @endif
                        @if($order->delivered_at)
                        <div class="info-item">
                            <span class="info-label">Delivered Date:</span>
                            <span class="info-value">{{ $order->delivered_at->format('M d, Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Section -->
        <div class="order-container">
            <div class="section-header">
                <h2 class="section-title">Order Items</h2>
            </div>
            <div class="section-content">
                @foreach($cartItems as $item)
                    <div class="product-item">
                        <div class="product-image">
                            <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                                alt="{{ $item->product->name }}"
                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                        </div>
                        <div class="product-details">
                            <div class="product-name">{{ $item->product->name }}</div>
                            @if($item->size)
                                <div class="product-specs">Size: {{ $item->size }}</div>
                            @endif
                            <div class="product-quantity">x{{ $item->quantity }}</div>
                        </div>
                        <div class="product-price">₱{{ number_format($item->price * $item->quantity, 2) }}</div>
                    </div>
                @endforeach
            </div>

            <div class="order-summary">
                <div class="summary-row">
                    <span class="summary-label">Merchandise Subtotal</span>
                    <span class="summary-value">₱{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Shipping Fee</span>
                    <span class="summary-value">₱0.00</span>
                </div>
                <div class="summary-row total">
                    <span class="summary-label">Order Total</span>
                    <span class="summary-value">₱{{ number_format($subtotal, 2) }}</span>
                </div>
            </div>
        </div>
        <!-- Review Section - Only show when order is completed/delivered -->
        @if($order->status === 'completed')
            <div class="review-section">
                <div class="section-header">
                    <h2 class="section-title">Share Your Experience</h2>
                    <p class="section-subtitle">Help other customers by reviewing the products you purchased</p>
                </div>
                <div class="review-actions">
                    @foreach($order->items as $item)
                        @php
                            // Check if user has already reviewed this product for this order
                            $hasReviewed = \App\Models\ProductReview::where('user_id', auth()->id())
                                ->where('product_id', $item->product_id)
                                ->where('order_id', $order->id)  // Now properly checking order-specific reviews
                                ->exists();
                        @endphp
                        
                        <div class="review-item">
                            <div class="review-product-info">
                                <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                                    alt="{{ $item->product->name }}"
                                    class="review-product-image">
                                <div class="review-product-details">
                                    <h4 class="review-product-name">{{ $item->product->name }}</h4>
                                    @if($item->size)
                                        <span class="review-product-size">Size: {{ $item->size }}</span>
                                    @endif
                                    <span class="review-product-quantity">Quantity: {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <div class="review-button-container">
                                @if($hasReviewed)
                                    <span class="review-completed">
                                        <i class="checkmark-icon">✓</i>
                                        Review Submitted
                                    </span>
                                @else
                                    <a href="{{ route('customer.form.create', ['product_id' => $item->product_id, 'order_id' => $order->id]) }}" 
                                    class="review-button">
                                        <span class="review-button-text">Write Review</span>
                                        <span class="review-button-icon">→</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Delivery Confirmation Section -->
        @if($order->status === 'shipped')
            <div class="delivery-confirmation">
                <div class="confirmation-content">
                    <h3>Have you received your order?</h3>
                    <p>Mark as delivered once you've received all items in good condition</p>
                    <form method="POST" action="{{ route('customer.orders.confirm-delivery', $order->id) }}" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="confirm-delivery-btn">
                            <span>✓</span>
                            Confirm Delivery
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
    <script>
        function goBack() {
            // Check if there's a previous page in history
            if (document.referrer && document.referrer !== window.location.href) {
                window.history.back();
            } else {
                // Fallback to orders list or dashboard
                window.location.href = "{{ route('customer.orders.index') }}";
            }
        }
    </script>

</x-customer.layout>