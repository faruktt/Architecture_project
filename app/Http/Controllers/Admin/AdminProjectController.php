<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Author;
use App\Models\ProjectCategory;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProjectController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Project::with('author');

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all' => Project::count(),
            'pending' => Project::where('status', 'pending')->count(),
            'approved' => Project::where('status', 'approved')->count(),
            'rejected' => Project::where('status', 'rejected')->count(),
        ];

        return view('admin.projects.index', compact('projects', 'status', 'search', 'counts'));
    }

    public function create()
    {
        $authors = Author::where('status', 'active')->get();
        $countries = Country::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($countries)) {
            $countries = ['Malaysia', 'Indonesia', 'Philippine', 'Thailand', 'Vietnam', 'China', 'Japan', 'India', 'Others'];
        }

        $categories = ProjectCategory::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = ['Architecture', 'Interior', 'Residential', 'Commercial', 'Hospitality', 'Landscape', 'Cultural'];
        }

        return view('admin.projects.create', compact('authors', 'countries', 'categories'));
    }

    public function store(Request $request)
    {
        // Support content passed as description
        if (!$request->filled('content') && $request->filled('description')) {
            $request->merge(['content' => $request->input('description')]);
        }

        // Support collaboration passed as architects
        if (!$request->filled('collaboration') && $request->filled('architects')) {
            $request->merge(['collaboration' => $request->input('architects')]);
        }

        // Support file uploaded as featured_image_file
        if (!$request->hasFile('featured_image') && $request->hasFile('featured_image_file')) {
            $request->files->set('featured_image', $request->file('featured_image_file'));
        }

        // Support text URL passed in featured_image
        if (!$request->filled('featured_image_url') && $request->filled('featured_image') && is_string($request->input('featured_image'))) {
            $request->merge(['featured_image_url' => $request->input('featured_image')]);
            $request->request->remove('featured_image');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'nullable|exists:authors,id',
            'subtitle' => 'nullable|string|max:255',
            'category' => 'required|string',
            'country' => 'required|string',
            'city' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:10',
            'area' => 'nullable|string|max:100',
            'collaboration' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:100',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'nullable|boolean',
            'is_spotlight' => 'nullable|boolean',
            'is_hero_story' => 'nullable|boolean',
            'is_property_sell' => 'nullable|boolean',
            'excerpt' => 'required|string|max:1000',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'featured_image_url' => 'nullable|url',
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Project::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

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

        $gallery = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $gFile) {
                if ($gFile && $gFile->isValid()) {
                    $gFilename = time() . '_' . Str::random(8) . '.' . $gFile->getClientOriginalExtension();
                    $gFile->move(public_path('uploads'), $gFilename);
                    $gallery[] = $gFilename;
                }
            }
        }

        if ($request->filled('gallery_images_text')) {
            $lines = array_filter(array_map('trim', explode("\n", $request->input('gallery_images_text'))));
            if (!empty($lines)) {
                $gallery = array_merge($gallery, array_values($lines));
            }
        }

        if (empty($gallery) && $featuredImage) {
            $gallery = [$featuredImage];
        }

        $isFeatured = $request->boolean('is_featured');
        $isSpotlight = $request->boolean('is_spotlight');
        $isHeroStory = $request->boolean('is_hero_story');

        // Enforce exclusive single selection
        if ($isFeatured) {
            Project::where('is_featured', true)->update(['is_featured' => false]);
        }
        if ($isSpotlight) {
            Project::where('is_spotlight', true)->update(['is_spotlight' => false]);
        }
        if ($isHeroStory) {
            Project::where('is_hero_story', true)->update(['is_hero_story' => false]);
        }

        Project::create([
            'author_id' => $validated['author_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $slug,
            'subtitle' => $validated['subtitle'] ?? null,
            'category' => $validated['category'],
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
            'year' => $validated['year'] ?? date('Y'),
            'area' => $validated['area'] ?? null,
            'collaboration' => $validated['collaboration'] ?? null,
            'price' => $validated['price'] ?? null,
            'status' => $validated['status'],
            'is_featured' => $isFeatured,
            'is_spotlight' => $isSpotlight,
            'is_hero_story' => $isHeroStory,
            'is_property_sell' => $request->boolean('is_property_sell'),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'featured_image' => $featuredImage,
            'gallery' => $gallery,
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(int $id)
    {
        $project = Project::with('author')->findOrFail($id);
        $authors = Author::where('status', 'active')->get();

        $countries = Country::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($countries)) {
            $countries = ['Malaysia', 'Indonesia', 'Philippine', 'Thailand', 'Vietnam', 'China', 'Japan', 'India', 'Others'];
        }

        $categories = ProjectCategory::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = ['Architecture', 'Interior', 'Residential', 'Commercial', 'Hospitality', 'Landscape', 'Cultural'];
        }

        return view('admin.projects.edit', compact('project', 'authors', 'countries', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $project = Project::findOrFail($id);

        // Support content passed as description
        if (!$request->filled('content') && $request->filled('description')) {
            $request->merge(['content' => $request->input('description')]);
        }

        // Support collaboration passed as architects
        if (!$request->filled('collaboration') && $request->filled('architects')) {
            $request->merge(['collaboration' => $request->input('architects')]);
        }

        // Support file uploaded as featured_image_file
        if (!$request->hasFile('featured_image') && $request->hasFile('featured_image_file')) {
            $request->files->set('featured_image', $request->file('featured_image_file'));
        }

        // Support text URL passed in featured_image
        if (!$request->filled('featured_image_url') && $request->filled('featured_image') && is_string($request->input('featured_image'))) {
            $request->merge(['featured_image_url' => $request->input('featured_image')]);
            $request->request->remove('featured_image');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug,' . $id,
            'author_id' => 'nullable|exists:authors,id',
            'subtitle' => 'nullable|string|max:255',
            'category' => 'required|string',
            'country' => 'required|string',
            'city' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:10',
            'area' => 'nullable|string|max:100',
            'collaboration' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:100',
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
            'is_featured' => 'nullable|boolean',
            'is_spotlight' => 'nullable|boolean',
            'is_hero_story' => 'nullable|boolean',
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

        if ($request->filled('gallery_images_text')) {
            $lines = array_filter(array_map('trim', explode("\n", $request->input('gallery_images_text'))));
            if (!empty($lines)) {
                $gallery = array_merge($gallery, array_values($lines));
            }
        }

        if (empty($gallery) && $featuredImage) {
            $gallery = [$featuredImage];
        }

        $isFeatured = $request->boolean('is_featured');
        $isSpotlight = $request->boolean('is_spotlight');
        $isHeroStory = $request->boolean('is_hero_story');

        // Enforce exclusive single selection
        if ($isFeatured) {
            Project::where('id', '!=', $project->id)->where('is_featured', true)->update(['is_featured' => false]);
        }
        if ($isSpotlight) {
            Project::where('id', '!=', $project->id)->where('is_spotlight', true)->update(['is_spotlight' => false]);
        }
        if ($isHeroStory) {
            Project::where('id', '!=', $project->id)->where('is_hero_story', true)->update(['is_hero_story' => false]);
        }

        $project->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'author_id' => $validated['author_id'] ?? $project->author_id,
            'subtitle' => $validated['subtitle'] ?? null,
            'category' => $validated['category'],
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
            'year' => $validated['year'] ?? null,
            'area' => $validated['area'] ?? null,
            'collaboration' => $validated['collaboration'] ?? null,
            'price' => $validated['price'] ?? null,
            'status' => $validated['status'], // Admin can edit and approve!
            'admin_notes' => $validated['admin_notes'] ?? null,
            'is_featured' => $isFeatured,
            'is_spotlight' => $isSpotlight,
            'is_hero_story' => $isHeroStory,
            'is_property_sell' => $request->boolean('is_property_sell'),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'featured_image' => $featuredImage,
            'gallery' => $gallery,
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully! Current status: ' . strtoupper($project->status));
    }

    /**
     * Single Exclusive Feature Selection (Project of the Week, Nook Spotlight, Main Hero Story)
     * When one is selected, all others are automatically unmarked/removed!
     */
    public function setFeature(Request $request, int $id, string $feature)
    {
        $project = Project::findOrFail($id);

        if (!in_array($feature, ['is_featured', 'is_spotlight', 'is_hero_story'])) {
            return back()->with('error', 'Invalid feature option.');
        }

        $featureLabels = [
            'is_featured' => 'Project of the Week',
            'is_spotlight' => 'Nook Spotlight',
            'is_hero_story' => 'Main Hero Story',
        ];
        $label = $featureLabels[$feature] ?? $feature;

        if ($project->$feature) {
            // Already active, turn it off
            $project->update([$feature => false]);
            return back()->with('success', "Removed '{$project->title}' from {$label}.");
        } else {
            // Remove previous active ones from all other projects
            Project::where('id', '!=', $project->id)->where($feature, true)->update([$feature => false]);
            // Activate on this project
            $project->update([$feature => true]);
            return back()->with('success', "Set '{$project->title}' as the exclusive {$label}! (Previous selection removed)");
        }
    }

    public function approve(int $id)
    {
        $project = Project::findOrFail($id);
        $project->update(['status' => 'approved']);

        return back()->with('success', 'Project "' . $project->title . '" has been APPROVED and is now live on the site.');
    }

    public function reject(Request $request, int $id)
    {
        $project = Project::findOrFail($id);
        $reason = $request->input('reason', 'Submission does not meet architectural editorial criteria.');
        $project->update([
            'status' => 'rejected',
            'admin_notes' => $reason
        ]);

        return back()->with('warning', 'Project "' . $project->title . '" has been marked as REJECTED.');
    }

    public function destroy(int $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
}
