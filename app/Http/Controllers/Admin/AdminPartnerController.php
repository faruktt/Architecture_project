<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPartnerController extends Controller
{
    /**
     * Display a listing of the partners.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Partner::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
        }

        $partners = $query->orderBy('order', 'asc')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.partners.index', compact('partners', 'search'));
    }

    /**
     * Store a newly created partner in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'logo' => 'required|file|mimes:jpeg,png,jpg,svg,webp,gif|max:4096',
            'url' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $filename = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'partner_' . time() . '_' . Str::random(8) . '.' . $extension;
            $file->move(public_path('uploads'), $filename);
        }

        Partner::create([
            'name' => $validated['name'],
            'logo' => $filename,
            'url' => $validated['url'] ?? null,
            'order' => $validated['order'] ?? (Partner::max('order') + 1),
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner created successfully.');
    }

    /**
     * Update the specified partner in storage.
     */
    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,svg,webp,gif|max:4096',
            'url' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $filename = $partner->logo;
        if ($request->hasFile('logo')) {
            // Delete old logo if exists and not default svg seeded
            if ($partner->logo && !str_starts_with($partner->logo, 'http') && !str_starts_with($partner->logo, 'partner_') && file_exists(public_path('uploads/' . $partner->logo))) {
                @unlink(public_path('uploads/' . $partner->logo));
            }

            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'partner_' . time() . '_' . Str::random(8) . '.' . $extension;
            $file->move(public_path('uploads'), $filename);
        }

        $partner->update([
            'name' => $validated['name'],
            'logo' => $filename,
            'url' => $validated['url'] ?? null,
            'order' => $validated['order'] ?? $partner->order,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully.');
    }

    /**
     * Remove the specified partner from storage.
     */
    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);

        if ($partner->logo && !str_starts_with($partner->logo, 'http') && file_exists(public_path('uploads/' . $partner->logo))) {
            // Only unlink if not a shared default asset
            if (!in_array($partner->logo, ['partner_holcim.svg', 'partner_waf.svg', 'partner_uia.svg', 'partner_unhabitat.svg', 'partner_obel.svg', 'partner_ecc.svg'])) {
                @unlink(public_path('uploads/' . $partner->logo));
            }
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner deleted successfully.');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->is_active = !$partner->is_active;
        $partner->save();

        return redirect()->back()->with('success', 'Partner status updated.');
    }
}
