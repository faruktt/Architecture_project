<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Editorial Admin | nook MAGAZINE')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Cinzel:wght@700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }
        .admin-sidebar {
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
        }
        .admin-content {
            background-color: #f8fafc;
        }
        /* Custom scrollbars for high-end feel */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row antialiased selection:bg-amber-200 selection:text-amber-950">

    @php
        $adminSiteLogo = \App\Models\Setting::logoUrl();
        $adminSiteLogoText = \App\Models\Setting::logoText();
    @endphp

    <!-- Mobile Header with Hamburger Toggle -->
    <div class="md:hidden bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                @if($adminSiteLogo)
                    <img src="{{ $adminSiteLogo }}" alt="{{ $adminSiteLogoText }}" class="h-6 max-w-[100px] object-contain">
                @else
                    <span class="font-black text-slate-900 text-lg tracking-tight lowercase" style="font-family: 'Cinzel', serif;">{{ $adminSiteLogoText }}</span>
                @endif
            </a>
            <span class="text-[9px] font-bold bg-amber-100 text-amber-900 border border-amber-200 px-1.5 py-0.5 rounded-full uppercase tracking-wider">ADMIN</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" target="_blank" class="text-xs text-slate-500 hover:text-slate-900 font-medium px-2 py-1 rounded bg-slate-100">
                Site &nearr;
            </a>
            <button type="button"
                    onclick="document.getElementById('admin-sidebar').classList.toggle('hidden')"
                    class="p-1.5 text-slate-700 hover:text-slate-950 hover:bg-slate-100 rounded-lg focus:outline-none"
                    aria-label="Toggle Navigation Sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <!-- ================= ADMIN SIDEBAR ================= -->
    <!-- "admin sidebar asob feature thakbe ,, page create, author management, project , product, news chara o sob feature admin ar sidebar a thakbe." -->
    <aside id="admin-sidebar" class="hidden md:flex w-full md:w-64 admin-sidebar flex-shrink-0 flex-col justify-between py-6 min-h-screen">
        <div>
            <!-- Brand / Logo -->
            <div class="px-6 pb-6 border-b border-slate-100">
                <a href="{{ route('admin.dashboard') }}" class="block group">
                    <div class="flex items-center gap-2.5">
                        @if($adminSiteLogo)
                            <img src="{{ $adminSiteLogo }}" alt="{{ $adminSiteLogoText }}" class="h-7 max-w-[120px] object-contain">
                        @else
                            <span class="text-2xl font-black tracking-tight text-slate-950 group-hover:text-amber-600 transition-colors lowercase" style="font-family: 'Cinzel', serif;">{{ $adminSiteLogoText }}</span>
                        @endif
                        <span class="text-[9px] font-black uppercase tracking-widest bg-amber-50 border border-amber-200 text-amber-800 px-2 py-0.5 rounded-md">Control</span>
                    </div>
                    <span class="text-[11px] font-medium text-slate-400 block mt-1 tracking-wide">Architecture Editorial Panel</span>
                </a>
                <div class="mt-3.5 inline-flex items-center gap-2 px-2.5 py-1 bg-emerald-50 border border-emerald-200/80 rounded-full text-[11px] font-medium text-emerald-800">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Admin Guard Active</span>
                </div>
            </div>

            <!-- Navigation Links -->
            @php
                $pendingProjectsBadge = \App\Models\Project::where('status', 'pending')->count();
                $pendingProductsBadge = \App\Models\Product::where('status', 'pending')->count();
                $newInquiriesBadge = \App\Models\ProductInquiry::where('status', 'new')->count();
            @endphp
            <nav class="px-3 pt-5 space-y-1 text-xs">
                <!-- 1. Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-amber-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </div>
                </a>

                <!-- 2. Project Management -->
                <div class="pt-3">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Architecture</span>
                    <a href="{{ route('admin.projects.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.projects.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.projects.*') ? 'text-emerald-400' : 'text-emerald-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>Projects</span>
                        </div>
                        @if($pendingProjectsBadge > 0)
                            <span class="bg-amber-100 text-amber-900 border border-amber-300 text-[10px] font-extrabold px-2 py-0.5 rounded-full" title="{{ $pendingProjectsBadge }} Pending Review">
                                {{ $pendingProjectsBadge }}
                            </span>
                        @endif
                    </a>

                    <!-- Project Categories Link -->
                    <a href="{{ route('admin.project-categories.index') }}"
                       class="flex items-center justify-between px-3.5 py-2 rounded-xl font-medium transition-all duration-150 mt-0.5 {{ request()->routeIs('admin.project-categories.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <div class="flex items-center gap-3 pl-1">
                            <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.project-categories.*') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <span>Project Categories</span>
                        </div>
                    </a>

                    <!-- Countries Link -->
                    <a href="{{ route('admin.countries.index') }}"
                       class="flex items-center justify-between px-3.5 py-2 rounded-xl font-medium transition-all duration-150 mt-0.5 {{ request()->routeIs('admin.countries.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <div class="flex items-center gap-3 pl-1">
                            <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.countries.*') ? 'text-blue-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Countries</span>
                        </div>
                    </a>
                </div>

                <!-- 3. Product Management -->
                <div class="pt-2">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Catalog & Materials</span>
                    <a href="{{ route('admin.products.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.products.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.products.*') ? 'text-blue-400' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span>Products</span>
                        </div>
                        @if($pendingProductsBadge > 0)
                            <span class="bg-blue-100 text-blue-900 border border-blue-200 text-[10px] font-extrabold px-2 py-0.5 rounded-full" title="{{ $pendingProductsBadge }} Pending Review">
                                {{ $pendingProductsBadge }}
                            </span>
                        @endif
                    </a>

                    <!-- Product Categories Link -->
                    <a href="{{ route('admin.product-categories.index') }}"
                       class="flex items-center justify-between px-3.5 py-2 rounded-xl font-medium transition-all duration-150 mt-0.5 {{ request()->routeIs('admin.product-categories.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <div class="flex items-center gap-3 pl-1">
                            <svg class="w-3.5 h-3.5 {{ request()->routeIs('admin.product-categories.*') ? 'text-blue-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span>Product Categories</span>
                        </div>
                    </a>
                </div>

                <!-- 4. Author Management -->
                <div class="pt-2">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Community</span>
                    <a href="{{ route('admin.authors.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.authors.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.authors.*') ? 'text-purple-400' : 'text-purple-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Author Management</span>
                        </div>
                    </a>
                </div>

                <!-- 5. Page Create / CMS Pages -->
                <div class="pt-2">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Content CMS</span>
                    <a href="{{ route('admin.pages.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.pages.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.pages.*') ? 'text-amber-400' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Page Create & Pages</span>
                        </div>
                    </a>
                </div>

                <!-- 6. Editorial CMS: Articles & Architecture News (Separated) -->
                <div class="pt-3">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Editorial & Journalism</span>
                    
                    <div class="space-y-1">
                        <!-- Articles -->
                        <div class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.articles.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                            <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 flex-1">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.articles.*') ? 'text-rose-400' : 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                <span>Articles</span>
                            </a>
                            <a href="{{ route('admin.articles.create') }}" title="Write New Article" class="p-1 rounded-lg transition-colors {{ request()->routeIs('admin.articles.*') ? 'hover:bg-slate-800 text-rose-300' : 'hover:bg-slate-200 text-slate-500' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </a>
                        </div>

                        <!-- Architecture News -->
                        <div class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.news.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                            <a href="{{ route('admin.news.index') }}" class="flex items-center gap-3 flex-1">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.news.*') ? 'text-blue-400' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Architecture News</span>
                            </a>
                            <a href="{{ route('admin.news.create') }}" title="Post Architecture News" class="p-1 rounded-lg transition-colors {{ request()->routeIs('admin.news.*') ? 'hover:bg-slate-800 text-blue-300' : 'hover:bg-slate-200 text-slate-500' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 7. Inquiries -->
                <div class="pt-2">
                    <a href="{{ route('admin.inquiries.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.inquiries.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.inquiries.*') ? 'text-teal-400' : 'text-teal-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>Product Inquiries</span>
                        </div>
                        @if($newInquiriesBadge > 0)
                            <span class="bg-teal-100 text-teal-900 border border-teal-200 text-[10px] font-extrabold px-2 py-0.5 rounded-full">
                                {{ $newInquiriesBadge }}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- 8. Settings -->
                <div class="pt-2">
                    <a href="{{ route('admin.settings.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.settings.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.*') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Settings & Status</span>
                        </div>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Sidebar Footer / Admin Profile & Logout -->
        <div class="px-6 pt-5 border-t border-slate-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-900 block leading-tight">{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</span>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Super Admin</span>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs text-rose-600 hover:text-rose-700 font-semibold p-1.5 hover:bg-rose-50 rounded-lg transition-colors" title="Sign Out">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('home') }}" target="_blank" class="text-[11px] font-medium text-slate-500 hover:text-slate-900 flex items-center gap-1.5 transition-colors">
                    <span>Open Public Magazine</span>
                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Admin Workspace (Crisp Off-White Canvas) -->
    <main class="flex-grow admin-content p-4 sm:p-8 min-h-screen overflow-x-hidden">
        <!-- Toast Alerts in Modern Light Tone -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-900 px-5 py-3.5 rounded-xl text-xs font-medium flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-base font-bold">&times;</button>
            </div>
        @endif
        @if(session('warning'))
            <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-900 px-5 py-3.5 rounded-xl text-xs font-medium flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold text-xs">!</span>
                    <span>{{ session('warning') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-amber-700 hover:text-amber-900 text-base font-bold">&times;</button>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-900 px-5 py-3.5 rounded-xl text-xs font-medium flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center font-bold text-xs">✕</span>
                    <span>{{ $errors->first() }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 text-base font-bold">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
