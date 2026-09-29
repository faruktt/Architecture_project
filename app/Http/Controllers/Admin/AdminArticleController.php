<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminArticleController extends Controller
{
    public function index()
    {
        $articles = Article::articles()->latest()->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = ['Editorial', 'Essays', 'Interviews', 'Theory & Criticism', 'Material Science', 'Urbanism'];
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'author_name' => 'required|string|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|in:published,draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'image_url' => 'nullable|url',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'has_audio' => 'nullable|boolean',
            'has_video' => 'nullable|boolean',
            'badge_text' => 'nullable|string|max:50',
        ]);

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $image = $validated['image_url'] ?? null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $image = $filename;
        }

        if (!$image) {
            $image = 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80';
        }

        $gallery = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $gallery[] = $filename;
            }
        }

        Article::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'type' => 'article',
            'category' => $validated['category'],
            'author_name' => $validated['author_name'],
            'summary' => $validated['summary'],
            'content' => $validated['content'],
            'image' => $image,
            'gallery' => $gallery,
            'has_audio' => $request->boolean('has_audio', true),
            'has_video' => $request->boolean('has_video', false),
            'badge_text' => $request->input('badge_text'),
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Article published successfully.');
    }

    public function edit(int $id)
    {
        $article = Article::articles()->findOrFail($id);
        $categories = ['Editorial', 'Essays', 'Interviews', 'Theory & Criticism', 'Material Science', 'Urbanism'];
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $article = Article::articles()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug,' . $id,
            'category' => 'required|string',
            'author_name' => 'required|string|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|in:published,draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'image_url' => 'nullable|url',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'has_audio' => 'nullable|boolean',
            'has_video' => 'nullable|boolean',
            'badge_text' => 'nullable|string|max:50',
        ]);

        $image = $article->getRawOriginal('image');
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $image = $filename;
        } elseif (!empty($validated['image_url'])) {
            $image = $validated['image_url'];
        }

        $gallery = $article->getRawOriginal('gallery');
        $gallery = is_string($gallery) ? json_decode($gallery, true) : ($gallery ?? []);
        if (!is_array($gallery)) {
            $gallery = [];
        }

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $gallery[] = $filename;
            }
        }

        $article->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'type' => 'article',
            'category' => $validated['category'],
            'author_name' => $validated['author_name'],
            'summary' => $validated['summary'],
            'content' => $validated['content'],
            'image' => $image,
            'gallery' => $gallery,
            'has_audio' => $request->boolean('has_audio'),
            'has_video' => $request->boolean('has_video'),
            'badge_text' => $request->input('badge_text'),
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' && !$article->published_at ? now() : $article->published_at,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(int $id)
    {
        $article = Article::articles()->findOrFail($id);
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully.');
    }
}
