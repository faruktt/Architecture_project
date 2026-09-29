<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCountryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Country::withCount('projects');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }

        $countries = $query->orderBy('order')->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.countries.index', compact('countries', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:countries,name',
            'code' => 'nullable|string|max:10',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Country::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        Country::create([
            'name' => $validated['name'],
            'code' => !empty($validated['code']) ? strtoupper($validated['code']) : strtoupper(substr($validated['name'], 0, 2)),
            'slug' => $slug,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return redirect()->route('admin.countries.index')->with('success', 'Country "' . $validated['name'] . '" added successfully. It is now active on the homepage and project dropdowns.');
    }

    public function update(Request $request, int $id)
    {
        $country = Country::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:countries,name,' . $id,
            'code' => 'nullable|string|max:10',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $country->update([
            'name' => $validated['name'],
            'code' => !empty($validated['code']) ? strtoupper($validated['code']) : strtoupper(substr($validated['name'], 0, 2)),
            'slug' => Str::slug($validated['name']),
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.countries.index')->with('success', 'Country updated successfully.');
    }

    public function destroy(int $id)
    {
        $country = Country::withCount('projects')->findOrFail($id);

        if ($country->projects_count > 0) {
            return redirect()->route('admin.countries.index')->with('warning', 'Country cannot be deleted because ' . $country->projects_count . ' project(s) are associated with it.');
        }

        $country->delete();

        return redirect()->route('admin.countries.index')->with('success', 'Country removed successfully.');
    }
}
