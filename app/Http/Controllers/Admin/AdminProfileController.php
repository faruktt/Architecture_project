<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    /**
     * Show the profile edit form for the authenticated administrator.
     */
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update the authenticated administrator's profile (name, email, password, avatar image).
     */
    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($admin->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'remove_avatar' => 'nullable|boolean',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        // Update password if provided
        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        // Handle avatar removal request
        if ($request->boolean('remove_avatar')) {
            $admin->avatar = null;
        }

        // Handle avatar image file upload directly to public/uploads/
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'admin_avatar_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $admin->avatar = $filename;
        }

        $admin->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Admin profile updated successfully.');
    }
}
