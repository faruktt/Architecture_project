<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $query = Article::articles()->published();

        if ($category) {
            $query->where('category', $category);
        }

        $articles = $query->latest('published_at')->paginate(10);
        $categories = Article::articles()->published()->select('category')->distinct()->pluck('category');

        $selectedProducts = Product::approved()->latest()->take(3)->get();

        return view('frontend.articles.index', compact('articles', 'categories', 'category', 'selectedProducts'));
    }

    public function show(string $slug)
    {
        $article = Article::articles()->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $recentArticles = Article::articles()->where('status', 'published')
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $selectedProducts = Product::approved()->latest()->take(3)->get();

        return view('frontend.articles.show', compact('article', 'recentArticles', 'selectedProducts'));
    }
}
