<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthorProjectController extends Controller
{
    public function index()
    {
        $author = Auth::guard('author')->user();
        $projects = $author->projects()->latest()->paginate(10);

        return view('author.projects.index', compact('projects'));
    }

    public function create()
    {
        $countries = Country::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($countries)) {
            $countries = ['Malaysia', 'Indonesia', 'Philippine', 'Thailand', 'Vietnam', 'China', 'Japan', 'India', 'Others'];
        }

        $categories = ProjectCategory::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = ['Architecture', 'Interior', 'Residential', 'Commercial', 'Hospitality', 'Landscape', 'Cultural'];
        }

        return view('author.projects.create', compact('countries', 'categories'));
    }

    public function store(Request $request)
    {
        $author = Auth::guard('author')->user();

        // Pre-normalize incoming fields
        $data = $request->all();
        if (empty($data['content']) && !empty($data['description'])) {
            $data['content'] = $data['description'];
        }
        if (empty($data['collaboration']) && !empty($data['architects'])) {
            $data['collaboration'] = $data['architects'];
        }
        $request->merge($data);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'category' => 'required|string',
            'country' => 'required|string',
            'city' => 'nullable|string|max:255',
            'lead_architects' => 'nullable|string|max:255',
            'associate' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:100',
            'build_year' => 'nullable|string|max:20',
            'year' => 'nullable|string|max:20',
            'photographer' => 'nullable|string|max:255',
            'illustrations' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:100',
            'web_address' => 'nullable|string|max:255',
            'collaboration' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:100',
            'is_property_sell' => 'nullable|boolean',
            'excerpt' => 'required|string|max:1000',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'featured_image_url' => 'nullable|url',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_urls' => 'nullable|string',
        ]);

        // Generate unique slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Project::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        // Handle Featured Image
        $featuredImage = $validated['featured_image_url'] ?? null;
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $featuredImage = $filename;
        }

        if (!$featuredImage) {
            $featuredImage = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80';
        }

        // Handle Gallery
        $gallery = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $gallery[] = $filename;
            }
        }

        if (!empty($validated['gallery_urls'])) {
            $extraUrls = array_filter(array_map('trim', explode("\n", $validated['gallery_urls'])));
            $gallery = array_merge($gallery, $extraUrls);
        }

        if (empty($gallery)) {
            $gallery = [$featuredImage];
        }

        $project = Project::create([
            'author_id' => $author->id,
            'title' => $validated['title'],
            'slug' => $slug,
            'subtitle' => $validated['subtitle'] ?? null,
            'category' => $validated['category'],
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
            'lead_architects' => $validated['lead_architects'] ?? null,
            'associate' => $validated['associate'] ?? null,
            'year' => $validated['build_year'] ?? $validated['year'] ?? date('Y'),
            'build_year' => $validated['build_year'] ?? $validated['year'] ?? date('Y'),
            'area' => $validated['area'] ?? null,
            'photographer' => $validated['photographer'] ?? null,
            'illustrations' => $validated['illustrations'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'web_address' => $validated['web_address'] ?? null,
            'collaboration' => $validated['collaboration'] ?? null,
            'price' => $validated['price'] ?? null,
            'is_property_sell' => $request->boolean('is_property_sell'),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'featured_image' => $featuredImage,
            'gallery' => $gallery,
            'status' => 'pending', // REQUIRED: Starts as pending!
        ]);

        return redirect()->route('author.projects.index')->with('success', 'Project "' . $project->title . '" submitted successfully! It is now pending admin review and approval.');
    }

    public function edit(int $id)
    {
        $author = Auth::guard('author')->user();
        $project = $author->projects()->findOrFail($id);

        $countries = Country::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($countries)) {
            $countries = ['Malaysia', 'Indonesia', 'Philippine', 'Thailand', 'Vietnam', 'China', 'Japan', 'India', 'Others'];
        }

        $categories = ProjectCategory::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = ['Architecture', 'Interior', 'Residential', 'Commercial', 'Hospitality', 'Landscape', 'Cultural'];
        }

        return view('author.projects.edit', compact('project', 'countries', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $author = Auth::guard('author')->user();
        $project = $author->projects()->findOrFail($id);

        // Pre-normalize incoming fields
        $data = $request->all();
        if (empty($data['content']) && !empty($data['description'])) {
            $data['content'] = $data['description'];
        }
        if (empty($data['collaboration']) && !empty($data['architects'])) {
            $data['collaboration'] = $data['architects'];
        }
        $request->merge($data);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'category' => 'required|string',
            'country' => 'required|string',
            'city' => 'nullable|string|max:255',
            'lead_architects' => 'nullable|string|max:255',
            'associate' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:100',
            'build_year' => 'nullable|string|max:20',
            'year' => 'nullable|string|max:20',
            'photographer' => 'nullable|string|max:255',
            'illustrations' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:100',
            'web_address' => 'nullable|string|max:255',
            'collaboration' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:100',
            'is_property_sell' => 'nullable|boolean',
            'excerpt' => 'required|string|max:1000',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'featured_image_url' => 'nullable|url',
        ]);

        $featuredImage = $project->getRawOriginal('featured_image');
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $featuredImage = $filename;
        } elseif (!empty($validated['featured_image_url'])) {
            $featuredImage = $validated['featured_image_url'];
        }

        $rawGallery = $project->getRawOriginal('gallery');
        $gallery = is_array($rawGallery) ? $rawGallery : (json_decode($rawGallery, true) ?? []);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $gFile) {
                if ($gFile && $gFile->isValid()) {
                    $gFilename = time() . '_' . Str::random(8) . '.' . $gFile->getClientOriginalExtension();
                    $gFile->move(public_path('uploads'), $gFilename);
                    $gallery[] = $gFilename;
                }
            }
        }

        if ($request->filled('gallery_urls')) {
            $lines = array_filter(array_map('trim', explode("\n", $request->input('gallery_urls'))));
            if (!empty($lines)) {
                $gallery = array_merge($gallery, array_values($lines));
            }
        }

        if (empty($gallery) && $featuredImage) {
            $gallery = [$featuredImage];
        }

        $project->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'category' => $validated['category'],
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
            'lead_architects' => $validated['lead_architects'] ?? null,
            'associate' => $validated['associate'] ?? null,
            'year' => $validated['build_year'] ?? $validated['year'] ?? $project->year,
            'build_year' => $validated['build_year'] ?? $validated['year'] ?? $project->build_year,
            'area' => $validated['area'] ?? null,
            'photographer' => $validated['photographer'] ?? null,
            'illustrations' => $validated['illustrations'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'web_address' => $validated['web_address'] ?? null,
            'collaboration' => $validated['collaboration'] ?? null,
            'price' => $validated['price'] ?? null,
            'is_property_sell' => $request->boolean('is_property_sell'),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'featured_image' => $featuredImage,
            'gallery' => $gallery,
            'status' => 'pending', // Re-verify on edit if required
        ]);

        return redirect()->route('author.projects.index')->with('success', 'Project updated successfully and re-submitted for review.');
    }

    public function destroy(int $id)
    {
        $author = Auth::guard('author')->user();
        $project = $author->projects()->findOrFail($id);
        $project->delete();

        return redirect()->route('author.projects.index')->with('success', 'Project deleted successfully.');
    }
}
