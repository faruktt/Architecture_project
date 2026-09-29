<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = ProductCategory::withCount('products');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('order')->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.product_categories.index', compact('categories', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:product_categories,name',
            'description' => 'nullable|string|max:500',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (ProductCategory::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        ProductCategory::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return redirect()->route('admin.product-categories.index')->with('success', 'Product category "' . $validated['name'] . '" created successfully. It will now appear in product submission dropdowns.');
    }

    public function update(Request $request, int $id)
    {
        $category = ProductCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:product_categories,name,' . $id,
            'description' => 'nullable|string|max:500',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.product-categories.index')->with('success', 'Product category updated successfully.');
    }

    public function destroy(int $id)
    {
        $category = ProductCategory::withCount('products')->findOrFail($id);

        if ($category->products_count > 0) {
            return redirect()->route('admin.product-categories.index')->with('warning', 'Product category cannot be deleted because ' . $category->products_count . ' product(s) are cataloged under it.');
        }

        $category->delete();

        return redirect()->route('admin.product-categories.index')->with('success', 'Product category deleted successfully.');
    }
}
