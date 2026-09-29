<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthorAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('author')->check()) {
            return redirect()->route('author.dashboard');
        }
        return view('author.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('author')->attempt($credentials, $remember)) {
            $author = Auth::guard('author')->user();

            if ($author->status === 'banned') {
                Auth::guard('author')->logout();
                return back()->withErrors(['email' => 'Your author account has been suspended by the administrator.']);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('author.dashboard'))->with('success', 'Welcome back, ' . $author->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our author records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::guard('author')->check()) {
            return redirect()->route('author.dashboard');
        }
        return view('author.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:authors,email',
            'password' => 'required|string|min:6|confirmed',
            'company' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'country' => 'required|string|max:100',
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
        ]);

        // Generate clean username from name
        $baseUsername = Str::slug($validated['name']);
        $username = $baseUsername;
        $counter = 1;
        while (Author::where('username', $username)->exists()) {
            $username = $baseUsername . '-' . $counter++;
        }

        $author = Author::create([
            'name' => $validated['name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company' => $validated['company'] ?? null,
            'title' => $validated['title'] ?? 'Architect',
            'country' => $validated['country'],
            'bio' => $validated['bio'] ?? null,
            'website' => $validated['website'] ?? null,
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($validated['name']) . '&background=1b1b18&color=ffffff&size=200',
            'status' => 'active',
        ]);

        Auth::guard('author')->login($author);
        $request->session()->regenerate();

        return redirect()->route('author.dashboard')->with('success', 'Your author account has been created successfully!');
    }

    public function logout(Request $request)
    {
        Auth::guard('author')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
