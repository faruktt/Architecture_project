@php
    $siteLogo = \App\Models\Setting::logoUrl();
    $siteLogoText = \App\Models\Setting::logoText();
    $siteFavicon = \App\Models\Setting::faviconUrl();
    $siteTitle = \App\Models\Setting::siteTitle();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Author Workspace | ' . $siteTitle)</title>
    @if($siteFavicon)
        <link rel="icon" type="image/x-icon" href="{{ $siteFavicon }}">
        <link rel="shortcut icon" href="{{ $siteFavicon }}">
        <link rel="apple-touch-icon" href="{{ $siteFavicon }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f7f7f8; }
        .site-cinzel-logo { font-family: 'Cinzel', serif; letter-spacing: -0.04em; }
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between text-zinc-800">

    <!-- Top Navigation -->
    <header class="bg-white border-b border-zinc-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left: Admin-Configured Logo & Portal Label -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <a href="{{ route('author.dashboard') }}" class="font-black text-lg sm:text-xl tracking-tight text-black flex items-center gap-2">
                        @if($siteLogo)
                            <img src="{{ $siteLogo }}" alt="{{ $siteLogoText }}" class="h-7 sm:h-8 max-w-[110px] sm:max-w-[140px] object-contain">
                        @else
                            <span class="text-xl sm:text-2xl font-black tracking-tight text-black site-cinzel-logo lowercase">{{ $siteLogoText }}</span>
                        @endif
                        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest bg-zinc-900 text-white px-1.5 sm:px-2 py-0.5 rounded whitespace-nowrap">Studio</span>
                    </a>
                    <span class="text-zinc-300 hidden md:inline">|</span>
                    <a href="{{ route('home') }}" class="text-xs text-zinc-500 hover:text-black hidden md:flex items-center gap-1 transition-colors">
                        &larr; View Magazine Frontend
                    </a>
                </div>

                <!-- Right Desktop: Author Profile, New Project & Logout -->
                <div class="hidden md:flex items-center space-x-4 text-xs font-medium">
                    @auth('author')
                        <a href="{{ route('author.projects.create') }}" class="bg-black text-white hover:bg-zinc-800 px-3.5 py-2 rounded font-semibold transition-colors flex items-center gap-1.5 shadow-sm">
                            <span class="text-emerald-400 font-bold">+</span> Submit Your Project
                        </a>

                        <a href="{{ route('author.profile.edit') }}" title="Click to Edit Profile" class="flex items-center gap-2 border-l border-zinc-200 pl-4 group hover:opacity-90 transition-opacity">
                            <img src="{{ Auth::guard('author')->user()->avatar }}" class="w-8 h-8 rounded-full object-cover border border-zinc-300 group-hover:border-zinc-600 transition-colors">
                            <div class="text-left">
                                <span class="font-bold text-zinc-900 block leading-tight group-hover:underline flex items-center gap-1">
                                    {{ Auth::guard('author')->user()->name }}
                                    <i class="fa-solid fa-pen text-[10px] text-zinc-400 group-hover:text-zinc-700"></i>
                                </span>
                                <span class="text-[10px] text-zinc-500">{{ Auth::guard('author')->user()->company ?? 'Architect' }}</span>
                            </div>
                        </a>

                        <form action="{{ route('author.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-zinc-500 hover:text-red-600 transition-colors p-1 cursor-pointer" title="Sign Out">
                                <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            </button>
                        </form>
                    @endauth
                </div>

                <!-- Right Mobile: Quick Actions + Hamburger Toggle -->
                <div class="flex md:hidden items-center gap-2">
                    @auth('author')
                        <a href="{{ route('author.profile.edit') }}" class="p-1 rounded-full border border-zinc-200" title="Edit Profile">
                            <img src="{{ Auth::guard('author')->user()->avatar }}" class="w-7 h-7 rounded-full object-cover">
                        </a>
                        <button type="button"
                                onclick="document.getElementById('author-mobile-menu').classList.toggle('hidden')"
                                class="p-2 text-zinc-700 hover:text-black hover:bg-zinc-100 rounded-lg transition-colors focus:outline-none cursor-pointer"
                                aria-label="Toggle Navigation Menu">
                            <i class="fa-solid fa-bars text-lg"></i>
                        </button>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu (Toggled on Small Screens) -->
        @auth('author')
            <div id="author-mobile-menu" class="hidden md:hidden border-t border-zinc-200 bg-white px-4 py-4 space-y-4 shadow-lg">
                <!-- Author Info -->
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                    <div class="flex items-center gap-3">
                        <img src="{{ Auth::guard('author')->user()->avatar }}" class="w-10 h-10 rounded-full object-cover border border-zinc-300">
                        <div>
                            <div class="font-bold text-sm text-zinc-900 leading-tight">{{ Auth::guard('author')->user()->name }}</div>
                            <div class="text-xs text-zinc-500">{{ Auth::guard('author')->user()->company ?? 'Architect' }}</div>
                        </div>
                    </div>
                    <a href="{{ route('author.profile.edit') }}" class="text-xs font-semibold px-2.5 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 rounded transition-colors">
                        Edit
                    </a>
                </div>

                <!-- Quick Action Buttons -->
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('author.projects.create') }}" class="px-3 py-2 bg-black text-white hover:bg-zinc-800 text-xs font-bold rounded-lg text-center flex items-center justify-center gap-1 shadow-sm">
                        <span class="text-emerald-400 font-bold">+</span> Project
                    </a>
                    <a href="{{ route('author.products.create') }}" class="px-3 py-2 bg-blue-900 text-white hover:bg-blue-800 text-xs font-bold rounded-lg text-center flex items-center justify-center gap-1 shadow-sm">
                        <span class="text-blue-300 font-bold">+</span> Product
                    </a>
                </div>

                <!-- Navigation Links List -->
                <div class="space-y-1 text-xs font-semibold text-zinc-700">
                    <a href="{{ route('author.dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-zinc-100 {{ request()->routeIs('author.dashboard') ? 'bg-zinc-100 text-black font-bold' : '' }}">Dashboard</a>
                    <a href="{{ route('author.projects.index') }}" class="block px-3 py-2 rounded-lg hover:bg-zinc-100 {{ request()->routeIs('author.projects.*') ? 'bg-zinc-100 text-black font-bold' : '' }}">My Projects</a>
                    <a href="{{ route('author.products.index') }}" class="block px-3 py-2 rounded-lg hover:bg-zinc-100 {{ request()->routeIs('author.products.*') ? 'bg-zinc-100 text-black font-bold' : '' }}">My Products</a>
                    <a href="{{ route('author.profile.edit') }}" class="block px-3 py-2 rounded-lg hover:bg-zinc-100 {{ request()->routeIs('author.profile.edit') ? 'bg-zinc-100 text-black font-bold' : '' }}">Edit Profile</a>
                    <a href="{{ route('author.profile', Auth::guard('author')->user()->username) }}" target="_blank" class="block px-3 py-2 rounded-lg hover:bg-zinc-100 text-zinc-600">Public Profile &nearr;</a>
                    <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg hover:bg-zinc-100 text-zinc-500">&larr; View Magazine Frontend</a>
                </div>

                <!-- Logout -->
                <div class="pt-2 border-t border-zinc-100">
                    <form action="{{ route('author.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                            Logout of Author Studio
                        </button>
                    </form>
                </div>
            </div>
        @endauth

        <!-- Subnav menu for Author (Horizontal Touch Scroll with no scrollbars) -->
        @auth('author')
            <div class="bg-zinc-50 border-t border-zinc-200">
                <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
                    <nav class="flex items-center space-x-4 sm:space-x-6 text-xs font-semibold py-2.5 overflow-x-auto whitespace-nowrap scrollbar-none scroll-smooth">
                        <a href="{{ route('author.dashboard') }}" class="transition-colors shrink-0 {{ request()->routeIs('author.dashboard') ? 'text-black font-bold border-b-2 border-black pb-1' : 'text-zinc-600 hover:text-black' }}">Dashboard</a>
                        <a href="{{ route('author.projects.index') }}" class="transition-colors shrink-0 {{ request()->routeIs('author.projects.index') || request()->routeIs('author.projects.edit') ? 'text-black font-bold border-b-2 border-black pb-1' : 'text-zinc-600 hover:text-black' }}">My Projects</a>
                        <a href="{{ route('author.projects.create') }}" class="transition-colors shrink-0 {{ request()->routeIs('author.projects.create') ? 'text-emerald-700 font-bold border-b-2 border-emerald-700 pb-1' : 'text-emerald-700 hover:text-emerald-800' }}">+ Submit Project</a>
                        <a href="{{ route('author.products.index') }}" class="transition-colors shrink-0 {{ request()->routeIs('author.products.index') || request()->routeIs('author.products.edit') ? 'text-black font-bold border-b-2 border-black pb-1' : 'text-zinc-600 hover:text-black' }}">My Products</a>
                        <a href="{{ route('author.products.create') }}" class="transition-colors shrink-0 {{ request()->routeIs('author.products.create') ? 'text-blue-700 font-bold border-b-2 border-blue-700 pb-1' : 'text-blue-700 hover:text-blue-800' }}">+ Submit Product</a>
                        <a href="{{ route('author.profile.edit') }}" class="transition-colors shrink-0 {{ request()->routeIs('author.profile.edit') ? 'text-black font-bold border-b-2 border-black pb-1' : 'text-zinc-600 hover:text-black' }} flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Profile
                        </a>
                        <a href="{{ route('author.profile', Auth::guard('author')->user()->username) }}" target="_blank" class="text-zinc-600 hover:text-black transition-colors flex items-center gap-1 sm:ml-auto shrink-0">
                            Public Profile &nearr;
                        </a>
                    </nav>
                </div>
            </div>
        @endauth
    </header>

    <!-- Global Flash Notification -->
    <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
        @if(session('success'))
            <div class="bg-black text-white px-5 py-3 rounded-lg shadow-md border border-zinc-700 text-xs font-medium flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-zinc-400 hover:text-white">&times;</button>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-950 text-red-100 px-5 py-3 rounded-lg shadow-md border border-red-800 text-xs font-medium flex items-center justify-between">
                <span>{{ $errors->first() }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-300 hover:text-white">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-zinc-200 py-6 text-center text-xs text-zinc-400">
        <p>{{ $siteLogoText }} MAGAZINE &bull; Author Guard Portal &bull; Architectural Submissions</p>
    </footer>

    @stack('scripts')
</body>
</html>
