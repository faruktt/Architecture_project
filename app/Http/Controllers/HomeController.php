<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Product;
use App\Models\Article;
use App\Models\Country;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $selectedCountry = $request->query('country', null);

        // Dynamic active countries from admin management
        $countries = Country::active()->orderBy('order')->orderBy('name')->pluck('name')->toArray();
        if (empty($countries)) {
            $countries = [
                'Malaysia',
                'Indonesia',
                'Philippine',
                'Thailand',
                'Vietnam',
                'China',
                'Japan',
                'India',
                'Others',
            ];
        }

        // Query for projects
        $projectQuery = Project::approved()->with('author');

        if ($selectedCountry) {
            $projectQuery->where('country', $selectedCountry);
        }

        // Project of the Week (Single selection, fallback to latest approved)
        $projectOfTheWeek = Project::approved()
            ->when($selectedCountry, fn($q) => $q->where('country', $selectedCountry))
            ->where('is_featured', true)
            ->latest()
            ->first();

        if (!$projectOfTheWeek) {
            $projectOfTheWeek = Project::approved()
                ->when($selectedCountry, fn($q) => $q->where('country', $selectedCountry))
                ->latest()
                ->first();
        }

        // Nook Spotlight (Single selection, fallback to another approved)
        $nookSpotlight = Project::approved()
            ->when($selectedCountry, fn($q) => $q->where('country', $selectedCountry))
            ->where('is_spotlight', true)
            ->when($projectOfTheWeek, fn($q) => $q->where('id', '!=', $projectOfTheWeek->id))
            ->latest()
            ->first();

        if (!$nookSpotlight) {
            $nookSpotlight = Project::approved()
                ->when($selectedCountry, fn($q) => $q->where('country', $selectedCountry))
                ->when($projectOfTheWeek, fn($q) => $q->where('id', '!=', $projectOfTheWeek->id))
                ->latest()
                ->first();
        }

        // Main Hero Story below the top 2 cards (Selectable by admin, fallback if none marked)
        $heroStory = Project::approved()
            ->when($selectedCountry, fn($q) => $q->where('country', $selectedCountry))
            ->where('is_hero_story', true)
            ->when($projectOfTheWeek, fn($q) => $q->where('id', '!=', $projectOfTheWeek->id))
            ->when($nookSpotlight, fn($q) => $q->where('id', '!=', $nookSpotlight->id))
            ->latest()
            ->first();

        if (!$heroStory) {
            $heroStory = Project::approved()
                ->when($selectedCountry, fn($q) => $q->where('country', $selectedCountry))
                ->when($projectOfTheWeek, fn($q) => $q->where('id', '!=', $projectOfTheWeek->id))
                ->when($nookSpotlight, fn($q) => $q->where('id', '!=', $nookSpotlight->id))
                ->latest()
                ->first();
        }

        // Feed stories
        $editorialStories = Project::approved()
            ->when($heroStory, fn($q) => $q->where('id', '!=', $heroStory->id))
            ->when($projectOfTheWeek, fn($q) => $q->where('id', '!=', $projectOfTheWeek->id))
            ->when($nookSpotlight, fn($q) => $q->where('id', '!=', $nookSpotlight->id))
            ->when($selectedCountry, fn($q) => $q->where('country', $selectedCountry))
            ->latest()
            ->take(6)
            ->get();

        // Property sell posts (Right Column) - Products ONLY as requested by user
        $propertySellPosts = Product::approved()
            ->where('is_property_sell', true)
            ->latest()
            ->take(4)
            ->get();

        if ($propertySellPosts->isEmpty()) {
            $propertySellPosts = Product::approved()
                ->latest()
                ->take(2)
                ->get();
        }

        // Products Catalog box (Right Column)
        $catalogProduct = Product::approved()
            ->where('category', 'Lighting & Electrical')
            ->first() ?? Product::approved()->first();

        // Latest news/articles
        $articles = Article::where('status', 'published')->latest()->take(3)->get();

        return view('frontend.home', compact(
            'countries',
            'selectedCountry',
            'projectOfTheWeek',
            'nookSpotlight',
            'heroStory',
            'editorialStories',
            'propertySellPosts',
            'catalogProduct',
            'articles'
        ));
    }
}
