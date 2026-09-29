<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Author;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Product::with('author');

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all' => Product::count(),
            'pending' => Product::where('status', 'pending')->count(),
            'approved' => Product::where('status', 'approved')->count(),
            'rejected' => Product::where('status', 'rejected')->count(),
        ];

        return view('admin.products.index', compact('products', 'status', 'search', 'counts'));
    }

    public function create()
    {
        $authors = Author::where('status', 'active')->get();
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

        return view('admin.products.create', compact('authors', 'categories'));
    }

    public function store(Request $request)
    {
        // Pre-normalize incoming fields from forms
        $data = $request->all();
        if (empty($data['short_description']) && !empty($data['excerpt'])) {
            $data['short_description'] = $data['excerpt'];
        }
        if (empty($data['use_description']) && !empty($data['description'])) {
            $data['use_description'] = $data['description'];
        }
        if (empty($data['phone']) && !empty($data['contact_phone'])) {
            $data['phone'] = $data['contact_phone'];
        }
        if (empty($data['email']) && !empty($data['contact_email'])) {
            $data['email'] = $data['contact_email'];
        }

        // If featured_image was uploaded as featured_image_file
        if ($request->hasFile('featured_image_file')) {
            $request->files->set('featured_image', $request->file('featured_image_file'));
        }

        // If featured_image came as a string URL instead of a file
        if (isset($data['featured_image']) && is_string($data['featured_image']) && !empty($data['featured_image'])) {
            if (empty($data['featured_image_url'])) {
                $data['featured_image_url'] = $data['featured_image'];
            }
            unset($data['featured_image']);
        }

        $request->merge($data);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'nullable|exists:authors,id',
            'manufacturer' => 'required|string|max:255',
            'category' => 'required|string',
            'country_region' => 'nullable|string|max:100',
            'has_bim' => 'nullable|boolean',
            'website_url' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:pending,approved,rejected',
            'is_property_sell' => 'nullable|boolean',
            'price' => 'nullable|string|max:100',
            'short_description' => 'required|string|max:1000',
            'use_description' => 'nullable|string',
            'applications' => 'nullable|string',
            'characteristics' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'featured_image_url' => 'nullable|url',
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

        if ($request->filled('gallery_images_text')) {
            $lines = array_filter(array_map('trim', explode("\n", $request->input('gallery_images_text'))));
            if (!empty($lines)) {
                $gallery = array_merge($gallery, array_values($lines));
            }
        }

        if (empty($gallery) && $featuredImage) {
            $gallery = [$featuredImage];
        }

        Product::create([
            'author_id' => $validated['author_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $slug,
            'manufacturer' => $validated['manufacturer'],
            'category' => $validated['category'],
            'country_region' => $validated['country_region'] ?? 'Global',
            'has_bim' => $request->boolean('has_bim'),
            'website_url' => $validated['website_url'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => $validated['status'],
            'is_property_sell' => $request->boolean('is_property_sell'),
            'price' => $validated['price'] ?? null,
            'short_description' => $validated['short_description'],
            'use_description' => $validated['use_description'] ?? null,
            'applications' => $validated['applications'] ?? null,
            'characteristics' => $validated['characteristics'] ?? null,
            'featured_image' => $featuredImage,
            'gallery' => $gallery,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(int $id)
    {
        $product = Product::with('author')->findOrFail($id);
        $authors = Author::where('status', 'active')->get();
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

        return view('admin.products.edit', compact('product', 'authors', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->all();
        if (empty($data['short_description']) && !empty($data['excerpt'])) {
            $data['short_description'] = $data['excerpt'];
        }
        if (empty($data['use_description']) && !empty($data['description'])) {
            $data['use_description'] = $data['description'];
        }
        if (empty($data['phone']) && !empty($data['contact_phone'])) {
            $data['phone'] = $data['contact_phone'];
        }
        if (empty($data['email']) && !empty($data['contact_email'])) {
            $data['email'] = $data['contact_email'];
        }

        if ($request->hasFile('featured_image_file')) {
            $request->files->set('featured_image', $request->file('featured_image_file'));
        }

        if (isset($data['featured_image']) && is_string($data['featured_image']) && !empty($data['featured_image'])) {
            if (empty($data['featured_image_url'])) {
                $data['featured_image_url'] = $data['featured_image'];
            }
            unset($data['featured_image']);
        }

        $request->merge($data);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $id,
            'author_id' => 'nullable|exists:authors,id',
            'manufacturer' => 'required|string|max:255',
            'category' => 'required|string',
            'country_region' => 'nullable|string|max:100',
            'has_bim' => 'nullable|boolean',
            'website_url' => 'nullable|url|max:255', // Website link field
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:pending,approved,rejected', // Admin approves here
            'admin_notes' => 'nullable|string|max:1000',
            'is_property_sell' => 'nullable|boolean',
            'price' => 'nullable|string|max:100',
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
                    $gFile->move(public_path('uploads'), $filename ?? $gFilename);
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

        $product->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'author_id' => $validated['author_id'] ?? $product->author_id,
            'manufacturer' => $validated['manufacturer'],
            'category' => $validated['category'],
            'country_region' => $validated['country_region'] ?? 'Global',
            'has_bim' => $request->boolean('has_bim'),
            'website_url' => $validated['website_url'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'is_property_sell' => $request->boolean('is_property_sell'),
            'price' => $validated['price'] ?? null,
            'short_description' => $validated['short_description'],
            'use_description' => $validated['use_description'] ?? null,
            'applications' => $validated['applications'] ?? null,
            'characteristics' => $validated['characteristics'] ?? null,
            'featured_image' => $featuredImage,
            'gallery' => $gallery,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully! Current status: ' . strtoupper($product->status));
    }

    public function togglePropertySell(int $id)
    {
        $product = Product::findOrFail($id);
        $newStatus = !$product->is_property_sell;
        $product->update(['is_property_sell' => $newStatus]);

        $msg = $newStatus
            ? "Product '{$product->title}' is now selected for Property Sell Post on homepage."
            : "Product '{$product->title}' removed from Property Sell Post.";

        return back()->with('success', $msg);
    }

    public function approve(int $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'approved']);

        return back()->with('success', 'Product "' . $product->title . '" has been APPROVED and published.');
    }

    public function reject(int $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'rejected']);

        return back()->with('warning', 'Product "' . $product->title . '" marked as REJECTED.');
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
