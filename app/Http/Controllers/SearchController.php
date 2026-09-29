<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Product;
use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('q', ''));
        $tab = $request->input('tab', 'all');

        $projects = collect();
        $products = collect();
        $articles = collect();

        if ($query !== '') {
            if ($tab === 'all' || $tab === 'projects') {
                $projects = Project::where('status', 'approved')
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('category', 'like', "%{$query}%")
                          ->orWhere('country', 'like', "%{$query}%")
                          ->orWhere('city', 'like', "%{$query}%")
                          ->orWhere('excerpt', 'like', "%{$query}%");
                    })
                    ->latest()
                    ->take(20)
                    ->get();
            }

            if ($tab === 'all' || $tab === 'products') {
                $products = Product::where('status', 'approved')
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('manufacturer', 'like', "%{$query}%")
                          ->orWhere('category', 'like', "%{$query}%")
                          ->orWhere('short_description', 'like', "%{$query}%");
                    })
                    ->latest()
                    ->take(20)
                    ->get();
            }

            if ($tab === 'all' || $tab === 'articles') {
                $articles = Article::where('status', 'published')
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('category', 'like', "%{$query}%")
                          ->orWhere('summary', 'like', "%{$query}%");
                    })
                    ->latest()
                    ->take(20)
                    ->get();
            }
        }

        $totalCount = $projects->count() + $products->count() + $articles->count();

        return view('frontend.search', compact('query', 'tab', 'projects', 'products', 'articles', 'totalCount'));
    }
}
