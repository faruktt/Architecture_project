<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $query = Article::news()->published();

        if ($category) {
            $query->where('category', $category);
        }

        $news = $query->latest('published_at')->paginate(10);
        $categories = Article::news()->published()->select('category')->distinct()->pluck('category');

        $selectedProducts = Product::approved()->latest()->take(3)->get();

        return view('frontend.news.index', compact('news', 'categories', 'category', 'selectedProducts'));
    }

    public function show(string $slug)
    {
        $newsItem = Article::news()->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $recentNews = Article::news()->where('status', 'published')
            ->where('id', '!=', $newsItem->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $selectedProducts = Product::approved()->latest()->take(3)->get();

        return view('frontend.news.show', compact('newsItem', 'recentNews', 'selectedProducts'));
    }
}
