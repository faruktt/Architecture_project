<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthorProfileController extends Controller
{
    /**
     * Show the profile edit form for the authenticated author.
     */
    public function edit()
    {
        $author = Auth::guard('author')->user();
        $countries = \App\Models\Country::active()->orderBy('name')->get();
        return view('author.profile.edit', compact('author', 'countries'));
    }

    /**
     * Update the authenticated author's profile (name, email, password, avatar image, etc.).
     */
    public function update(Request $request)
    {
        $author = Auth::guard('author')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('authors', 'email')->ignore($author->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'avatar_url' => 'nullable|url',
            'company' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'bio' => 'nullable|string|max:3000',
        ]);

        $author->name = $validated['name'];
        $author->email = $validated['email'];

        if (array_key_exists('company', $validated)) {
            $author->company = $validated['company'];
        }
        if (array_key_exists('title', $validated)) {
            $author->title = $validated['title'];
        }
        if (array_key_exists('country', $validated)) {
            $author->country = $validated['country'];
        }
        if (array_key_exists('website', $validated)) {
            $author->website = $validated['website'];
        }
        if (array_key_exists('phone', $validated)) {
            $author->phone = $validated['phone'];
        }
        if (array_key_exists('bio', $validated)) {
            $author->bio = $validated['bio'];
        }

        // Update password if provided
        if (!empty($validated['password'])) {
            $author->password = Hash::make($validated['password']);
        }

        // Handle avatar image file upload directly to public/uploads/
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $author->avatar = $filename;
        } elseif (!empty($validated['avatar_url'])) {
            $author->avatar = $validated['avatar_url'];
        }

        $author->save();

        return redirect()->route('author.dashboard')->with('success', 'Your author profile was updated successfully.');
    }
}
