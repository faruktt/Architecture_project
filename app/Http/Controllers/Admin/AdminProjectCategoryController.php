<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProjectCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = ProjectCategory::withCount('projects');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('order')->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.project_categories.index', compact('categories', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:project_categories,name',
            'description' => 'nullable|string|max:500',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (ProjectCategory::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        ProjectCategory::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return redirect()->route('admin.project-categories.index')->with('success', 'Project category "' . $validated['name'] . '" created successfully.');
    }

    public function update(Request $request, int $id)
    {
        $category = ProjectCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:project_categories,name,' . $id,
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

        return redirect()->route('admin.project-categories.index')->with('success', 'Project category updated successfully.');
    }

    public function destroy(int $id)
    {
        $category = ProjectCategory::withCount('projects')->findOrFail($id);

        if ($category->projects_count > 0) {
            return redirect()->route('admin.project-categories.index')->with('warning', 'Category cannot be deleted because ' . $category->projects_count . ' project(s) are currently categorized under it.');
        }

        $category->delete();

        return redirect()->route('admin.project-categories.index')->with('success', 'Category deleted successfully.');
    }
}
