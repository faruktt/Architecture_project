@extends('layouts.author')

@section('title', 'Edit Author Profile | ' . \App\Models\Setting::logoText() . ' MAGAZINE')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-zinc-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-zinc-400 mb-1">
                <a href="{{ route('author.dashboard') }}" class="hover:text-black transition-colors">Author Studio</a>
                <span>/</span>
                <span class="text-zinc-700 font-medium">Account Settings</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Edit Author Profile</h1>
            <p class="text-xs text-zinc-500 mt-1">Manage your public architect identity, login credentials, and editorial bio.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <a href="{{ route('author.profile', $author->username) }}" target="_blank" class="flex-1 sm:flex-initial text-center px-3.5 py-2 bg-white text-zinc-700 hover:text-black rounded-lg text-xs font-semibold border border-zinc-200 shadow-sm transition-colors flex items-center justify-center gap-1.5">
                <span>View Public Profile</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <a href="{{ route('author.dashboard') }}" class="flex-1 sm:flex-initial text-center px-3.5 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 rounded-lg text-xs font-semibold transition-colors">
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <form action="{{ route('author.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Profile Picture / Avatar Card -->
        <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-sm">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 mb-1">Profile Photo & Identity</h2>
            <p class="text-xs text-zinc-500 mb-5">This photo will appear beside your project submissions, articles, and public architect showcase.</p>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <!-- Current / Preview Avatar -->
                <div class="relative group">
                    <img id="avatar-preview"
                         src="{{ $author->avatar }}"
                         alt="{{ $author->name }}"
                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover border-4 border-zinc-100 shadow-md">
                    <div class="absolute -bottom-1 -right-1 bg-black text-white p-1.5 rounded-full shadow border-2 border-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>

                <div class="flex-1 space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Upload New Picture</label>
                        <input type="file"
                               id="avatar-input"
                               name="avatar"
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="block w-full text-xs text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-zinc-900 file:text-white hover:file:bg-black file:cursor-pointer cursor-pointer border border-zinc-200 rounded-lg p-1.5 focus:outline-none focus:border-zinc-400">
                        <span class="text-[11px] text-zinc-400 mt-1 block">Supported formats: JPG, PNG, WebP. Recommended square size (e.g. 500x500px, max 5MB).</span>
                    </div>

                    <div class="pt-1">
                        <details class="text-xs text-zinc-500">
                            <summary class="cursor-pointer font-medium hover:text-black inline-flex items-center gap-1">
                                <span>Or provide an image URL</span>
                            </summary>
                            <div class="mt-2">
                                <input type="url"
                                       name="avatar_url"
                                       placeholder="https://example.com/photo.jpg"
                                       class="w-full text-xs px-3 py-2 border border-zinc-200 rounded-lg focus:outline-none focus:border-zinc-400">
                            </div>
                        </details>
                    </div>
                    @error('avatar')
                        <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 2. Basic Profile Information Card -->
        <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-sm space-y-5">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 mb-1">Account & Studio Details</h2>
            <p class="text-xs text-zinc-500 mb-4">Update your contact credentials and professional practice information.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-zinc-800 uppercase tracking-wide mb-1">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           required
                           value="{{ old('name', $author->name) }}"
                           placeholder="e.g. Ar. Sarah Jenkins"
                           class="w-full text-xs sm:text-sm px-3.5 py-2.5 bg-zinc-50 focus:bg-white border @error('name') border-red-500 @else border-zinc-300 @enderror rounded-lg focus:outline-none focus:ring-1 focus:ring-black transition-all">
                    @error('name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-zinc-800 uppercase tracking-wide mb-1">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           required
                           value="{{ old('email', $author->email) }}"
                           placeholder="architect@domain.com"
                           class="w-full text-xs sm:text-sm px-3.5 py-2.5 bg-zinc-50 focus:bg-white border @error('email') border-red-500 @else border-zinc-300 @enderror rounded-lg focus:outline-none focus:ring-1 focus:ring-black transition-all">
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title / Role -->
                <div>
                    <label for="title" class="block text-xs font-bold text-zinc-800 uppercase tracking-wide mb-1">
                        Professional Title
                    </label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $author->title) }}"
                           placeholder="e.g. Principal Architect / Partner"
                           class="w-full text-xs sm:text-sm px-3.5 py-2.5 bg-zinc-50 focus:bg-white border @error('title') border-red-500 @else border-zinc-300 @enderror rounded-lg focus:outline-none focus:ring-1 focus:ring-black transition-all">
                    @error('title')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Studio / Company -->
                <div>
                    <label for="company" class="block text-xs font-bold text-zinc-800 uppercase tracking-wide mb-1">
                        Architecture Studio / Practice
                    </label>
                    <input type="text"
                           id="company"
                           name="company"
                           value="{{ old('company', $author->company) }}"
                           placeholder="e.g. Atelier Maritime"
                           class="w-full text-xs sm:text-sm px-3.5 py-2.5 bg-zinc-50 focus:bg-white border @error('company') border-red-500 @else border-zinc-300 @enderror rounded-lg focus:outline-none focus:ring-1 focus:ring-black transition-all">
                    @error('company')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-xs font-bold text-zinc-800 uppercase tracking-wide mb-1">
                        Location / Country
                    </label>
                    <select id="country"
                            name="country"
                            class="w-full text-xs sm:text-sm px-3.5 py-2.5 bg-zinc-50 focus:bg-white border @error('country') border-red-500 @else border-zinc-300 @enderror rounded-lg focus:outline-none focus:ring-1 focus:ring-black transition-all">
                        <option value="">Select Country</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->name }}" {{ old('country', $author->country) == $c->name ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                        @if($author->country && !$countries->contains('name', $author->country))
                            <option value="{{ $author->country }}" selected>{{ $author->country }}</option>
                        @endif
                    </select>
                    @error('country')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-bold text-zinc-800 uppercase tracking-wide mb-1">
                        Phone / Direct Line
                    </label>
                    <input type="text"
                           id="phone"
                           name="phone"
                           value="{{ old('phone', $author->phone) }}"
                           placeholder="e.g. +60 3 2145 8890"
                           class="w-full text-xs sm:text-sm px-3.5 py-2.5 bg-zinc-50 focus:bg-white border @error('phone') border-red-500 @else border-zinc-300 @enderror rounded-lg focus:outline-none focus:ring-1 focus:ring-black transition-all">
                    @error('phone')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Website -->
                <div class="md:col-span-2">
                    <label for="website" class="block text-xs font-bold text-zinc-800 uppercase tracking-wide mb-1">
                        Studio Website
                    </label>
                    <input type="url"
                           id="website"
                           name="website"
                           value="{{ old('website', $author->website) }}"
                           placeholder="https://ateliermaritime.com"
                           class="w-full text-xs sm:text-sm px-3.5 py-2.5 bg-zinc-50 focus:bg-white border @error('website') border-red-500 @else border-zinc-300 @enderror rounded-lg focus:outline-none focus:ring-1 focus:ring-black transition-all">
                    @error('website')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Biography / Statement -->
                <div class="md:col-span-2">
                    <label for="bio" class="block text-xs font-bold text-zinc-800 uppercase tracking-wide mb-1">
                        Architectural Statement & Biography
                    </label>
                    <textarea id="bio"
                              name="bio"
                              rows="4"
                              placeholder="Write a brief overview of your design philosophy, notable commissions, or architectural focus..."
                              class="w-full text-xs sm:text-sm px-3.5 py-2.5 bg-zinc-50 focus:bg-white border @error('bio') border-red-500 @else border-zinc-300 @enderror rounded-lg focus:outline-none focus:ring-1 focus:ring-black transition-all">{{ old('bio', $author->bio) }}</textarea>
                    <span class="text-[11px] text-zinc-400 mt-1 block">Displayed on your public architect portfolio profile page.</span>
                    @error('bio')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 3. Password / Security Card -->
        <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 mb-1">Change Password</h2>
                    <p class="text-xs text-zinc-500">Leave these password fields completely blank if you do not want to change your current password.</p>
                </div>
                <span class="text-[11px] text-zinc-400 bg-zinc-100 px-2.5 py-1 rounded">Optional</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                <!-- New Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-zinc-800 uppercase tracking-wide mb-1">
                        New Password
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           autocomplete="new-password"
                           placeholder="•••••••• (min 6 characters)"
                           class="w-full text-xs sm:text-sm px-3.5 py-2.5 bg-zinc-50 focus:bg-white border @error('password') border-red-500 @else border-zinc-300 @enderror rounded-lg focus:outline-none focus:ring-1 focus:ring-black transition-all">
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-zinc-800 uppercase tracking-wide mb-1">
                        Confirm New Password
                    </label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           autocomplete="new-password"
                           placeholder="••••••••"
                           class="w-full text-xs sm:text-sm px-3.5 py-2.5 bg-zinc-50 focus:bg-white border border-zinc-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-black transition-all">
                </div>
            </div>
        </div>

        <!-- Form Action Buttons -->
        <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2.5 pt-2">
            <a href="{{ route('author.dashboard') }}" class="px-5 py-2.5 text-center bg-white border border-zinc-300 hover:bg-zinc-100 text-zinc-700 text-xs font-bold rounded-lg transition-colors shadow-sm">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-black hover:bg-zinc-800 text-white text-xs font-bold rounded-lg transition-all shadow-md flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Profile Changes</span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Live preview for avatar image file selection
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreview = document.getElementById('avatar-preview');

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    avatarPreview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush
@endsection
