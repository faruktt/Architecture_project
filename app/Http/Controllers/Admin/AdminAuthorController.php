<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AdminAuthorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Author::withCount(['projects', 'products', 'followers', 'following']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['active', 'banned'])) {
            $query->where('status', $status);
        }

        $authors = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'total' => Author::count(),
            'active' => Author::where('status', 'active')->count(),
            'banned' => Author::where('status', 'banned')->count(),
        ];

        return view('admin.authors.index', compact('authors', 'search', 'status', 'counts'));
    }

    public function show(int $id)
    {
        $author = Author::withCount(['followers', 'following'])
            ->with(['projects' => fn($q) => $q->latest(), 'products' => fn($q) => $q->latest()])
            ->findOrFail($id);

        return view('admin.authors.show', compact('author'));
    }

    public function toggleStatus(int $id)
    {
        $author = Author::findOrFail($id);
        $newStatus = $author->status === 'active' ? 'banned' : 'active';
        $author->update(['status' => $newStatus]);

        return back()->with('success', "Author {$author->name} status changed to {$newStatus}.");
    }

    public function destroy(int $id)
    {
        $author = Author::findOrFail($id);
        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Author deleted successfully.');
    }
}
