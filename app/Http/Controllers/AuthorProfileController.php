<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorProfileController extends Controller
{
    public function show(string $username)
    {
        $author = Author::with(['followers', 'following'])
            ->where('username', $username)
            ->firstOrFail();

        $projects = $author->approvedProjects()->latest()->paginate(9, ['*'], 'projects_page');
        $products = $author->approvedProducts()->latest()->paginate(9, ['*'], 'products_page');

        $currentAuthor = Auth::guard('author')->user();
        $isFollowing = $currentAuthor ? $author->isFollowedBy($currentAuthor) : false;

        return view('frontend.author-profile', compact('author', 'projects', 'products', 'isFollowing', 'currentAuthor'));
    }

    public function toggleFollow(Request $request, int $id)
    {
        $currentAuthor = Auth::guard('author')->user();

        if (!$currentAuthor) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'requires_auth' => true,
                    'redirect' => route('author.login'),
                    'message' => 'Please login as an author to follow this architect.'
                ], 401);
            }
            return redirect()->route('author.login')->with('info', 'Please log in to follow authors.');
        }

        if ($currentAuthor->id === $id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself.'
            ], 422);
        }

        $targetAuthor = Author::findOrFail($id);

        if ($targetAuthor->followers()->where('follower_id', $currentAuthor->id)->exists()) {
            $targetAuthor->followers()->detach($currentAuthor->id);
            $isFollowing = false;
            $message = 'You have unfollowed ' . $targetAuthor->name;
        } else {
            $targetAuthor->followers()->attach($currentAuthor->id);
            $isFollowing = true;
            $message = 'You are now following ' . $targetAuthor->name;
        }

        $followersCount = $targetAuthor->followers()->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_following' => $isFollowing,
                'followers_count' => $followersCount,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
