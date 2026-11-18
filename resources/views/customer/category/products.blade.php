{{-- filepath: resources/views/customer/category/products.blade.php --}}
<x-customer.layout css="product.css" title="{{ $category->name }} Products">
    <div class="container">
        <!-- Category Header -->
        <div class="category-header">
            <div class="breadcrumb">
                <a href="{{ route('customer.landing') }}" class="breadcrumb-link">Home</a>
                <div class="breadcrumb-arrow"></div>
                <span class="current-category">{{ $category->name }}</span>
            </div>
            <h1 class="category-title">{{ $category->name }}</h1>
            @if($category->description)
                <p class="category-description">{{ $category->description }}</p>
            @endif
        </div>

        <!-- Yellow accent line (matching landing page) -->
        <div class="yellow-line top"></div>

        <!-- Filters Section -->
        <div class="filters-section">
            <form method="GET" class="filters-form">
                <div class="filter-group">
                    <label for="sort" class="filter-label">Sort by:</label>
                    <select name="sort" id="sort" class="filter-select" onchange="this.form.submit()">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    </select>
                </div>

                <div class="filter-group price-filter">
                    <label class="filter-label">Price Range:</label>
                    <input type="number" name="min_price" class="price-input" placeholder="Min ₱" value="{{ request('min_price') }}">
                    <span class="price-separator">-</span>
                    <input type="number" name="max_price" class="price-input" placeholder="Max ₱" value="{{ request('max_price') }}">
                    <button type="submit" class="filter-apply-btn">Apply</button>
                </div>

                <div class="results-count">
                    <span class="count-text">{{ $products->total() }} products found</span>
                </div>
            </form>
        </div>

        <!-- Products Grid (EXACTLY like landing page featured section) -->
        <div class="featured-section">
            <div class="featured-title">{{ $category->name }} Products</div>
            <div class="featured-grid">
                @forelse($products as $product)
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
                    <div class="no-products-message">
                        <h3>No products found in this category</h3>
                        <p>Try adjusting your filters or browse our other categories.</p>
                        <a href="{{ route('customer.landing') }}" class="back-home-btn">Back to Home</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
</x-customer.layout>