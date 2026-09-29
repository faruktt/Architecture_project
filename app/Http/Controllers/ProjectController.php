<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $country = $request->query('country');
        $search = $request->query('search');

        $query = Project::approved()->with('author');

        if ($category) {
            $query->where('category', $category);
        }

        if ($country) {
            $query->where('country', $country);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->paginate(12)->withQueryString();

        $categories = ProjectCategory::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = ['Architecture', 'Interior', 'Residential', 'Commercial', 'Hospitality', 'Landscape'];
        }

        $countries = Country::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($countries)) {
            $countries = ['Malaysia', 'Indonesia', 'Philippine', 'Thailand', 'Vietnam', 'China', 'Japan', 'India', 'Others'];
        }

        return view('frontend.projects.index', compact('projects', 'categories', 'countries', 'category', 'country', 'search'));
    }

    public function show(string $slug)
    {
        $project = Project::with(['author.followers'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views
        $project->increment('views_count');

        // Check if currently authenticated author follows this project's author
        $currentAuthor = Auth::guard('author')->user();
        $isFollowing = $currentAuthor && $project->author ? $project->author->isFollowedBy($currentAuthor) : false;

        // Related projects
        $relatedProjects = Project::approved()
            ->where('id', '!=', $project->id)
            ->where(function($q) use ($project) {
                $q->where('category', $project->category)
                  ->orWhere('country', $project->country);
            })
            ->take(3)
            ->get();

        return view('frontend.projects.show', compact('project', 'isFollowing', 'relatedProjects'));
    }
}
