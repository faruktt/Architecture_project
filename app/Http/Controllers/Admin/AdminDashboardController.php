<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Product;
use App\Models\Author;
use App\Models\Article;
use App\Models\Page;
use App\Models\ProductInquiry;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $stats = [
            'total_projects' => Project::count(),
            'pending_projects' => Project::where('status', 'pending')->count(),
            'approved_projects' => Project::where('status', 'approved')->count(),
            'total_products' => Product::count(),
            'pending_products' => Product::where('status', 'pending')->count(),
            'approved_products' => Product::where('status', 'approved')->count(),
            'total_authors' => Author::count(),
            'active_authors' => Author::where('status', 'active')->count(),
            'total_articles' => Article::count(),
            'total_pages' => Page::count(),
            'total_inquiries' => ProductInquiry::count(),
            'new_inquiries' => ProductInquiry::where('status', 'new')->count(),
        ];

        $pendingProjects = Project::with('author')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $pendingProducts = Product::with('author')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentAuthors = Author::latest()->take(5)->get();

        $featuredProject = Project::where('is_featured', true)->first();
        $spotlightProject = Project::where('is_spotlight', true)->first();
        $heroStoryProject = Project::where('is_hero_story', true)->first();
        $propertySellProductsCount = Product::where('is_property_sell', true)->count();
        $propertySellProducts = Product::where('is_property_sell', true)->latest()->take(2)->get();

        return view('admin.dashboard', compact(
            'admin',
            'stats',
            'pendingProjects',
            'pendingProducts',
            'recentAuthors',
            'featuredProject',
            'spotlightProject',
            'heroStoryProject',
            'propertySellProductsCount',
            'propertySellProducts'
        ));
    }
}
