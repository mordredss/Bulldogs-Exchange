<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $category = \App\Models\Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . uniqid()), // Generate a unique slug
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
            'description' => $request->description ?? null,
        ]);

        return response()->json(['id' => $category->id, 'name' => $category->name]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = \App\Models\Category::with('products')->findOrFail($id);
        return view('admin.category.view', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = \App\Models\Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . uniqid()),
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false // Better checkbox handling
        ]);

        return redirect()->route('category.show', $category->id)
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = \App\Models\Category::findOrFail($id);

        // Optional: Check if category has products and handle accordingly
        if ($category->products()->count() > 0) {
            // Option 1: Prevent deletion if products exist
            // return redirect()->back()->with('error', 'Cannot delete category with existing products.');

            // Option 2: Uncategorize products (set category_id to null)
            $category->products()->update(['category_id' => null]);
        }

        $category->delete();

        return redirect()->route('productmanagement.index')
            ->with('success', 'Category deleted successfully!');
    }
}
