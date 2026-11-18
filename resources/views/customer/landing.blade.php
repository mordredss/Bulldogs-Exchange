<x-customer.layout css="landingpage.css" title="Landing Page">
    <div class="container">
        <!-- Hero Section with Ads -->
        <div class="hero-section">
            <div class="ad-picture"><img class="ad-picture" src="{{ asset('images/landing-page/ad picture 1.jpg') }}" alt="ad picture"></div>
            <div class="middle-ad-picture"><img class="middle-ad-picture" src="{{ asset('images/landing-page/nu-laguna-hero.jpg') }}" alt="ad picture"></div>
            <div class="ad-picture"><img class="ad-picture" src="{{ asset('images/landing-page/ad picture 2.jpg') }}" alt="ad picture"></div>
        </div>

        <!-- Yellow accent line -->
        <div class="yellow-line top"></div>

        <!-- Featured section -->
        <div class="featured-section">
            <div class="featured-title">Featured Products</div>
            <div class="featured-grid">
                @forelse($featuredProducts as $product)
                <a href="{{ route('products.show', $product->id) }}" class="featured-item-link">
                    <div class="featured-item">
                        <img class="product-image"
                            src="{{ $product->main_image ? asset('images/' . $product->main_image) : asset('images/landing-page/placeholder.jpg') }}"
                            alt="{{ $product->name }}">
                        <div class="product-info">
                            <div class="product-price">₱{{ number_format($product->current_price, 2) }}</div>
                            <div class="product-name">{{ $product->name }}</div>
                        </div>
                    </div>
                </a>
                @empty
                <div>No featured products available.</div>
                @endforelse
            </div>
        </div>
</x-customer.layout>