<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Customer\FormController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\ShoppingCartController;
use App\Http\Controllers\Customer\ProductCategoryController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\OrderController;
use App\Models\ShoppingCart;

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/logout', [DashboardController::class, 'profile'])->name('logout');
    Route::resource('dashboard', DashboardController::class);
    Route::resource('productmanagement', ProductManagementController::class)->names('admin.productmanagement');
    Route::resource('ordermanagement', OrderManagementController::class)->names('admin.ordermanagement');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('analytics', AnalyticsController::class)->names('admin.analytics');
    Route::resource('category', CategoryController::class)->names('admin.category');
    Route::get('/admin/category/{category}', [CategoryController::class, 'show'])->name('admin.category.view');
    Route::delete('/product/{product}/review/{review}', [ProductManagementController::class, 'destroyReview'])->name('admin.productmanagement.destroyReview');
    Route::put('/orders/{id}/status', [OrderManagementController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('/orders', [OrderManagementController::class, 'index'])->name('admin.orders.index');
    
});

Route::get('/logout',[DashboardController::class, 'profile']);

Route::prefix('customer')->group(function () {
    // Browsing routes (no auth required)
    Route::resource('product', ProductController::class);
    Route::get('/all-products', [ProductController::class, 'index'])->name('customer.products.index');
    Route::get('/products', [ProductController::class, 'landing'])->name('customer.landing');
    Route::get('/category/{slug}', [ProductCategoryController::class, 'products'])->name('category.products');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::resource('form', FormController::class);
    
    // Auth required routes
    Route::middleware('auth')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::post('/cart/checkout', [ShoppingCartController::class, 'checkout'])->name('customer.cart.checkout');
        Route::get('/ordertracking', [ProductController::class, 'ordertracking'])->name('customer.ordertracking');
        Route::get('/ordertracking/{order}', [ShoppingCartController::class, 'orderTracking'])->name('ordertracking');
        Route::get('/orders/{order}', [OrderManagementController::class, 'show'])->name('admin.orders.show'); 
        Route::put('/orders/{id}/status', [OrderManagementController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        Route::put('/orders/{id}/status-view', [OrderManagementController::class, 'updateStatusView'])->name('admin.orders.updateStatusView'); 
    });
});

Route::prefix('cart')->middleware('auth')->group(function () {
    Route::get('/', [ShoppingCartController::class, 'index'])->name('cart.index');
    Route::post('/add', [ShoppingCartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/update/{id}', [ShoppingCartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/remove/{id}', [ShoppingCartController::class, 'removeItem'])->name('cart.remove');
});
Route::get('/', [ProductController::class, 'landing'])->name('landing');

Route::get('/dashboard', [ProductController::class, 'landing'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/Login', function () {
    return view('Authentication.Login');
})->name('Login');

Route::get('/Register', function () {
    return view('Authentication.Register');
})->name('Register');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/product/{product}/review', [ProductReviewController::class, 'store'])->name('product.review.store');
    Route::get('/product/{product}/review', [ProductController::class, 'reviewForm'])->middleware('auth')->name('form.index');
    Route::delete('/product/{product}/review/{review}', [ProductReviewController::class, 'destroy'])->middleware('auth')->name('product.review.destroy');
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/orders', [OrderController::class, 'userOrders'])->name('orders.index');
        Route::get('/ordertracking/{orderNumber}', [OrderController::class, 'tracking'])->name('orders.show');
        Route::resource('form', FormController::class);
    });
    Route::get('/ordertracking/{order}', [ShoppingCartController::class, 'orderTracking'])->name('ordertracking');
    Route::get('/api/cart/count', function() {
        $count = ShoppingCart::where('user_id', auth()->id())->sum('quantity');
        return response()->json(['count' => $count]);
    });
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::put('/customer/orders/{order}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('customer.orders.confirm-delivery');
});

Route::get('/logout',[DashboardController::class, 'profile']);
Route::put('/admin/orders/{id}/status', [OrderManagementController::class, 'updateStatus'])
    ->middleware(['auth', 'admin'])
    ->name('admin.orders.updateStatus');

require __DIR__ . '/auth.php';
