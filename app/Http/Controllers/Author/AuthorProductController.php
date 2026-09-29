<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthorProductController extends Controller
{
    public function index()
    {
        $author = Auth::guard('author')->user();
        $products = $author->products()->latest()->paginate(10);

        return view('author.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = [
                'Software / Courses',
                'Kitchen & Bath',
                'Lighting & Electrical',
                'Finishes',
                'Facade & Exterior',
                'Furniture',
                'Doors & Windows',
                'Acoustics & Insulation',
                'Structural Materials',
            ];
        }

        return view('author.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $author = Auth::guard('author')->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'category' => 'required|string',
            'country_region' => 'nullable|string|max:100',
            'has_bim' => 'nullable|boolean',
            'website_url' => 'nullable|url|max:255', // Website link allowed!
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'short_description' => 'required|string|max:1000',
            'use_description' => 'nullable|string',
            'applications' => 'nullable|string',
            'characteristics' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'featured_image_url' => 'nullable|url',
            'gallery_urls' => 'nullable|string',
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
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
            $featuredImage = 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=1000&q=80';
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

        if (!empty($validated['gallery_urls'])) {
            $extra = array_filter(array_map('trim', explode("\n", $validated['gallery_urls'])));
            $gallery = array_merge($gallery, $extra);
        }
        if (empty($gallery)) {
            $gallery = [$featuredImage];
        }

        $product = Product::create([
            'author_id' => $author->id,
            'title' => $validated['title'],
            'slug' => $slug,
            'manufacturer' => $validated['manufacturer'],
            'category' => $validated['category'],
            'country_region' => $validated['country_region'] ?? 'Global',
            'has_bim' => $request->boolean('has_bim'),
            'website_url' => $validated['website_url'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'short_description' => $validated['short_description'],
            'use_description' => $validated['use_description'] ?? null,
            'applications' => $validated['applications'] ?? null,
            'characteristics' => $validated['characteristics'] ?? null,
            'featured_image' => $featuredImage,
            'gallery' => $gallery,
            'status' => 'pending', // REQUIRED: Starts as pending until admin approval!
        ]);

        return redirect()->route('author.products.index')->with('success', 'Product "' . $product->title . '" submitted successfully! Admin will review and approve it.');
    }

    public function edit(int $id)
    {
        $author = Auth::guard('author')->user();
        $product = $author->products()->findOrFail($id);

        $categories = ProductCategory::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = [
                'Software / Courses',
                'Kitchen & Bath',
                'Lighting & Electrical',
                'Finishes',
                'Facade & Exterior',
                'Furniture',
                'Doors & Windows',
                'Acoustics & Insulation',
                'Structural Materials',
            ];
        }

        return view('author.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $author = Auth::guard('author')->user();
        $product = $author->products()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'category' => 'required|string',
            'country_region' => 'nullable|string|max:100',
            'has_bim' => 'nullable|boolean',
            'website_url' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'short_description' => 'required|string|max:1000',
            'use_description' => 'nullable|string',
            'applications' => 'nullable|string',
            'characteristics' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'featured_image_url' => 'nullable|url',
        ]);

        $featuredImage = $product->getRawOriginal('featured_image');
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $featuredImage = $filename;
        } elseif (!empty($validated['featured_image_url'])) {
            $featuredImage = $validated['featured_image_url'];
        }

        $rawGallery = $product->getRawOriginal('gallery');
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

        if (!empty($validated['gallery_urls'])) {
            $extra = array_filter(array_map('trim', explode("\n", $validated['gallery_urls'])));
            $gallery = array_merge($gallery, $extra);
        }
        if (empty($gallery) && $featuredImage) {
            $gallery = [$featuredImage];
        }

        $product->update([
            'title' => $validated['title'],
            'manufacturer' => $validated['manufacturer'],
            'category' => $validated['category'],
            'country_region' => $validated['country_region'] ?? 'Global',
            'has_bim' => $request->boolean('has_bim'),
            'website_url' => $validated['website_url'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'short_description' => $validated['short_description'],
            'use_description' => $validated['use_description'] ?? null,
            'applications' => $validated['applications'] ?? null,
            'characteristics' => $validated['characteristics'] ?? null,
            'featured_image' => $featuredImage,
            'gallery' => $gallery,
            'status' => 'pending', // Re-verify on edit
        ]);

        return redirect()->route('author.products.index')->with('success', 'Product updated successfully and re-submitted for admin review.');
    }

    public function destroy(int $id)
    {
        $author = Auth::guard('author')->user();
        $product = $author->products()->findOrFail($id);
        $product->delete();

        return redirect()->route('author.products.index')->with('success', 'Product deleted successfully.');
    }
}
