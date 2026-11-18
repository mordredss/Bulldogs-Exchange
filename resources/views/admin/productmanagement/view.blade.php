<x-admin.layout css="" title="View Product">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                    <div>
                        <h2 class="text-primary mb-1">{{ $product->name }}</h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-tag me-2"></i>{{ $product->category->name ?? 'Uncategorized' }}
                            <span class="mx-2">•</span>
                            <i class="fas fa-barcode me-2"></i>{{ $product->sku }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.productmanagement.edit', $product) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Product
                        </a>
                        <a href="{{ route('admin.category.view', $product->category_id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to {{ $product->category->name ?? 'Category' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Product Images Section -->
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-images me-2"></i>Product Images</h5>
                    </div>
                    <div class="card-body text-center">
                        <!-- Main Image -->
                        <div class="mb-3">
                            <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/admin/material-symbols-light_image-outline.svg') }}"
                                alt="{{ $product->name }}" class="img-fluid rounded border"
                                style="max-height: 300px; object-fit: cover;">
                        </div>

                        <!-- Gallery Thumbnails -->
                        @if($product->images && count($product->images) > 1)
                        <div>
                            <h6 class="text-muted mb-3">Additional Images</h6>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                @foreach(array_slice($product->images, 1) as $img)
                                <img src="{{ asset('storage/' . $img) }}" alt="Product Image" class="rounded border"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="col-lg-7 col-md-6">
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small fw-bold">STATUS</label>
                                        <div>
                                            @if($product->status == 'active')
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-check-circle me-1"></i>Active
                                            </span>
                                            @elseif($product->status == 'inactive')
                                            <span class="badge bg-secondary fs-6">
                                                <i class="fas fa-pause-circle me-1"></i>Inactive
                                            </span>
                                            @else
                                            <span class="badge bg-danger fs-6">
                                                <i class="fas fa-times-circle me-1"></i>Out of Stock
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small fw-bold">FEATURED</label>
                                        <div>
                                            @if($product->is_featured)
                                            <span class="badge bg-warning text-dark fs-6">
                                                <i class="fas fa-star me-1"></i>Featured
                                            </span>
                                            @else
                                            <span class="text-muted">Not Featured</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">SHORT DESCRIPTION</label>
                                    <p class="mb-0">
                                        {{ $product->short_description ?? 'No short description available.' }}
                                    </p>
                                </div>

                                <div>
                                    <label class="text-muted small fw-bold">FULL DESCRIPTION</label>
                                    <p class="mb-0">{{ $product->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Inventory -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-peso-sign me-2"></i>Pricing & Stock</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">REGULAR PRICE</label>
                                    <div class="h4 text-success mb-0">₱{{ number_format($product->price, 2) }}</div>
                                </div>

                                @if($product->discount_price)
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">SALE PRICE</label>
                                    <div class="h4 text-danger mb-0">₱{{ number_format($product->discount_price, 2) }}
                                    </div>
                                    <small class="text-muted">
                                        Save ₱{{ number_format($product->price - $product->discount_price, 2) }}
                                    </small>
                                </div>
                                @endif

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">STOCK QUANTITY</label>
                                    <div class="d-flex align-items-center">
                                        <span class="h5 mb-0 me-2">{{ $product->stock_quantity }}</span>
                                        @if($product->stock_quantity <= ($product->low_stock_threshold ?? 10))
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Low Stock
                                            </span>
                                            @endif
                                    </div>
                                </div>

                                <div>
                                    <label class="text-muted small fw-bold">LOW STOCK ALERT</label>
                                    <div>{{ $product->low_stock_threshold ?? 10 }} units</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Variants -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-palette me-2"></i>Variants</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold">AVAILABLE SIZES</label>
                                    <div>
                                        @if($product->sizes && count($product->sizes) > 0)
                                        @foreach($product->sizes as $size)
                                        <span class="badge bg-light text-dark border me-1 mb-1">{{ $size }}</span>
                                        @endforeach
                                        @else
                                        <span class="text-muted">No sizes specified</span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <label class="text-muted small fw-bold">AVAILABLE COLORS</label>
                                    <div>
                                        @if($product->colors && count($product->colors) > 0)
                                        @foreach($product->colors as $color)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $color }}</span>
                                        @endforeach
                                        @else
                                        <span class="text-muted">No colors specified</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Specifications -->
                    @if($product->specifications && count($product->specifications) > 0)
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-list-ul me-2"></i>Specifications</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        @foreach($product->specifications as $key => $value)
                                        <tr>
                                            <td class="text-muted fw-bold" style="width: 30%;">{{ $key }}:</td>
                                            <td>{{ $value }}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('admin.productmanagement.edit', $product) }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-edit me-2"></i>Edit Product
                    </a>
                    <a href="{{ route('admin.productmanagement.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-list me-2"></i>All Products
                    </a>
                    <form method="POST" action="{{ route('admin.productmanagement.destroy', $product) }}" class="d-inline"
                        onsubmit="return confirm('Are you sure you want to delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-lg">
                            <i class="fas fa-trash me-2"></i>Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Feedback Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-comments me-2"></i>Customer Feedback
                        </h5>
                        <span class="badge bg-primary">{{ $product->reviews->count() }} Reviews</span>
                    </div>
                    <div class="card-body">
                        @forelse($product->reviews as $review)
                        <div class="feedback-item border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <strong class="text-primary me-3">{{ $review->user->username }}</strong>
                                        <div class="rating me-3">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? ' text-warning' : ' text-muted' }}"></i>
                                                @endfor
                                        </div>
                                        <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <p class="mb-2 text-dark">{{ $review->comment }}</p>
                                    @if($review->seller_service || $review->driver_service)
                                    <div class="service-ratings">
                                        @if($review->seller_service)
                                        <small class="text-muted me-3">
                                            <strong>Seller:</strong>
                                            @for ($i = 1; $i <= $review->seller_service; $i++)
                                                <i class="fas fa-star text-warning"></i>
                                                @endfor
                                        </small>
                                        @endif
                                        @if($review->driver_service)
                                        <small class="text-muted">
                                            <strong>Driver:</strong>
                                            @for ($i = 1; $i <= $review->driver_service; $i++)
                                                <i class="fas fa-star text-warning"></i>
                                                @endfor
                                        </small>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    <form action="{{ route('admin.productmanagement.destroyReview', [$product->id, $review->id]) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete Feedback">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No customer feedback yet.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-admin.layout>