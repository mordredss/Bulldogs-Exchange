{{-- filepath: resources/views/admin/category/view.blade.php --}}
<x-admin.layout css="productmanagement.css" title="View Category">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <div class="container-fluid py-4">

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <nav aria-label="breadcrumb" class="mb-2">
                                    <ol class="breadcrumb mb-0 bg-transparent">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('admin.productmanagement.index') }}" class="text-white-50">
                                                <i class="fas fa-arrow-left me-1"></i>
                                                Product Management
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item active text-white" aria-current="page">
                                            {{ $category->name }}
                                        </li>
                                    </ol>
                                </nav>
                                <h1 class="h2 mb-0">
                                    <i class="fas fa-tags me-2"></i>
                                    {{ $category->name }}
                                </h1>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                    <i class="fas fa-edit me-1"></i>
                                    Edit
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal">
                                    <i class="fas fa-trash me-1"></i>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }} fs-6">
                                        <i class="fas {{ $category->is_active ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="badge bg-info fs-6">
                                        <i class="fas fa-box me-1"></i>
                                        {{ $category->products->count() }} Products
                                    </span>
                                </div>
                                @if($category->description)
                                <p class="text-muted mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    {{ $category->description }}
                                </p>
                                @endif
                            </div>
                            <div class="col-md-4 text-md-end">
                                <a href="{{ route('admin.productmanagement.create') }}?category_id={{ $category->id }}" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>
                                    Add Product to Category
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="bg-primary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-boxes fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $category->products->count() }}</h4>
                                <small class="text-muted">Total Products</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="bg-success text-white rounded-circle p-3 me-3">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $category->products->where('status', 'active')->count() }}</h4>
                                <small class="text-muted">Active Products</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="bg-warning text-white rounded-circle p-3 me-3">
                                <i class="fas fa-exclamation-triangle fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $category->products->where('stock_quantity', '<=', 10)->count() }}</h4>
                                <small class="text-muted">Low Stock</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="bg-info text-white rounded-circle p-3 me-3">
                                <i class="fas fa-peso-sign fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">₱{{ number_format($category->products->sum('price'), 0) }}</h4>
                                <small class="text-muted">Total Value</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>
                            Products in this Category
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($category->products->count())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/admin/material-symbols-light_image-outline.svg') }}"
                                                    alt="{{ $product->name }}"
                                                    class="rounded me-3"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                                    <small class="text-muted">{{ Str::limit($product->short_description, 50) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $product->sku }}</span>
                                        </td>
                                        <td>
                                            @if($product->discount_price)
                                            <div>
                                                <small class="text-decoration-line-through text-muted">₱{{ number_format($product->price, 2) }}</small>
                                                <div class="fw-bold text-danger">₱{{ number_format($product->discount_price, 2) }}</div>
                                            </div>
                                            @else
                                            <span class="fw-bold">₱{{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="{{ $product->stock_quantity <= 10 ? 'text-danger fw-bold' : '' }}">
                                                    {{ $product->stock_quantity }}
                                                </span>
                                                @if($product->stock_quantity <= 10)
                                                    <i class="fas fa-exclamation-triangle text-warning ms-1"></i>
                                                    @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($product->is_featured)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-star me-1"></i>
                                                Featured
                                            </span>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.productmanagement.show', $product->id) }}"
                                                    class="btn btn-sm btn-outline-info" title="View Product">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.productmanagement.edit', $product->id) }}"
                                                    class="btn btn-sm btn-outline-warning" title="Edit Product">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open text-muted mb-3" style="font-size: 4rem;"></i>
                            <h4 class="text-muted">No Products in this Category</h4>
                            <p class="text-muted mb-4">This category doesn't have any products yet. Start by adding your first product.</p>
                            <a href="{{ route('admin.productmanagement.create') }}?category_id={{ $category->id }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Add First Product
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title d-flex align-items-center" id="editCategoryModalLabel">
                        <i class="fas fa-edit me-2"></i>
                        Edit Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm" action="{{ route('admin.category.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <!-- Alert for errors/success -->
                        <div id="editCategoryAlert" class="alert alert-dismissible d-none" role="alert">
                            <div id="editCategoryMessage"></div>
                            <button type="button" class="btn-close" onclick="document.getElementById('editCategoryAlert').classList.add('d-none')"></button>
                        </div>

                        <div class="mb-4">
                            <label for="editCategoryName" class="form-label fw-bold">
                                <i class="fas fa-tag me-1 text-primary"></i>
                                Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control form-control-lg border-2 @error('name') is-invalid @enderror"
                                id="editCategoryName"
                                name="name"
                                value="{{ old('name', $category->name) }}"
                                required
                                autocomplete="off">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Choose a clear and descriptive name for your category
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editCategoryDescription" class="form-label fw-bold">
                                <i class="fas fa-align-left me-1 text-primary"></i>
                                Description <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea class="form-control border-2 @error('description') is-invalid @enderror"
                                id="editCategoryDescription"
                                name="description"
                                rows="3"
                                placeholder="Brief description of this category...">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input"
                                type="checkbox"
                                id="editCategoryActive"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="editCategoryActive">
                                <i class="fas fa-toggle-on me-1 text-success"></i>
                                Active Category
                            </label>
                            <div class="form-text">Active categories will be visible to customers</div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 p-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-warning btn-lg px-4" id="updateCategoryBtn">
                            <i class="fas fa-save me-1"></i>
                            <span id="updateCategoryText">Update Category</span>
                            <span id="updateCategorySpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Category Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title d-flex align-items-center" id="deleteCategoryModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Delete Category
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-center mb-3">Are you sure you want to delete this category?</h5>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone.
                        @if($category->products->count() > 0)
                        This category contains <strong>{{ $category->products->count() }} products</strong>.
                        These products will be uncategorized.
                        @endif
                    </div>
                    <p class="text-center text-muted">
                        Category: <strong>{{ $category->name }}</strong>
                    </p>
                </div>
                <div class="modal-footer bg-light border-0 p-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancel
                    </button>
                    <form id="deleteCategoryForm" action="{{ route('admin.category.destroy', $category->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-lg px-4" onclick="return confirmDelete()">
                            <i class="fas fa-trash me-1"></i>
                            Yes, Delete Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm('Are you absolutely sure you want to delete this category? This action cannot be undone.');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Enhanced form handling for edit category
        document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
            // Show loading state
            const submitBtn = document.getElementById('updateCategoryText');
            const spinner = document.getElementById('updateCategorySpinner');
            const updateBtn = document.getElementById('updateCategoryBtn');

            submitBtn.textContent = 'Updating...';
            spinner.classList.remove('d-none');
            updateBtn.disabled = true;

            // You can add client-side validation here if needed
            const categoryName = document.getElementById('editCategoryName').value.trim();
            if (categoryName.length < 2) {
                e.preventDefault();
                showAlert('Category name must be at least 2 characters long.', 'danger');
                resetButtonState();
                return false;
            }
        });

        // Reset button state function
        function resetButtonState() {
            const submitBtn = document.getElementById('updateCategoryText');
            const spinner = document.getElementById('updateCategorySpinner');
            const updateBtn = document.getElementById('updateCategoryBtn');

            submitBtn.textContent = 'Update Category';
            spinner.classList.add('d-none');
            updateBtn.disabled = false;
        }

        // Show alert function
        function showAlert(message, type) {
            const alertDiv = document.getElementById('editCategoryAlert');
            const messageDiv = document.getElementById('editCategoryMessage');

            alertDiv.className = `alert alert-${type} alert-dismissible`;
            messageDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-1"></i>${message}`;
            alertDiv.classList.remove('d-none');
        }

        // Reset form when modal is hidden
        document.getElementById('editCategoryModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('editCategoryAlert').classList.add('d-none');
            resetButtonState();
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                }
            });
        }, 5000);
    </script>
</x-admin.layout>