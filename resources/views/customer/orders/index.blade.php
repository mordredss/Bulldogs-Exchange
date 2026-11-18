<x-customer.layout css="orders.css" title="My Orders">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">My Orders</h1>
            <p class="page-subtitle">Track and manage your orders</p>
        </div>

        <div class="orders-container">
            @forelse($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h3 class="order-number">Order #{{ $order->order_number }}</h3>
                            <span class="order-date">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="order-items">
                        @foreach($order->items->take(3) as $item)
                            <div class="order-item">
                                <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="item-image">
                                <div class="item-details">
                                    <span class="item-name">{{ $item->product->name }}</span>
                                    <span class="item-quantity">Qty: {{ $item->quantity }}</span>
                                </div>
                            </div>
                        @endforeach
                        @if($order->items->count() > 3)
                            <div class="more-items">
                                +{{ $order->items->count() - 3 }} more items
                            </div>
                        @endif
                    </div>

                    <div class="order-footer">
                        <div class="order-total">
                            <span class="total-label">Total:</span>
                            <span class="total-amount">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <a href="{{ route('ordertracking', $order->order_number) }}" 
                           class="track-btn">
                            Track Order
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3>No orders yet</h3>
                    <p>Start shopping to see your orders here!</p>
                    <a href="{{ route('landing') }}" class="shop-now-btn">Shop Now</a>
                </div>
            @endforelse

            {{ $orders->links('custom.pagination') }}
        </div>
    </div>
</x-customer.layout>