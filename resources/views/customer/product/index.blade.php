<x-customer.layout css="landingpage.css" title="All Products">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">All Products</h1>
            <p class="page-description">Browse our complete collection of NU Bulldogs merchandise</p>
        </div>

        <!-- Yellow accent line -->
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

                <div class="results-count">
                    <span class="count-text">{{ $products->total() }} products found</span>
                </div>
            </form>
        </div>

        <!-- Products Grid (using same style as landing page) -->
        <div class="featured-section">
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
                    <h3>No products found</h3>
                    <p>We're working on adding more products. Check back soon!</p>
                    <a href="{{ route('customer.landing') }}" class="back-home-btn">Back to Home</a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Enhanced Pagination -->
        <div class="custom-pagination-wrapper">
            @if($products->hasPages())
            <div class="pagination-results">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
            </div>
            <div class="pagination-container">
                <nav>
                    <ul class="pagination" style="display: flex; gap: 8px; list-style: none; padding: 0; margin: 0;">
                        {{-- Previous Page Link --}}
                        @if ($products->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link" style="padding: 8px 16px; border-radius: 8px; background: #F8F9FA; color: #BDBDBD; border: 2px solid #E8E8E9; font-weight: 600; cursor: not-allowed;">« Previous</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->previousPageUrl() }}" style="padding: 8px 16px; border-radius: 8px; background: #fff; color: #35408E; border: 2px solid #E8E8E9; font-weight: 600; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#35408E';this.style.color='#fff'" onmouseout="this.style.background='#fff';this.style.color='#35408E'">« Previous</a>
                        </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $startPage = max(1, $products->currentPage() - 2);
                            $endPage = min($products->lastPage(), $products->currentPage() + 2);
                        @endphp
                        @foreach ($products->getUrlRange($startPage, $endPage) as $page => $url)
                        @if ($page == $products->currentPage())
                        <li class="page-item active">
                            <span class="page-link" style="padding: 8px 14px; border-radius: 8px; background: #35408E; color: #fff; border: 2px solid #35408E; font-weight: 600;">{{ $page }}</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}" style="padding: 8px 14px; border-radius: 8px; background: #fff; color: #35408E; border: 2px solid #E8E8E9; font-weight: 500; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#35408E';this.style.color='#fff'" onmouseout="this.style.background='#fff';this.style.color='#35408E'">{{ $page }}</a>
                        </li>
                        @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($products->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->nextPageUrl() }}" style="padding: 8px 16px; border-radius: 8px; background: #fff; color: #35408E; border: 2px solid #E8E8E9; font-weight: 600; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#35408E';this.style.color='#fff'" onmouseout="this.style.background='#fff';this.style.color='#35408E'">Next »</a>
                        </li>
                        @else
                        <li class="page-item disabled">
                            <span class="page-link" style="padding: 8px 16px; border-radius: 8px; background: #F8F9FA; color: #BDBDBD; border: 2px solid #E8E8E9; font-weight: 600; cursor: not-allowed;">Next »</span>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif
        </div>
        
</x-customer.layout>