<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteLogo = \App\Models\Setting::logoUrl();
        $siteLogoText = \App\Models\Setting::logoText();
        $siteFavicon = \App\Models\Setting::faviconUrl();
        $siteTitle = \App\Models\Setting::siteTitle();
        if (!isset($navCountries) || empty($navCountries) || (is_object($navCountries) && $navCountries->isEmpty())) {
            $navCountries = \App\Models\Country::active()->orderBy('order')->orderBy('name')->get();
        }
    @endphp

    <title>@yield('title', $siteTitle)</title>
    @if($siteFavicon)
        <link rel="icon" type="image/x-icon" href="{{ $siteFavicon }}">
        <link rel="shortcut icon" href="{{ $siteFavicon }}">
        <link rel="apple-touch-icon" href="{{ $siteFavicon }}">
    @endif
    <meta name="description" content="@yield('meta_description', 'International architectural and interior showcase, featuring curated projects, building products, and editorial narratives across Asia and the world.')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind & App Assets -->
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                        cinzel: ['"Cinzel"', 'serif'],
                    },
                    colors: {
                        arch: {
                            blue: '#0d47a1',
                            dark: '#111827',
                            slate: '#1e293b',
                            gold: '#c29d59',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            color: #1a1a1a;
            -webkit-font-smoothing: antialiased;
        }

        .nook-logo-text {
            font-family: 'Cinzel', serif;
            letter-spacing: -0.04em;
        }

        /* Smooth scroll transitions for header */
        #scrolled-sticky-header {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease;
        }

        .badge-editorial {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 0, 0, 0.08);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.04em;
        }

        .editorial-image-hover {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .group:hover .editorial-image-hover {
            transform: scale(1.03);
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-black selection:text-white">

    <!-- Global Toast Notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2 max-w-md w-full px-4">
        @if(session('success'))
            <div class="bg-black text-white px-5 py-3.5 rounded shadow-xl border border-zinc-700 flex items-center justify-between text-sm">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-zinc-400 hover:text-white ml-3 text-lg">&times;</button>
            </div>
        @endif
        @if(session('warning'))
            <div class="bg-amber-900 text-amber-100 px-5 py-3.5 rounded shadow-xl border border-amber-700 flex items-center justify-between text-sm">
                <span>{{ session('warning') }}</span>
                <button onclick="this.parentElement.remove()" class="text-amber-300 hover:text-white ml-3 text-lg">&times;</button>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-950 text-red-100 px-5 py-3.5 rounded shadow-xl border border-red-800 flex items-center justify-between text-sm">
                <span>{{ $errors->first() }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-300 hover:text-white ml-3 text-lg">&times;</button>
            </div>
        @endif
    </div>

    <!-- ================= STATIC HERO HEADER (IMAGE 1 DESIGN WHEN NOT SCROLLED) ================= -->
    @if(request()->routeIs('home'))
        <header id="static-hero-header" class="bg-white border-b border-zinc-200">
            <!-- Row 1: Top Navigation Bar -->
            <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
                <div>
                    <a href="{{ route('author.projects.create') }}" class="text-xs sm:text-sm font-medium text-zinc-800 hover:text-black transition-colors">
                        Submit Your Project
                    </a>
                </div>

                <nav class="hidden md:flex items-center space-x-8 text-xs sm:text-sm font-medium text-zinc-800">
                    <a href="{{ route('projects.index') }}" class="hover:text-black transition-colors">Projects</a>
                    <a href="{{ route('articles.index') }}" class="hover:text-black transition-colors">Articles</a>
                    <a href="{{ route('products.index') }}" class="hover:text-black transition-colors">Products</a>
                    <a href="{{ route('news.index') }}" class="hover:text-black transition-colors">News</a>
                </nav>

                <div class="flex items-center space-x-3 text-xs sm:text-sm">
                    @auth('admin')
                        <a href="{{ route('admin.dashboard') }}" class="font-bold text-zinc-900 hover:underline">Admin Panel</a>
                    @elseauth('author')
                        @php
                            $headerAuthor = Auth::guard('author')->user();
                        @endphp
                        <div class="relative group">
                            <a href="{{ route('author.dashboard') }}" class="flex items-center gap-2.5 text-left hover:opacity-90 transition-opacity">
                                <img src="{{ $headerAuthor->avatar }}"
                                     alt="{{ $headerAuthor->name }}"
                                     class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover border border-zinc-200 shadow-2xs">
                                <div class="flex flex-col justify-center leading-tight">
                                    <span class="text-xs sm:text-[13px] font-bold text-zinc-900 group-hover:text-black">
                                        {{ $headerAuthor->name }}
                                    </span>
                                    <span class="text-[10px] sm:text-[11px] font-medium text-sky-700">
                                        {{ $headerAuthor->company ?: ($headerAuthor->title ?: 'Author') }}
                                    </span>
                                </div>
                            </a>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-zinc-200 py-2 hidden group-hover:block z-50 text-xs">
                                <div class="px-4 py-2 border-b border-zinc-100">
                                    <p class="font-bold text-zinc-900 truncate">{{ $headerAuthor->name }}</p>
                                    <p class="text-[10px] text-zinc-400 truncate">{{ $headerAuthor->email }}</p>
                                </div>
                                <a href="{{ route('author.dashboard') }}" class="block px-4 py-2 hover:bg-zinc-50 font-medium">Dashboard</a>
                                <a href="{{ route('author.projects.create') }}" class="block px-4 py-2 hover:bg-zinc-50 text-emerald-700 font-medium">+ Submit Project</a>
                                <a href="{{ route('author.products.create') }}" class="block px-4 py-2 hover:bg-zinc-50 text-blue-700 font-medium">+ Submit Product</a>
                                <div class="border-t border-zinc-100 my-1"></div>
                                <form action="{{ route('author.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 font-medium">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('author.login') }}" class="text-zinc-800 hover:text-black font-medium transition-colors">Log in</a>
                        <a href="{{ route('author.register') }}" class="bg-black hover:bg-zinc-800 text-white px-2.5 py-1 font-bold text-xs transition-colors">Sign Up</a>
                    @endauth
                </div>
            </div>

            <!-- Row 2: Large Central Logo Section -->
            <div class="text-center bg-white border-t border-zinc-100">
                <a href="{{ route('home') }}" class="inline-block group">
                    @if($siteLogo)
                        <img src="{{ $siteLogo }}" alt="{{ $siteLogoText }}" class="h-16 md:h-20 max-w-[280px] mx-auto object-contain">
                    @else
                        <div class="flex items-center justify-center">
                            <span class="text-6xl md:text-7xl font-black tracking-tight text-black nook-logo-text lowercase" style="font-family: 'Cinzel', serif;">{{ $siteLogoText }}</span>
                        </div>
                    @endif

                </a>
            </div>

            <!-- Row 3: Country Navigation Strip (Starts from left, extends to right, hidden if no space) -->
            <div class="border-t border-zinc-200 bg-white overflow-hidden">
                <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-start py-3.5 text-xs md:text-sm text-zinc-700 font-normal overflow-hidden whitespace-nowrap gap-6 md:gap-8">
                        @foreach($navCountries as $countryItem)
                            @php $cName = is_object($countryItem) ? $countryItem->name : $countryItem; @endphp
                            <a href="{{ route('home', ['country' => $cName]) }}"
                               class="shrink-0 hover:text-black transition-colors {{ request('country') === $cName ? 'font-bold text-black border-b-2 border-black pb-0.5' : '' }}">
                                {{ $cName }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </header>
    @endif

    <!-- ================= SCROLLED STICKY HEADER (ARCHDAILY DESIGN WHEN SCROLLED) ================= -->
    <header id="scrolled-sticky-header"
            class="{{ request()->routeIs('home')
                ? 'fixed top-0 left-0 right-0 z-50 bg-white border-b border-zinc-200 shadow-md transition-all duration-300 ease-out transform -translate-y-full opacity-0 pointer-events-none'
                : 'sticky top-0 z-40 bg-white border-b border-zinc-200 shadow-xs translate-y-0 opacity-100 pointer-events-auto' }}">

        <!-- Primary Navigation & Search Bar (Matches ArchDaily Screenshot 1:1) -->
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 md:h-16 gap-3 sm:gap-6">

                <!-- Left: Dynamic Logo -->
                <div id="side-brand-logo" class="flex-shrink-0 flex items-center gap-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        @if($siteLogo)
                            <img src="{{ $siteLogo }}" alt="{{ $siteLogoText }}" class="h-8 md:h-9 max-w-[150px] object-contain">
                        @else
                            <div class="flex flex-col">
                                <span class="text-2xl md:text-3xl font-black tracking-tight text-slate-900 leading-none group-hover:text-amber-700 transition-colors lowercase" style="font-family: 'Cinzel', serif;">{{ $siteLogoText }}</span>
                                <span class="text-[8px] font-bold tracking-[0.25em] text-slate-500 uppercase leading-none mt-0.5">MAGAZINE</span>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- Center: ArchDaily Style Search Input & Button (User prompt requirement) -->
                <div class="flex-grow max-w-xl">
                    <form action="{{ route('search') }}" method="GET" class="relative flex items-center">
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 pl-3.5 hidden sm:flex items-center pointer-events-none text-zinc-400">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text"
                                   name="q"
                                   value="{{ request('q') }}"
                                   placeholder="Search..."
                                   class="w-full pl-3 sm:pl-10 pr-9 sm:pr-20 py-1.5 sm:py-2 text-xs md:text-sm bg-zinc-100 hover:bg-zinc-100/80 focus:bg-white text-zinc-900 rounded-md border border-transparent focus:border-zinc-300 focus:outline-none transition-all placeholder:text-zinc-500 font-normal">
                            <button type="submit"
                                    class="absolute inset-y-1 right-1 px-2.5 sm:px-3 bg-zinc-800 hover:bg-black text-white text-[10px] sm:text-[11px] font-bold rounded flex items-center justify-center gap-1.5 transition-colors"
                                    title="Search">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                <span class="hidden sm:inline">Search</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Navigation Links (Matching Screenshot) -->
                <div class="flex items-center space-x-2 sm:space-x-5 text-xs font-semibold text-zinc-700">
                    <nav class="hidden lg:flex items-center space-x-6">
                        <a href="{{ route('projects.index') }}" class="hover:text-black transition-colors {{ request()->routeIs('projects.*') ? 'text-black font-bold' : '' }}">Projects</a>
                        <a href="{{ route('articles.index') }}" class="hover:text-black transition-colors {{ request()->routeIs('articles.*') ? 'text-black font-bold' : '' }}">Articles</a>
                        <a href="{{ route('products.index') }}" class="hover:text-black transition-colors {{ request()->routeIs('products.*') ? 'text-black font-bold' : '' }}">Products</a>
                        <a href="{{ route('news.index') }}" class="hover:text-black transition-colors {{ request()->routeIs('news.*') ? 'text-black font-bold' : '' }}">News</a>
                    </nav>

                    <div class="h-4 w-px bg-zinc-300 hidden sm:block"></div>

                    <!-- Submit / Subscribe Button -->
                    @auth('author')
                        <a href="{{ route('author.projects.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-1.5 rounded text-xs font-bold transition-all shadow-2xs whitespace-nowrap hidden sm:inline-block">
                            + Submit Your Project
                        </a>
                    @else
                        <a href="{{ route('author.register') }}" class="bg-[#0d47a1] hover:bg-blue-900 text-white px-3.5 py-1.5 rounded text-xs font-bold transition-all shadow-2xs whitespace-nowrap hidden sm:inline-block">
                            Subscribe
                        </a>
                    @endauth

                    <!-- Dual Guard Auth Button / Avatar -->
                    @auth('admin')
                        <a href="{{ route('admin.dashboard') }}" class="w-8 h-8 rounded-full bg-slate-900 text-amber-400 flex items-center justify-center font-bold text-xs shadow-xs" title="Admin Panel">
                            A
                        </a>
                    @elseauth('author')
                        @php
                            $stickyAuthor = Auth::guard('author')->user();
                        @endphp
                        <div class="relative group">
                            <a href="{{ route('author.dashboard') }}" class="flex items-center gap-2 text-left hover:opacity-90 transition-opacity">
                                <img src="{{ $stickyAuthor->avatar }}" alt="{{ $stickyAuthor->name }}" class="w-8 h-8 rounded-full object-cover border border-zinc-300 shadow-2xs">
                                <div class="hidden sm:flex flex-col justify-center leading-tight">
                                    <span class="text-xs font-bold text-zinc-900 truncate max-w-[130px] leading-tight">{{ $stickyAuthor->name }}</span>
                                    <span class="text-[10px] font-medium text-sky-700 truncate max-w-[130px] leading-tight">{{ $stickyAuthor->company ?: ($stickyAuthor->title ?: 'Author') }}</span>
                                </div>
                            </a>
                            <div class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-xl border border-zinc-200 py-2 hidden group-hover:block z-50 text-xs">
                                <div class="px-4 py-2 border-b border-zinc-100">
                                    <p class="font-bold text-zinc-900 truncate">{{ Auth::guard('author')->user()->name }}</p>
                                    <p class="text-[10px] text-zinc-400">{{ Auth::guard('author')->user()->email }}</p>
                                </div>
                                <a href="{{ route('author.dashboard') }}" class="block px-4 py-2 hover:bg-zinc-50">Author Studio</a>
                                <a href="{{ route('author.projects.create') }}" class="block px-4 py-2 hover:bg-zinc-50 text-emerald-700">+ Submit Project</a>
                                <a href="{{ route('author.products.create') }}" class="block px-4 py-2 hover:bg-zinc-50 text-blue-700">+ Submit Product</a>
                                <div class="border-t border-zinc-100 my-1"></div>
                                <form action="{{ route('author.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('author.login') }}" class="w-8 h-8 rounded-full bg-[#5b21b6] text-white flex items-center justify-center font-bold text-xs shadow-xs hover:bg-purple-900 transition-colors" title="Sign In">
                            W
                        </a>
                    @endauth

                    <!-- Mobile Menu Hamburger Button -->
                    <button type="button" onclick="document.getElementById('mobile-drawer').classList.toggle('hidden')" class="p-2 text-zinc-600 hover:text-black lg:hidden" aria-label="Toggle Navigation Menu">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Regional Edition Sub-Navigation -->
        <div class="border-t border-zinc-200 bg-white overflow-x-auto scrollbar-none">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-4 md:space-x-6 py-2.5 text-xs text-zinc-600 font-medium whitespace-nowrap">
                    <!-- Global Edition pill -->
                    <a href="{{ route('home') }}" class="bg-zinc-100 hover:bg-zinc-200 text-zinc-900 px-3 py-1 rounded font-bold text-[11px] transition-colors {{ !request('country') ? 'bg-zinc-200 text-black' : '' }}">
                        Global Edition
                    </a>

                    <span class="text-zinc-300 font-light">|</span>

                    @foreach($navCountries as $cItem)
                        @php $cName = is_object($cItem) ? $cItem->name : $cItem; @endphp
                        <a href="{{ route('home', ['country' => $cName]) }}" class="hover:text-black transition-colors {{ request('country') === $cName ? 'text-black font-bold border-b border-black pb-0.5' : '' }}">{{ $cName }}</a>
                    @endforeach

                    @if(request('country'))
                        <a href="{{ route('home') }}" class="text-rose-600 font-bold hover:underline text-[11px] ml-2">
                            Reset &times;
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div id="mobile-drawer" class="hidden lg:hidden border-t border-zinc-200 bg-white p-4 space-y-3 text-xs font-semibold">
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('projects.index') }}" class="p-2.5 bg-zinc-50 rounded-lg hover:bg-zinc-100">Projects</a>
                <a href="{{ route('products.index') }}" class="p-2.5 bg-zinc-50 rounded-lg hover:bg-zinc-100">Products & Catalog</a>
                <a href="{{ route('articles.index') }}" class="p-2.5 bg-zinc-50 rounded-lg hover:bg-zinc-100">Articles</a>
                <a href="{{ route('news.index') }}" class="p-2.5 bg-zinc-50 rounded-lg hover:bg-zinc-100">News</a>
            </div>
            <div class="pt-2 border-t border-zinc-100 flex items-center justify-between">
                <a href="{{ route('author.projects.create') }}" class="text-emerald-700 font-bold">Submit Your Project</a>
                @auth('author')
                    @php
                        $mobileAuthor = Auth::guard('author')->user();
                    @endphp
                    <a href="{{ route('author.dashboard') }}" class="flex items-center gap-2">
                        <img src="{{ $mobileAuthor->avatar }}" alt="{{ $mobileAuthor->name }}" class="w-7 h-7 rounded-full object-cover border border-zinc-200">
                        <div class="flex flex-col text-left leading-tight">
                            <span class="text-xs font-bold text-zinc-900">{{ $mobileAuthor->name }}</span>
                            <span class="text-[10px] font-medium text-sky-700">{{ $mobileAuthor->company ?: 'Author' }}</span>
                        </div>
                    </a>
                @else
                    <a href="{{ route('author.login') }}" class="text-zinc-800 font-bold">Architect Log In</a>
                    <a href="{{ route('author.register') }}" class="bg-black text-white px-3 py-1.5 rounded font-bold">Sign Up</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- ================= FOOTER (MATCHING USER SCREENSHOT EXACTLY) ================= -->
    <!-- "footer a oi imaeg ar footer ar moto hobe,," -->
    <footer class="bg-white border-t border-zinc-200 mt-20 pt-16 pb-12">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- ================= OUR PARTNERS SECTION (Dynamic & Admin Controlled) ================= -->
            @php
                $partnersList = \App\Models\Partner::active()->get();
            @endphp

            @if($partnersList->isNotEmpty())
            <div id="partners" class="text-center mb-14">
                <span class="text-[11px] font-extrabold uppercase tracking-[0.25em] text-zinc-500 block mb-8">
                    OUR PARTNERS
                </span>

                @if($partnersList->count() <= 6)
                    <!-- Partner Logos Strip (Exact layout from user screenshot for <= 6 items) -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-8 sm:gap-10 items-center justify-items-center opacity-85 hover:opacity-100 transition-opacity">
                        @foreach($partnersList as $partner)
                            @if($partner->url)
                                <a href="{{ $partner->url }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="group flex items-center justify-center p-2 rounded-lg transition-transform hover:scale-105"
                                   title="{{ $partner->name }} (Opens in new window)">
                                    <img src="{{ $partner->logo_url }}"
                                         alt="{{ $partner->name }}"
                                         class="h-8 sm:h-9 max-w-[160px] object-contain transition-opacity duration-200 group-hover:opacity-100">
                                </a>
                            @else
                                <div class="flex items-center justify-center p-2" title="{{ $partner->name }}">
                                    <img src="{{ $partner->logo_url }}"
                                         alt="{{ $partner->name }}"
                                         class="h-8 sm:h-9 max-w-[160px] object-contain">
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <!-- Sliding Horizontal Marquee when partners > 6 (Bese hole side a slide hobe) -->
                    <div class="relative overflow-hidden w-full partner-marquee-container group py-2">
                        <!-- Fade Gradient Edges for Magazine Aesthetic -->
                        <div class="absolute left-0 inset-y-0 w-12 sm:w-24 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
                        <div class="absolute right-0 inset-y-0 w-12 sm:w-24 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>

                        <!-- Infinite Sliding Track -->
                        <div class="partner-marquee-track flex items-center gap-12 sm:gap-16">
                            <!-- Loop 1 -->
                            @foreach($partnersList as $partner)
                                @if($partner->url)
                                    <a href="{{ $partner->url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="shrink-0 flex items-center justify-center p-2 group transition-transform hover:scale-110"
                                       title="{{ $partner->name }} (Opens in new window)">
                                        <img src="{{ $partner->logo_url }}"
                                             alt="{{ $partner->name }}"
                                             class="h-8 sm:h-9 max-w-[160px] object-contain opacity-85 group-hover:opacity-100 transition-all duration-200">
                                    </a>
                                @else
                                    <div class="shrink-0 flex items-center justify-center p-2" title="{{ $partner->name }}">
                                        <img src="{{ $partner->logo_url }}"
                                             alt="{{ $partner->name }}"
                                             class="h-8 sm:h-9 max-w-[160px] object-contain opacity-85">
                                    </div>
                                @endif
                            @endforeach

                            <!-- Loop 2 (For seamless continuous marquee) -->
                            @foreach($partnersList as $partner)
                                @if($partner->url)
                                    <a href="{{ $partner->url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="shrink-0 flex items-center justify-center p-2 group transition-transform hover:scale-110"
                                       title="{{ $partner->name }} (Opens in new window)">
                                        <img src="{{ $partner->logo_url }}"
                                             alt="{{ $partner->name }}"
                                             class="h-8 sm:h-9 max-w-[160px] object-contain opacity-85 group-hover:opacity-100 transition-all duration-200">
                                    </a>
                                @else
                                    <div class="shrink-0 flex items-center justify-center p-2" title="{{ $partner->name }}">
                                        <img src="{{ $partner->logo_url }}"
                                             alt="{{ $partner->name }}"
                                             class="h-8 sm:h-9 max-w-[160px] object-contain opacity-85">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <style>
                @keyframes partnerSlideMarquee {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(-50%); }
                }
                .partner-marquee-track {
                    display: flex;
                    width: max-content;
                    animation: partnerSlideMarquee 30s linear infinite;
                    will-change: transform;
                }
                .partner-marquee-container:hover .partner-marquee-track {
                    animation-play-state: paused;
                }
            </style>
            @endif

            <!-- Navigation Links Row & Social Icons Row (Matching Screenshot) -->
            <div class="border-t border-zinc-200 pt-8 pb-8 flex flex-col md:flex-row items-center justify-between gap-6 text-xs text-zinc-600">
                <!-- Dynamic Footer CMS Pages (Only shows pages created in Admin Panel) -->
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-x-5 gap-y-2 font-medium">
                    @php
                        $activePages = isset($footerPages) && count($footerPages) > 0
                            ? $footerPages
                            : \App\Models\Page::published()->orderBy('order')->orderBy('id')->get();
                    @endphp
                    @foreach($activePages as $footerPage)
                        <a href="{{ route('page.show', $footerPage->slug) }}" class="hover:text-black transition-colors">
                            {{ $footerPage->title }}
                        </a>
                    @endforeach
                    <a href="{{ route('articles.index') }}" class="hover:text-black transition-colors">RSS</a>
                </div>

                <!-- Dynamic Social Icons (Configured and activated in Admin Panel) -->
                @php
                    $activeSocials = isset($footerSocialLinks) ? $footerSocialLinks : \App\Models\Setting::getActiveSocialLinks();
                @endphp
                @if(!empty($activeSocials))
                    <div class="flex items-center space-x-5 text-zinc-700">
                        @foreach($activeSocials as $socKey => $socItem)
                            <a href="{{ $socItem['url'] }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="hover:text-black transition-colors"
                               title="{{ $socItem['name'] }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    {!! $socItem['svg'] !!}
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Bottom Row: Logo & Copyright Statement (Exact Screenshot) -->
            <div class="pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-[11px] text-zinc-500">
                <div class="flex items-center gap-3">
                    @if($siteLogo)
                        <img src="{{ $siteLogo }}" alt="{{ $siteLogoText }}" class="h-6 max-w-[120px] object-contain">
                    @else
                        <span class="text-xl font-black text-zinc-900 tracking-tight nook-logo-text lowercase" style="font-family: 'Cinzel', serif;">{{ $siteLogoText }}</span>
                    @endif
                    <span class="text-zinc-300">|</span>
                    <p class="leading-relaxed">
                        &copy; All rights reserved. {{ $siteLogoText }}, part of DAaily platforms AG 2008-{{ date('Y') }} &bull; ISSN 0719-8884 &bull; All images are &copy; each office/photographer mentioned.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-zinc-500">Developed by</span>
                    <a href="https://sarkarit.com/" class="font-medium hover:text-zinc-900 transition-colors">Sarkar IT</a>
                </div>

            </div>

        </div>
    </footer>

    <!-- Scroll Transition Script for Header -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stickyHeader = document.getElementById('scrolled-sticky-header');
            const isHomePage = {{ request()->routeIs('home') ? 'true' : 'false' }};

            if (stickyHeader && isHomePage) {
                function checkScroll() {
                    if (window.scrollY > 100) {
                        stickyHeader.classList.remove('-translate-y-full', 'opacity-0', 'pointer-events-none');
                        stickyHeader.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
                    } else {
                        stickyHeader.classList.add('-translate-y-full', 'opacity-0', 'pointer-events-none');
                        stickyHeader.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
                    }
                }

                window.addEventListener('scroll', checkScroll, { passive: true });
                checkScroll();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
