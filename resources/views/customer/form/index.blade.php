<x-customer.layout css="form.css" title="Form">
<main class="main-content">
    <div class="rate-product-header">
        <div class="rate-icon"></div>
        <h1 class="rate-product-title">RATE PRODUCT</h1>
    </div>

    <form class="review-form" method="POST" action="{{ route('product.review.store', $product->id) }}">
        @csrf
        @if(isset($orderId) && $orderId)
        <input type="hidden" name="order_id" value="{{ $orderId }}">
        @endif
        <div class="product-section">
            <div class="cart-item">
                <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/landing-page/placeholder.jpg') }}">
            </div>
            <div class="product-info">
                <span class="uniform-tag">Uniform</span>
                <h2 class="product-title">{{ $product->name }}</h2>
                <div class="product-details">
                    Size: {{ $product->size ?? 'N/A' }}<br>
                    Color: {{ $product->color ?? 'N/A' }}
                </div>
            </div>
            <div class="rating-section">
                <div class="quality-label">Product Quality</div>
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" style="display:none;">
                        <label for="star{{ $i }}" class="star">★</label>
                    @endfor
                </div>
            </div>
        </div>

        <div class="comment-section">
            <h3 class="comment-title">Comment</h3>
            <textarea class="comment-textarea" name="comment" placeholder="Write something about quality, delivery service, etc..." required></textarea>
        </div>

        <div class="submit-section">
            <button type="submit" class="submit-btn">SUBMIT</button>
        </div>
    </form>
</main>
</x-customer.layout>