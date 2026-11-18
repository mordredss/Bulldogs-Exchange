<x-admin.layout css="productmanagement.css" title="Product Management">
    <div class="Frame1">
        <!-- Header Section (Remove the Add Product button from here) -->
        <div class="Rectangle31">
            <div class="header-texts">
                <div class="ProductManagement">Product Management</div>
                <div class="ManageYourProductCatalogAndInventory">Manage your product catalog and inventory</div>
            </div>
            <!-- Remove the Add Product button from here -->
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number total">{{ number_format($stats['total']) }}</div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-number active">{{ number_format($stats['active']) }}</div>
                <div class="stat-label">Active Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-number low-stock">{{ number_format($stats['low_stock']) }}</div>
                <div class="stat-label">Low Stock</div>
            </div>
            <div class="stat-card">
                <div class="stat-number out-of-stock">{{ number_format($stats['out_of_stock']) }}</div>
                <div class="stat-label">Out of Stock</div>
            </div>
        </div>

        <div class="main-content-section">
            <!-- CATEGORIES SECTION -->
            <div class="categories-section">
                <div class="categories-header">
                    <span class="Categories">Categories</span>
                    <button type="button" class="add-category-btn" id="addCategoryBtn">
                        <span class="IcBaselinePlus">
                            <img src="{{ asset('images/admin/ic_baseline-plus.svg') }}" alt="">
                        </span>
                        <span class="AddCategory">Add Category</span>
                    </button>
                </div>

                <!-- Categories Grid -->
                <div class="categories-grid">
                    @forelse($categories ?? [] as $category)
                    <a href="{{ route('admin.category.show', optional($category)->id) }}" class="category-card-link">
                        <div class="category-card">
                            <div class="category-info">
                                <div class="category-name">{{ optional($category)->name }}</div>
                                <div class="category-details">
                                    <span class="product-count">{{ optional($category)->products_count ?? 0 }} products</span>
                                    <span class="category-status {{ optional($category)->is_active ? 'active' : 'inactive' }}">
                                        {{ optional($category)->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="no-categories">
                        <img src="{{ asset('images/admin/material-symbols-light_image-outline.svg') }}" alt=""
                            class="no-categories-icon">
                        <p>No categories found. Add your first category to get started.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- PRODUCTS SECTION -->
            <div class="products-section">
                <!-- Updated Products Header with Add Product button -->
                <div class="products-header">
                    <span class="Products">Products</span>
                    <!-- Use the same class as Add Category button -->
                    <a class="add-category-btn" href="{{ route('admin.productmanagement.create') }}">
                        <span class="IcBaselinePlus">
                            <img src="{{ asset('images/admin/ic_baseline-plus.svg') }}" alt="">
                        </span>
                        <span class="AddCategory">Add Product</span>
                    </a>
                </div>

                <!-- Search and Filter Row -->
                <form method="GET" action="{{ route('admin.productmanagement.index') }}" class="search-filter-row">
                    <div class="search-box">
                        <input type="text"
                            name="search"
                            placeholder="Search products..."
                            class="search-input"
                            value="{{ request('search') }}">
                    </div>
                    <div class="filter-box">
                        <select class="filter-select" name="category_id">
                            <option value="">Filter by category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-box">
                        <select class="filter-select" name="stock">
                            <option value="">Filter by stock</option>
                            <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                    <div class="filter-box">
                        <select class="filter-select" name="status">
                            <option value="">Filter by status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="filter-apply-btn">
                            <img src="{{ asset('images/admin/ic_baseline-search.svg') }}" alt="">
                            Apply
                        </button>
                        <a href="{{ route('admin.productmanagement.index') }}" class="filter-reset-btn">
                            <img src="{{ asset('images/admin/ic_baseline-refresh.svg') }}" alt="">
                            Reset
                        </a>
                    </div>
                </form>

                <!-- Results Info -->
                @if(request()->hasAny(['search', 'category_id', 'stock', 'status']))
                <div class="filter-results-info">
                    <div class="results-count">
                        <span class="results-number">{{ $products->count() }}</span>
                        product(s) found
                        @if(request('search'))
                        for "<strong>{{ request('search') }}</strong>"
                        @endif
                    </div>
                    <div class="active-filters">
                        @if(request('category_id'))
                        <span class="filter-tag">
                            Category: {{ $categories->find(request('category_id'))->name ?? 'Unknown' }}
                            <a href="{{ request()->fullUrlWithQuery(['category_id' => null]) }}" class="remove-filter">×</a>
                        </span>
                        @endif
                        @if(request('stock'))
                        <span class="filter-tag">
                            Stock: {{ ucwords(str_replace('_', ' ', request('stock'))) }}
                            <a href="{{ request()->fullUrlWithQuery(['stock' => null]) }}" class="remove-filter">×</a>
                        </span>
                        @endif
                        @if(request('status'))
                        <span class="filter-tag">
                            Status: {{ ucfirst(request('status')) }}
                            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="remove-filter">×</a>
                        </span>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Products Table -->
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>PRODUCT</th>
                            <th>CATEGORY</th>
                            <th>PRICE</th>
                            <th>STOCK</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <div class="product-image">
                                        <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/admin/material-symbols-light_image-outline.svg') }}"
                                            alt="">
                                    </div>
                                    <span>{{ $product->name }}</span>
                                </div>
                            </td>
                            <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                            <td>₱{{ number_format($product->current_price, 2) }}</td>
                            <td>
                                <span class="stock-badge {{ $product->stock_quantity <= ($product->low_stock_threshold ?? 10) ? 'low-stock' : '' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $product->status }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('admin.productmanagement.edit', $product->id) }}"
                                        class="action-icon edit-icon">
                                        <img src="{{ asset('images/admin/mdi_pencil-outline.svg') }}" alt="">
                                    </a>
                                    <a href="{{ route('admin.productmanagement.show', $product->id) }}"
                                        class="action-icon view-icon">
                                        <img src="{{ asset('images/admin/mdi_eye.svg') }}" alt="">
                                    </a>
                                    <form action="{{ route('admin.productmanagement.destroy', $product->id) }}" method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon delete-icon"
                                            style="background:none;border:none;padding:0;">
                                            <img src="{{ asset('images/admin/tabler_trash.svg') }}" alt="">
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="no-results">
                                <div class="no-results-content">
                                    <img src="{{ asset('images/admin/material-symbols-light_search-off.svg') }}" alt="" class="no-results-icon">
                                    <p>No products found matching your criteria.</p>
                                    <a href="{{ route('admin.productmanagement.index') }}" class="btn-reset-filters">Clear all filters</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
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
        </div>

        <!-- Rest of your modals and scripts remain the same -->
        <!-- Custom Add Category Modal -->
        <div class="custom-modal" id="addCategoryModal">
            <!-- Modal content remains the same -->
        </div>

        <script>
            // Your existing scripts remain the same
        </script>
    </div>
</x-admin.layout>