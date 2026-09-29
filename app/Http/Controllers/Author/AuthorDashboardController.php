<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthorDashboardController extends Controller
{
    public function index()
    {
        $author = Auth::guard('author')->user()->loadCount(['followers', 'following', 'projects', 'products']);

        $pendingProjectsCount = $author->projects()->where('status', 'pending')->count();
        $approvedProjectsCount = $author->projects()->where('status', 'approved')->count();
        $rejectedProjectsCount = $author->projects()->where('status', 'rejected')->count();

        $pendingProductsCount = $author->products()->where('status', 'pending')->count();
        $approvedProductsCount = $author->products()->where('status', 'approved')->count();

        $recentProjects = $author->projects()->latest()->take(5)->get();
        $recentProducts = $author->products()->latest()->take(5)->get();

        return view('author.dashboard', compact(
            'author',
            'pendingProjectsCount',
            'approvedProjectsCount',
            'rejectedProjectsCount',
            'pendingProductsCount',
            'approvedProductsCount',
            'recentProjects',
            'recentProducts'
        ));
    }
}
