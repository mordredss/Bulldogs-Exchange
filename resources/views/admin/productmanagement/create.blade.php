<x-admin.layout css="" title="Product Management">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            Add New Product - Bulldogs Exchange
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Product Form -->
        <form action="{{ route('admin.productmanagement.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Basic Product Information -->
                <div class="col-12">
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-info-circle me-2"></i>
                            Basic Product Information
                        </h5>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        Product Name <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}"
                                        placeholder="" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sku" class="form-label">
                                        SKU <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku"
                                        name="sku" value="{{ old('sku') }}" placeholder="" required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">
                                        Category <span class="required">*</span>
                                    </label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="short_description" class="form-label">Short Description</label>
                            <input type="text" class="form-control @error('short_description') is-invalid @enderror"
                                id="short_description" name="short_description" value="{{ old('short_description') }}"
                                placeholder="">
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                Full Description <span class="required">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="5"
                                placeholder=""
                                required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing & Inventory -->
                <div class="col-12">
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-peso-sign me-2"></i>
                            Pricing & Inventory
                        </h5>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="price" class="form-label">
                                        Regular Price (₱) <span class="required">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price') }}" step="0.01" min="0"
                                        placeholder="0.00" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount_price" class="form-label">Sale Price (₱)</label>
                                    <input type="number"
                                        class="form-control @error('discount_price') is-invalid @enderror"
                                        id="discount_price" name="discount_price" value="{{ old('discount_price') }}"
                                        step="0.01" min="0" placeholder="0.00 (Optional)">
                                    <div class="form-text">Leave empty if no discount</div>
                                    @error('discount_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Weight (kg)</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror"
                                        id="weight" name="weight" value="{{ old('weight') }}" step="0.01" min="0"
                                        placeholder="0.00">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">
                                        Stock Quantity <span class="required">*</span>
                                    </label>
                                    <input type="number"
                                        class="form-control @error('stock_quantity') is-invalid @enderror"
                                        id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}"
                                        min="0" placeholder="0" required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="low_stock_threshold" class="form-label">Low Stock Alert</label>
                                    <input type="number"
                                        class="form-control @error('low_stock_threshold') is-invalid @enderror"
                                        id="low_stock_threshold" name="low_stock_threshold"
                                        value="{{ old('low_stock_threshold', 10) }}" min="0" placeholder="10">
                                    <div class="form-text">Alert when stock reaches this level</div>
                                    @error('low_stock_threshold')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Variants -->
                <div class="col-12">
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-palette me-2"></i>
                            Product Variants
                        </h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Available Sizes</label>
                                    <div class="d-flex flex-wrap">
                                        <div class="form-check size-option">
                                            <input class="form-check-input" type="checkbox" name="sizes[]" value="XS"
                                                id="sizeXS">
                                            <label class="form-check-label" for="sizeXS">XS</label>
                                        </div>
                                        <div class="form-check size-option">
                                            <input class="form-check-input" type="checkbox" name="sizes[]" value="S"
                                                id="sizeS">
                                            <label class="form-check-label" for="sizeS">S</label>
                                        </div>
                                        <div class="form-check size-option">
                                            <input class="form-check-input" type="checkbox" name="sizes[]" value="M"
                                                id="sizeM">
                                            <label class="form-check-label" for="sizeM">M</label>
                                        </div>
                                        <div class="form-check size-option">
                                            <input class="form-check-input" type="checkbox" name="sizes[]" value="L"
                                                id="sizeL">
                                            <label class="form-check-label" for="sizeL">L</label>
                                        </div>
                                        <div class="form-check size-option">
                                            <input class="form-check-input" type="checkbox" name="sizes[]" value="XL"
                                                id="sizeXL">
                                            <label class="form-check-label" for="sizeXL">XL</label>
                                        </div>
                                        <div class="form-check size-option">
                                            <input class="form-check-input" type="checkbox" name="sizes[]" value="XXL"
                                                id="sizeXXL">
                                            <label class="form-check-label" for="sizeXXL">XXL</label>
                                        </div>
                                    </div>
                                    <div class="form-text">Check all sizes that apply to this product</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Available Colors</label>
                                    <div class="color-input-group">
                                        <input type="text" class="form-control mb-2" name="colors[]"
                                            placeholder="" value="{{ old('colors.0') }}">
                                    </div>
                                    <div class="color-input-group">
                                        <input type="text" class="form-control mb-2" name="colors[]"
                                            placeholder="" value="{{ old('colors.1') }}">
                                    </div>
                                    <div class="color-input-group">
                                        <input type="text" class="form-control mb-2" name="colors[]"
                                            placeholder="" value="{{ old('colors.2') }}">
                                    </div>
                                    <div class="form-text">Leave empty if color doesn't apply</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                <div class="col-12">
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-images me-2"></i>
                            Product Images
                        </h5>

                        <div class="mb-3">
                            <label for="images" class="form-label">Upload Images</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" id="images"
                                name="images[]" multiple accept="image/*">
                            <div class="form-text">
                                Select multiple images (JPG, PNG, GIF). Recommended size: 800x800px. Maximum 5 images.
                            </div>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Options -->
                <div class="col-12">
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-cog me-2"></i>
                            Additional Options
                        </h5>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <strong>Featured Product</strong>
                                <div class="form-text">Display this product prominently on homepage and category pages
                                </div>
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="specifications" class="form-label">Product Specifications</label>
                            <textarea class="form-control @error('specifications') is-invalid @enderror"
                                id="specifications" name="specifications" rows="4"
                                placeholder="">{{ old('specifications') }}</textarea>
                            <div class="form-text">Enter specifications one per line (e.g., Material: Cotton)</div>
                            @error('specifications')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="col-12">
                    <div class="form-section text-center">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.productmanagement.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-custom btn-lg">
                                <i class="fas fa-save me-2"></i>
                                Save Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </x-admin-layout>