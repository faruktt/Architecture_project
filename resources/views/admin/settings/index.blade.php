@extends('layouts.admin')

@section('title', 'Settings | nook')

@section('content')
<div class="max-w-4xl mx-auto py-2 space-y-6">

    <div class="border-b border-slate-200 pb-5">
        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Settings</h1>
    </div>

    <!-- BROWSER TAB TITLE & FAVICON SETTINGS -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-xs space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                <span>Browser Title & Favicon Control</span>
            </h2>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
                <div class="space-y-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Browser Tab Title</label>
                        <input type="text" 
                               name="site_title" 
                               value="{{ old('site_title', $siteTitle) }}" 
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none font-medium" 
                               placeholder="e.g. nook MAGAZINE | Architecture & Design">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Upload Favicon Icon</label>
                        <input type="file" 
                               name="site_favicon" 
                               accept=".ico,.png,.svg,.webp,.jpg,.jpeg"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 file:cursor-pointer">
                        <span class="text-[11px] text-slate-400 mt-1.5 block font-medium">Supported: ICO, PNG, SVG, WEBP, JPG</span>
                    </div>

                    @if($siteFaviconFilename)
                        <div class="pt-1">
                            <label class="flex items-center gap-2 text-rose-700 font-medium cursor-pointer">
                                <input type="checkbox" name="remove_favicon" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-0">
                                <span>Remove custom favicon</span>
                            </label>
                        </div>
                    @endif
                </div>

                <!-- Live Browser Tab Preview Card -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Live Browser Tab Preview</span>
                    
                    <!-- Mock browser chrome -->
                    <div class="bg-slate-200/80 rounded-xl border border-slate-300/80 overflow-hidden shadow-2xs">
                        <div class="bg-slate-200 px-3 py-2 flex items-center gap-2 border-b border-slate-300">
                            <!-- 3 window buttons -->
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-400 inline-block"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                            </div>
                            <!-- Mock Tab -->
                            <div class="ml-2 bg-white rounded-t-lg px-3 py-1.5 flex items-center gap-2 text-xs font-semibold text-slate-800 shadow-xs max-w-[240px] border border-b-0 border-slate-300 -mb-2">
                                @if($siteFavicon)
                                    <img src="{{ $siteFavicon }}" alt="Favicon Preview" class="w-4 h-4 object-contain rounded-xs shrink-0">
                                @else
                                    <div class="w-4 h-4 rounded-xs bg-[#fe5d70] flex items-center justify-center text-[9px] font-black text-white shrink-0">
                                        a
                                    </div>
                                @endif
                                <span class="truncate font-medium text-[11px]">{{ $siteTitle }}</span>
                                <span class="text-slate-400 hover:text-slate-600 ml-auto text-[10px]">&times;</span>
                            </div>
                        </div>
                        <div class="bg-white p-5"></div>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-xs transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>

    <!-- SITE BRANDING & LOGO CONTROL -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-xs space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                <span>Magazine Logo & Branding</span>
            </h2>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
                <div class="space-y-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Brand Logo Text</label>
                        <input type="text" 
                               name="site_logo_text" 
                               value="{{ old('site_logo_text', $siteLogoText) }}" 
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none font-bold" 
                               placeholder="e.g. nook">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Upload Custom Logo Image</label>
                        <input type="file" 
                               name="site_logo" 
                               accept=".png,.svg,.jpg,.jpeg,.webp" 
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 file:cursor-pointer">
                        <span class="text-[11px] text-slate-400 mt-1.5 block font-medium">Supported: PNG, SVG, JPG, WEBP (Max 2MB)</span>
                    </div>

                    @if($siteLogoFilename)
                        <div class="pt-1">
                            <label class="flex items-center gap-2 text-rose-700 font-medium cursor-pointer">
                                <input type="checkbox" name="remove_logo_image" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-0">
                                <span>Remove current logo</span>
                            </label>
                        </div>
                    @endif
                </div>

                <!-- Live Logo Preview Card -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-3">
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
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-xs transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>

    <!-- FOOTER SOCIAL MEDIA ICONS & LINKS CONTROL -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-xs space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                <span>Footer Social Media Links</span>
            </h2>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($socialLinks as $key => $social)
                    <div class="p-3.5 rounded-xl border {{ $social['active'] && !empty($social['url']) ? 'border-blue-200 bg-blue-50/20' : 'border-slate-200 bg-slate-50/50' }} flex flex-col justify-between gap-2.5 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-slate-900 text-white flex items-center justify-center p-1 shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                        {!! $social['svg'] !!}
                                    </svg>
                                </span>
                                <strong class="text-xs font-bold text-slate-900 block leading-tight">{{ $social['name'] }}</strong>
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
                            <input type="url"
                                   name="social_links[{{ $key }}][url]"
                                   value="{{ old('social_links.' . $key . '.url', $social['url']) }}"
                                   placeholder="{{ $social['placeholder'] }}"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg font-mono text-[11px] focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-xs transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Save Social Links</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
