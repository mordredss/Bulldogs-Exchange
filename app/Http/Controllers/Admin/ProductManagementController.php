<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search by name or SKU
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by stock
        if ($request->filled('stock')) {
            if ($request->input('stock') == 'low') {
                $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold');
            } elseif ($request->input('stock') == 'out') {
                $query->where('stock_quantity', 0);
            } elseif ($request->input('stock') == 'in_stock') {
                $query->where('stock_quantity', '>', 0);
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $products = $query->get();
        $categories = \App\Models\Category::withCount('products')->get();

        // Calculate stats for dashboard cards
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'low_stock' => Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                                ->where('stock_quantity', '>', 0)->count(),
            'out_of_stock' => Product::where('stock_quantity', 0)->count(),
        ];

        return view('admin.productmanagement.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.productmanagement.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'short_description' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'weight' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'sizes' => 'nullable|array',
            'sizes.*' => 'string',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'is_featured' => 'nullable|boolean',
            'specifications' => 'nullable|string',
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
        }

        // Prepare data for saving
        // Prepare data for saving
        $product = new Product();
        $product->slug = Str::slug($product->name . '-' . uniqid());
        $product->name = $validated['name'];
        $product->sku = $validated['sku'];
        $product->category_id = $validated['category_id'];
        $product->status = $validated['status'];
        $product->short_description = $validated['short_description'] ?? null;
        $product->description = $validated['description'];
        $product->price = $validated['price'];
        $product->discount_price = $validated['discount_price'] ?? null;
        $product->weight = $validated['weight'] ?? null;
        $product->stock_quantity = $validated['stock_quantity'];
        $product->low_stock_threshold = $validated['low_stock_threshold'] ?? 10;
        $product->sizes = $validated['sizes'] ?? [];
        $product->colors = array_filter($validated['colors'] ?? []);
        $product->images = $imagePaths;
        $product->is_featured = $request->has('is_featured');
        $product->specifications = $validated['specifications']
            ? preg_split('/\r\n|\r|\n/', $validated['specifications'])
            : [];

        $product->save();

        return redirect()->route('admin.productmanagement.index')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with(['category', 'reviews.user'])->findOrFail($id);
    return view('admin.productmanagement.view', compact('product'));
    }

    public function destroyReview(Product $product, ProductReview $review)
    {
        // Ensure the review belongs to this product
        if ($review->product_id !== $product->id) {
            abort(404);
        }

        $review->delete();
        
        return redirect()->back()->with('success', 'Customer feedback deleted successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::active()->get();
        return view('admin.productmanagement.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'short_description' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'weight' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'sizes' => 'nullable|array',
            'sizes.*' => 'string',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'is_featured' => 'nullable|boolean',
            'specifications' => 'nullable|string',
        ]);

        // Handle new image uploads and merge with existing images
        $imagePaths = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
            // Optional: Limit to 5 images
            $imagePaths = array_slice($imagePaths, 0, 5);
        }

        $product->name = $validated['name'];
        $product->sku = $validated['sku'];
        $product->category_id = $validated['category_id'];
        $product->status = $validated['status'];
        $product->short_description = $validated['short_description'] ?? null;
        $product->description = $validated['description'];
        $product->price = $validated['price'];
        $product->discount_price = $validated['discount_price'] ?? null;
        $product->weight = $validated['weight'] ?? null;
        $product->stock_quantity = $validated['stock_quantity'];
        $product->low_stock_threshold = $validated['low_stock_threshold'] ?? 10;
        $product->sizes = $validated['sizes'] ?? [];
        $product->colors = array_filter($validated['colors'] ?? []);
        $product->images = $imagePaths;
        $product->is_featured = $request->has('is_featured');
        $product->specifications = $validated['specifications']
            ? preg_split('/\r\n|\r|\n/', $validated['specifications'])
            : [];

        // Optionally update slug if name changes
        $product->slug = Str::slug($product->name . '-' . uniqid());

        $product->save();

        return redirect()->route('admin.productmanagement.show', $product->id)
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Optionally, delete images from storage
        if ($product->images && is_array($product->images)) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $product->delete();

        return redirect()->route('admin.productmanagement.index')->with('success', 'Product deleted successfully!');
    }
}
