@extends('layouts.admin')

@section('title', 'System Settings & Architecture Status | nook')

@section('content')
<div class="max-w-4xl mx-auto py-2 space-y-6">

    <div class="border-b border-slate-200 pb-5">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">Branding & System Configuration</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Site Settings & Branding</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Configure magazine branding logo, multi-guard authentication status, and storage metrics.</p>
    </div>

    <!-- SITE BRANDING & LOGO CONTROL (USER PROMPT SPECIFIED) -->
    <!-- "logo ta admin panel theke admin change korte parbe nook ata,, r sob image public/uploads/ ai path a uploads hobe r database a sudhu file name store hobe frontend a ai rokom vabe show hobe" -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-sm space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    <span>Magazine Logo & Branding Control</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Admin can customize the brand logo text or upload a custom logo image (saved to <code class="text-slate-800 font-mono bg-slate-100 px-1 py-0.5 rounded">public/uploads/</code>).</p>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200 px-2.5 py-1 rounded-full">Editorial Brand</span>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
                <div class="space-y-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Brand Logo Text</label>
                        <input type="text" name="site_logo_text" value="{{ old('site_logo_text', $siteLogoText) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none font-bold" placeholder="e.g. nook">
                        <span class="text-[11px] text-slate-400 mt-1 block">Displayed when no image logo is uploaded or as fallback.</span>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Upload Custom Logo Image</label>
                        <input type="file" name="site_logo" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 file:cursor-pointer">
                        <span class="text-[11px] text-slate-400 mt-1 block">PNG, SVG, JPG or WEBP (Max 2MB). Uploads to <code>public/uploads/</code>.</span>
                    </div>

                    @if($siteLogoFilename)
                        <div class="pt-2">
                            <label class="flex items-center gap-2 text-rose-700 font-medium cursor-pointer">
                                <input type="checkbox" name="remove_logo_image" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-0">
                                <span>Remove current logo image and revert to text logo</span>
                            </label>
                        </div>
                    @endif
                </div>

                <!-- Live Logo Preview Card -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Current Header Logo Preview</span>
                    <div class="h-24 bg-white border border-slate-200/80 rounded-xl p-4 flex items-center justify-center shadow-2xs">
                        @if($siteLogo)
                            <img src="{{ $siteLogo }}" alt="Logo" class="max-h-14 max-w-[200px] object-contain">
                        @else
                            <div class="text-center">
                                <span class="text-3xl font-black text-slate-950 lowercase tracking-tight block" style="font-family: 'Cinzel', serif;">{{ $siteLogoText }}</span>
                                <span class="text-[8px] font-bold tracking-[0.35em] text-slate-600 uppercase block mt-0.5">MAGAZINE</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-[11px] text-slate-500 flex items-center justify-between">
                        <span>Stored in Database:</span>
                        <code class="font-mono text-slate-800 bg-white px-1.5 py-0.5 rounded border border-slate-200">
                            {{ $siteLogoFilename ?: '(Text mode: ' . $siteLogoText . ')' }}
                        </code>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Save Logo & Branding Changes</span>
                </button>
            </div>
        </form>
    </div>

    <!-- FOOTER SOCIAL MEDIA ICONS & LINKS CONTROL (USER PROMPT SPECIFIED) -->
    <!-- "r footer a social icon o amn hobe admin panel theke jeita jeita link deye active korbe icone soho debe home page a oita show hobe" -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-sm space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <span>Footer Social Media Links & Icons</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Activate or deactivate social icons on the homepage footer and enter their destination URLs.</p>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200 px-2.5 py-1 rounded-full">Footer Socials</span>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($socialLinks as $key => $social)
                    <div class="p-4 rounded-xl border {{ $social['active'] && !empty($social['url']) ? 'border-blue-200 bg-blue-50/20' : 'border-slate-200 bg-slate-50/50' }} flex flex-col justify-between gap-3 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center p-1.5 shadow-2xs">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        {!! $social['svg'] !!}
                                    </svg>
                                </span>
                                <div>
                                    <strong class="text-xs font-bold text-slate-900 block leading-tight">{{ $social['name'] }}</strong>
                                    <span class="text-[10px] text-slate-400">Social platform</span>
                                </div>
                            </div>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer font-semibold text-slate-700 select-none">
                                <input type="checkbox"
                                       name="social_links[{{ $key }}][active]"
                                       value="1"
                                       {{ $social['active'] ? 'checked' : '' }}
                                       class="rounded border-slate-300 text-blue-600 focus:ring-0">
                                <span class="text-[11px] {{ $social['active'] ? 'text-blue-700 font-bold' : 'text-slate-500' }}">Active</span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-slate-600 mb-1">Profile / Channel URL</label>
                            <input type="url"
                                   name="social_links[{{ $key }}][url]"
                                   value="{{ old('social_links.' . $key . '.url', $social['url']) }}"
                                   placeholder="{{ $social['placeholder'] }}"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg font-mono text-[11px] focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] text-slate-500">Only enabled platforms with a valid URL will appear on the frontend footer.</span>
                <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Save Social Media Links</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Dual Guard Authentication Status Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-sm space-y-5">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2.5">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            Dual Authentication Guards Status
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-5 bg-slate-50 border border-slate-200 rounded-xl space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-amber-800 font-extrabold uppercase text-[10px] tracking-wider block bg-amber-50 px-2 py-0.5 rounded border border-amber-200/80">Guard: admin</span>
                    <span class="text-emerald-700 font-bold text-[11px] flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                    </span>
                </div>
                <p class="text-slate-600">Model: <code class="text-slate-900 font-mono bg-white px-1.5 py-0.5 rounded border border-slate-200">App\Models\Admin</code></p>
                <p class="text-slate-600">Provider: <code class="text-slate-900 font-mono bg-white px-1.5 py-0.5 rounded border border-slate-200">admins (eloquent)</code></p>
                <p class="text-slate-600">Driver: <code class="text-slate-900 font-mono bg-white px-1.5 py-0.5 rounded border border-slate-200">session</code></p>
            </div>

            <div class="p-5 bg-slate-50 border border-slate-200 rounded-xl space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-blue-800 font-extrabold uppercase text-[10px] tracking-wider block bg-blue-50 px-2 py-0.5 rounded border border-blue-200/80">Guard: author</span>
                    <span class="text-emerald-700 font-bold text-[11px] flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                    </span>
                </div>
                <p class="text-slate-600">Model: <code class="text-slate-900 font-mono bg-white px-1.5 py-0.5 rounded border border-slate-200">App\Models\Author</code></p>
                <p class="text-slate-600">Provider: <code class="text-slate-900 font-mono bg-white px-1.5 py-0.5 rounded border border-slate-200">authors (eloquent)</code></p>
                <p class="text-slate-600">Driver: <code class="text-slate-900 font-mono bg-white px-1.5 py-0.5 rounded border border-slate-200">session</code></p>
            </div>
        </div>
    </div>

    <!-- Quick Database Stats -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-sm space-y-5">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Database & Publication Metrics</h2>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-slate-400 uppercase text-[10px] font-bold block mb-1">Total Projects</span>
                <strong class="text-2xl text-slate-900 font-black">{{ \App\Models\Project::count() }}</strong>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-slate-400 uppercase text-[10px] font-bold block mb-1">Total Products</span>
                <strong class="text-2xl text-slate-900 font-black">{{ \App\Models\Product::count() }}</strong>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-slate-400 uppercase text-[10px] font-bold block mb-1">Registered Authors</span>
                <strong class="text-2xl text-slate-900 font-black">{{ \App\Models\Author::count() }}</strong>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-slate-400 uppercase text-[10px] font-bold block mb-1">Published Articles</span>
                <strong class="text-2xl text-slate-900 font-black">{{ \App\Models\Article::count() }}</strong>
            </div>
        </div>
    </div>

    <!-- System Diagnostics -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Platform Environment Diagnostics</h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-slate-400 block text-[10px] font-bold uppercase mb-1">Framework</span>
                <strong class="text-slate-900 block font-bold">Laravel {{ app()->version() }}</strong>
                <span class="text-slate-500 text-[11px]">PHP {{ phpversion() }}</span>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-slate-400 block text-[10px] font-bold uppercase mb-1">Database Connection</span>
                <strong class="text-slate-900 block font-bold">{{ config('database.default') }}</strong>
                <span class="text-slate-500 text-[11px]">{{ config('database.connections.' . config('database.default') . '.database') }}</span>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-slate-400 block text-[10px] font-bold uppercase mb-1">Storage Path</span>
                <strong class="text-slate-900 block font-bold">public/uploads/</strong>
                <span class="text-slate-500 text-[11px]">File name only in DB</span>
            </div>
        </div>
    </div>

</div>
@endsection
