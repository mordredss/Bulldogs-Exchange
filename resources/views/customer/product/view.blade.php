{{-- filepath: resources/views/customer/product/view.blade.php --}}
<x-customer.layout css="product.css" title="{{ $product->name ?? 'Product' }}">
    <div class="container">
        <div class="product-card">
            <!-- Product Image Section -->
            <div class="product-image-section">
                <div class="image-container">
                    <img src="{{ $product->main_image ? asset('images/' . $product->main_image) : asset('images/landing-page/placeholder.jpg') }}"
                        alt="{{ $product->name }}" class="product-image">
                    <div class="nav-arrow nav-left">
                        <div class="arrow-icon arrow-left"></div>
                    </div>
                    <div class="nav-arrow nav-right">
                        <div class="arrow-icon arrow-right"></div>
                    </div>
                    <div class="pagination" id="imagePagination">1/1</div>
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="product-details">
                <div class="category-badge">{{ $product->category->name ?? 'Uncategorized' }}</div>
                <h1 class="product-name">{{ $product->name }}</h1>

                <div class="price-container">
                    <span class="price">₱{{ number_format($product->current_price, 2) }}</span>
                </div>

                <!-- Size Selection (if applicable) -->
                @if($product->has_sizes ?? false)
                <div class="sizes-section">
                    <div class="sizes-header">
                        <span class="sizes-title">Sizes</span>
                        <div class="size-guide">
                            <div class="ruler-icon"></div>
                            <span class="size-guide-text">Size Guide</span>
                        </div>
                    </div>
                    <div class="size-options" id="sizeOptions">
                        <div class="size-option" data-size="XS">XS</div>
                        <div class="size-option" data-size="S">S</div>
                        <div class="size-option" data-size="M">M</div>
                        <div class="size-option" data-size="L">L</div>
                        <div class="size-option" data-size="XL">XL</div>
                        <div class="size-option size-small" data-size="XXL">XXL</div>
                        <div class="size-option size-small" data-size="3XL">3XL</div>
                    </div>
                </div>
                @endif

                <div class="quantity-section">
                    <span class="quantity-title">Quantity</span>
                    <div class="quantity-controls">
                        <button class="quantity-btn minus" onclick="decreaseQuantity()">-</button>
                        <span class="quantity-value" id="quantityValue">1</span>
                        <button class="quantity-btn plus" onclick="increaseQuantity()">+</button>
                    </div>
                    <div class="stock-info">
                        <span class="stock-text">
                            @if($product->stock_quantity > 0)
                            {{ $product->stock_quantity }} available
                            @else
                            Out of stock
                            @endif
                        </span>
                    </div>
                </div>

                <div class="action-buttons">
                    @if($product->stock_quantity > 0)
                        @auth
                            <button class="btn btn-primary add-to-cart" 
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}"
                                    data-product-price="{{ $product->current_price }}"
                                    data-product-image="{{ $product->main_image }}">
                                <div class="cart-icon"></div>
                                <span class="button-text">ADD TO CART</span>
                                <div class="loading-spinner d-none"></div>
                            </button>
                            <button class="btn btn-primary buy-now">BUY NOW</button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <div class="cart-icon"></div>
                                <span class="button-text">LOGIN TO ORDER</span>
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-primary">LOGIN TO BUY</a>
                        @endauth
                    @else
                        <button class="btn btn-primary add-to-cart" disabled>
                            <div class="cart-icon"></div>
                            OUT OF STOCK
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="product-spec-card">
            <div class="spec-section">
                <div class="section-header">
                    <h2 class="section-title">Product Specifications</h2>
                </div>
                <div class="specifications-list">
                    <div class="spec-item">
                        <span class="spec-label">Category</span>
                        <div class="spec-value breadcrumb">
                            <a href="#" class="breadcrumb-link">{{ $product->category->name ?? 'Uncategorized' }}</a>
                        </div>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Stock</span>
                        <span class="spec-value">{{ $product->stock_quantity }}</span>
                    </div>
                    @if($product->material)
                    <div class="spec-item">
                        <span class="spec-label">Material</span>
                        <span class="spec-value">{{ $product->material }}</span>
                    </div>
                    @endif
                    @if($product->sku)
                    <div class="spec-item">
                        <span class="spec-label">SKU</span>
                        <span class="spec-value">{{ $product->sku }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="description-section">
                <div class="section-header">
                    <h2 class="section-title">Product Description</h2>
                </div>
                <div class="description-content">
                    <p class="description-text">
                        {{ $product->description ?? 'No description available for this product.' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="feedback-card">
            <div class="section-header">
                <h2 class="section-title">Feedback</h2>
            </div>
            <div class="feedback-list">
                @forelse($product->reviews as $review)
                    <div class="feedback-item">
                        <div class="feedback-header">
                            <strong class="feedback-username">{{ $review->user->username }}</strong>
                            <span class="feedback-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star{{ $i <= $review->rating ? ' filled' : '' }}">★</span>
                                @endfor
                            </span>
                        </div>
                        <span class="feedback-comment">{{ $review->comment }}</span>
                        @auth
                            @if(auth()->id() === $review->user_id)
                                <form action="{{ route('product.review.destroy', [$product->id, $review->id]) }}" method="POST" class="delete-feedback-form" onsubmit="return confirm('Delete this feedback?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-feedback-btn" title="Delete">
                                        🗑️
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @empty
                    <div class="feedback-empty">No feedback yet.</div>
                @endforelse
            </div>
            <div class="feedback-form-section">
                @auth
                    <a href="{{ route('form.index', ['product' => $product->id]) }}" class="leave-review-btn">Leave a Review</a>
                @else
                    <p><a href="{{ route('login') }}">Log in</a> to leave feedback.</p>
                @endauth
            </div>
        </div>
    </div>
    <!-- JavaScript for Functionality -->
    <script>
        let currentQuantity = 1;
        let maxStock = {{ $product->stock_quantity }};
        let selectedSize = null;

        // Quantity Controls
        function increaseQuantity() {
            if (currentQuantity < maxStock) {
                currentQuantity++;
                document.getElementById('quantityValue').textContent = currentQuantity;
            }
        }

        function decreaseQuantity() {
            if (currentQuantity > 1) {
                currentQuantity--;
                document.getElementById('quantityValue').textContent = currentQuantity;
            }
        }

        // Size Selection
        document.querySelectorAll('.size-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.size-option').forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                selectedSize = this.getAttribute('data-size');
            });
        });

        // Add to Cart Functionality (only for authenticated users)
        @auth
        const addToCartBtn = document.querySelector('.add-to-cart');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function() {
                const button = this;
                const spinner = button.querySelector('.loading-spinner');
                const buttonText = button.querySelector('.button-text');

                // Validate size if required
                if (!selectedSize && document.querySelector('.sizes-section')) {
                    document.querySelector('.sizes-section').classList.add('shake');
                    setTimeout(() => {
                        document.querySelector('.sizes-section').classList.remove('shake');
                    }, 820);
                    return;
                }

                // Show loading state
                button.disabled = true;
                spinner.classList.remove('d-none');
                buttonText.textContent = 'Adding...';

                fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: parseInt(button.dataset.productId),
                        quantity: currentQuantity,
                        size: selectedSize,
                        price: parseFloat(button.dataset.productPrice)
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    buttonText.textContent = '✓ Added';
                    setTimeout(() => {
                        window.location.href = '{{ route("cart.index") }}';
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.disabled = false;
                    spinner.classList.add('d-none');
                    buttonText.textContent = 'ADD TO CART';
                    alert(error.message || 'Failed to add item to cart');
                });
            });
        }
        @endauth
    </script>

    <!-- Add CSS for active states -->
    <style>
        .size-option.active {
            background: #35408E;
            color: white;
            border-color: #35408E;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .stock-info {
            margin-top: 8px;
            font-size: 14px;
            color: #6c757d;
        }

        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }

        .d-none {
            display: none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .add-to-cart.loading .button-text {
            visibility: hidden;
        }

        .add-to-cart.loading .loading-spinner {
            display: inline-block;
        }

        .add-to-cart {
            position: relative;
            min-width: 150px;
            transition: all 0.3s ease;
        }

        .button-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .shake {
            animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both;
            transform: translate3d(0, 0, 0);
            backface-visibility: hidden;
            perspective: 1000px;
            border: 2px solid #ff4444;
        }

        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
    </style>
</x-customer.layout>