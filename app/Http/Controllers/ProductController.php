<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $manufacturer = $request->query('manufacturer');
        $country = $request->query('country');
        $bimOnly = $request->boolean('bim_only');
        $search = $request->query('search');

        $query = Product::approved()->with('author');

        if ($category) {
            $query->where('category', $category);
        }

        if ($manufacturer) {
            $query->where('manufacturer', $manufacturer);
        }

        if ($country) {
            $query->where('country_region', $country);
        }

        if ($bimOnly) {
            $query->where('has_bim', true);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $totalCount = $query->count();
        $products = $query->latest()->paginate(12)->withQueryString();

        // Filter options
        $dbCategories = \App\Models\ProductCategory::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        $categories = !empty($dbCategories)
            ? $dbCategories
            : Product::approved()->select('category')->distinct()->pluck('category')->toArray();

        $manufacturers = Product::approved()->select('manufacturer')->distinct()->pluck('manufacturer');

        $dbCountries = \App\Models\Country::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        $countries = !empty($dbCountries)
            ? $dbCountries
            : Product::approved()->select('country_region')->distinct()->pluck('country_region')->toArray();

        return view('frontend.products.index', compact(
            'products',
            'totalCount',
            'categories',
            'manufacturers',
            'countries',
            'category',
            'manufacturer',
            'country',
            'bimOnly',
            'search'
        ));
    }

    public function show(string $slug)
    {
        $product = Product::with(['author.followers'])
            ->where('slug', $slug)
            ->firstOrFail();

        $product->increment('views_count');

        // Check if author is followed
        $currentAuthor = Auth::guard('author')->user();
        $isFollowing = $currentAuthor && $product->author ? $product->author->isFollowedBy($currentAuthor) : false;

        // More products by same manufacturer or category
        $moreProducts = Product::approved()
            ->where('id', '!=', $product->id)
            ->where(function($q) use ($product) {
                $q->where('manufacturer', $product->manufacturer)
                  ->orWhere('category', $product->category);
            })
            ->take(4)
            ->get();

        return view('frontend.products.show', compact('product', 'isFollowing', 'moreProducts'));
    }

    public function submitInquiry(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ProductInquiry::create(array_merge($validated, [
            'product_id' => $product->id,
            'status' => 'new',
        ]));

        return back()->with('success', 'Your message has been sent to ' . $product->manufacturer . ' successfully! They will contact you shortly.');
    }
}
