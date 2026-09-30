<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $adminSiteTitle = \App\Models\Setting::siteTitle();
        $adminSiteFavicon = \App\Models\Setting::faviconUrl();
    @endphp

    <title>@yield('title', 'Admin | ' . $adminSiteTitle)</title>
    @if($adminSiteFavicon)
        <link rel="icon" type="image/x-icon" href="{{ $adminSiteFavicon }}">
        <link rel="shortcut icon" href="{{ $adminSiteFavicon }}">
        <link rel="apple-touch-icon" href="{{ $adminSiteFavicon }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Cinzel:wght@700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f4f7fa;
            color: #0f172a;
        }
        /* Adminty Dark Navy Theme for Sidebar */
        .admin-sidebar {
            background-color: #404e67 !important;
            border-right: 1px solid #333f54 !important;
            color: #a0b1cc;
        }
        .admin-sidebar-header {
            background-color: #353c48 !important;
            border-bottom: 1px solid #2f3640 !important;
        }
        .admin-sidebar-footer {
            background-color: #353c48 !important;
            border-top: 1px solid #2f3640 !important;
        }
        .admin-sidebar-nav-heading {
            color: #8392a7;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .admin-sidebar-link {
            color: #9cb0cc;
            transition: all 0.15s ease-in-out;
            border-left: 3px solid transparent;
        }
        .admin-sidebar-link:hover {
            color: #ffffff;
            background-color: #353f53;
        }
        .admin-sidebar-link.is-active {
            color: #fe5d70 !important;
            background-color: #353f53 !important;
            border-left: 3px solid #fe5d70 !important;
        }
        .admin-sidebar-link.is-active svg {
            color: #fe5d70 !important;
        }
        .admin-content {
            background-color: #f4f7fa;
        }
        /* Custom scrollbars for high-end feel */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #353c48; }
        ::-webkit-scrollbar-thumb { background: #526380; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #687d9f; }

        /* Sleek scrollbar for fixed sidebar nav */
        .admin-sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .admin-sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .admin-sidebar-scroll::-webkit-scrollbar-thumb { background: #526380; border-radius: 9999px; }
        .admin-sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #fe5d70; }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row antialiased selection:bg-[#fe5d70]/20 selection:text-[#fe5d70]">

    @php
        $adminSiteLogo = \App\Models\Setting::logoUrl();
        $adminSiteLogoText = \App\Models\Setting::logoText();
    @endphp

    <!-- Mobile Header with Hamburger Toggle and Admin Avatar Dropdown -->
    <div class="md:hidden bg-[#353c48] border-b border-[#2b323d] px-4 py-3 flex items-center justify-between sticky top-0 z-30 shadow-md">
        <div class="flex items-center gap-2.5">
            <button type="button"
                    onclick="document.getElementById('admin-sidebar').classList.toggle('hidden')"
                    class="p-2 text-slate-300 hover:text-white hover:bg-[#404e67] rounded-lg focus:outline-none transition-colors cursor-pointer"
                    aria-label="Toggle Navigation Sidebar">
                <i class="fa-solid fa-bars text-base"></i>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                @if($adminSiteLogo)
                    <img src="{{ $adminSiteLogo }}" alt="{{ $adminSiteLogoText }}" class="h-8 max-w-[140px] object-contain rounded">
                @else
                    <span class="font-bold text-white text-lg tracking-tight lowercase" style="font-family: 'Plus Jakarta Sans', sans-serif;">{{ $adminSiteLogoText }}</span>
                @endif
            </a>
            <span class="text-[9px] font-bold bg-[#fe5d70]/20 text-[#fe5d70] border border-[#fe5d70]/40 px-1.5 py-0.5 rounded uppercase tracking-wider">ADMIN</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" target="_blank" class="text-xs text-slate-300 hover:text-white font-medium px-2 py-1 rounded bg-[#404e67] border border-[#526380]/40 flex items-center gap-1">
                <span>Site</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
            </a>

            <!-- Mobile Admin Avatar Dropdown Trigger -->
            <div class="relative admin-dropdown-container">
                <button type="button"
                        onclick="toggleAdminDropdown('mobile-admin-dropdown-menu')"
                        class="p-0.5 rounded-full hover:ring-2 hover:ring-[#fe5d70] focus:outline-none cursor-pointer flex items-center transition-all"
                        title="Admin Profile Menu">
                    <img src="{{ Auth::guard('admin')->user()->avatar }}"
                         alt="{{ Auth::guard('admin')->user()->name ?? 'Admin' }}"
                         class="w-7 h-7 rounded-full object-cover border border-[#526380] shadow-2xs">
                </button>

                <div id="mobile-admin-dropdown-menu"
                     class="admin-dropdown-menu hidden absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50 transition-all duration-150 transform opacity-0 scale-95 origin-top-right">
                    <div class="px-4 py-2.5 border-b border-slate-100">
                        <div class="text-xs font-bold text-slate-900 truncate">{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</div>
                        <div class="text-[10px] text-slate-400 truncate">{{ Auth::guard('admin')->user()->email ?? 'admin@editorial.com' }}</div>
                        <span class="inline-block mt-1 text-[9px] font-extrabold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200 px-1.5 py-0.2 rounded">SUPER ADMIN</span>
                    </div>
                    <div class="py-1 text-xs">
                        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 font-medium">
                            <i class="fa-solid fa-user-pen text-[#fe5d70] w-4 text-center"></i>
                            <span>Edit Profile</span>
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 font-medium">
                            <i class="fa-solid fa-sliders text-[#01a9ac] w-4 text-center"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                    <div class="border-t border-slate-100 my-1"></div>
                    <form action="{{ route('admin.logout') }}" method="POST" class="p-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-1.5 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-semibold text-left cursor-pointer">
                            <i class="fa-solid fa-right-from-bracket text-rose-600 w-4 text-center"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= ADMIN SIDEBAR ================= -->
    <aside id="admin-sidebar" class="hidden md:flex flex-col w-72 md:w-64 admin-sidebar flex-shrink-0 h-screen sticky top-0 z-30 shadow-lg md:shadow-none">
        <!-- Top Brand Header: Fixed at top of sidebar -->
        <div class="px-5 py-4 admin-sidebar-header flex items-center flex-shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="block group">
                @if($adminSiteLogo)
                    <img src="{{ $adminSiteLogo }}" 
                         alt="{{ $adminSiteLogoText }}" 
                         class="h-9 sm:h-10 max-w-[190px] object-contain rounded-md transition-transform group-hover:scale-105">
                @else
                    <span class="text-2xl font-black tracking-tight text-white group-hover:text-[#fe5d70] transition-colors lowercase" style="font-family: 'Plus Jakarta Sans', sans-serif;">{{ $adminSiteLogoText }}</span>
                @endif
            </a>
        </div>

        <!-- Scrollable Navigation Area (Middle scrolls smoothly if long, footer remains fixed) -->
        <div class="flex-1 overflow-y-auto min-h-0 admin-sidebar-scroll">
            <!-- Navigation Links -->
            @php
                $pendingProjectsBadge = \App\Models\Project::where('status', 'pending')->count();
                $pendingProductsBadge = \App\Models\Product::where('status', 'pending')->count();
                $newInquiriesBadge = \App\Models\ProductInquiry::where('status', 'new')->count();
            @endphp
            <nav class="pt-2 pb-4 text-xs">
                <!-- SECTION 1: NAVIGATION -->
                <div class="px-5 pt-3 pb-1.5 admin-sidebar-nav-heading">
                    Navigation
                </div>

                <div class="space-y-0.5">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                       class="admin-sidebar-link flex items-center justify-between px-5 py-2.5 font-medium {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-gauge-high w-4 text-center text-xs"></i>
                            <span>Dashboard</span>
                        </div>
                        <span class="bg-[#01a9ac] text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">NEW</span>
                    </a>
                </div>

                <!-- SECTION 2: UI ELEMENT / ARCHITECTURE -->
                <div class="px-5 pt-4 pb-1.5 admin-sidebar-nav-heading">
                    UI Element
                </div>

                <div class="space-y-0.5">
                    <!-- Projects -->
                    <a href="{{ route('admin.projects.index') }}"
                       class="admin-sidebar-link flex items-center justify-between px-5 py-2.5 font-medium {{ request()->routeIs('admin.projects.*') ? 'is-active' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-cubes w-4 text-center text-xs"></i>
                            <span>Projects</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            @if($pendingProjectsBadge > 0)
                                <span class="bg-[#fe5d70] text-white text-[10px] font-extrabold px-1.5 py-0.2 rounded-full" title="{{ $pendingProjectsBadge }} Pending">
                                    {{ $pendingProjectsBadge }}
                                </span>
                            @endif
                            <i class="fa-solid fa-chevron-right text-[9px] text-[#71829e]"></i>
                        </div>
                    </a>

                    <!-- Project Categories Link -->
                    <a href="{{ route('admin.project-categories.index') }}"
                       class="admin-sidebar-link flex items-center justify-between px-5 py-2.5 font-medium {{ request()->routeIs('admin.project-categories.*') ? 'is-active' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-tags w-4 text-center text-xs"></i>
                            <span>Project Categories</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[9px] text-[#71829e]"></i>
                    </a>

                    <!-- Countries Link -->
                    <a href="{{ route('admin.countries.index') }}"
                       class="admin-sidebar-link flex items-center justify-between px-5 py-2.5 font-medium {{ request()->routeIs('admin.countries.*') ? 'is-active' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-earth-americas w-4 text-center text-xs"></i>
                            <span>Countries</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[9px] text-[#71829e]"></i>
                    </a>
                </div>

                <!-- SECTION 3: FORMS / CATALOG & MATERIALS -->
                <div class="px-5 pt-4 pb-1.5 admin-sidebar-nav-heading">
                    Forms
                </div>

                <div class="space-y-0.5">
                    <!-- Products -->
                    <a href="{{ route('admin.products.index') }}"
                       class="admin-sidebar-link flex items-center justify-between px-5 py-2.5 font-medium {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-box-open w-4 text-center text-xs"></i>
                            <span>Products</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            @if($pendingProductsBadge > 0)
                                <span class="bg-[#fe9365] text-white text-[10px] font-extrabold px-1.5 py-0.2 rounded-full" title="{{ $pendingProductsBadge }} Pending">
                                    {{ $pendingProductsBadge }}
                                </span>
                            @else
                                <span class="bg-[#01a9ac] text-white text-[9px] font-bold px-1.5 py-0.2 rounded-full uppercase">NEW</span>
                            @endif
                            <i class="fa-solid fa-chevron-right text-[9px] text-[#71829e]"></i>
                        </div>
                    </a>

                    <!-- Product Categories Link -->
                    <a href="{{ route('admin.product-categories.index') }}"
                       class="admin-sidebar-link flex items-center justify-between px-5 py-2.5 font-medium {{ request()->routeIs('admin.product-categories.*') ? 'is-active' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-shapes w-4 text-center text-xs"></i>
                            <span>Product Categories</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[9px] text-[#71829e]"></i>
                    </a>
                </div>

                <!-- SECTION 4: TABLES / EDITORIAL & CMS -->
                <div class="px-5 pt-4 pb-1.5 admin-sidebar-nav-heading">
                    Tables
                </div>

                <div class="space-y-0.5">
                    <!-- Authors -->
                    <a href="{{ route('admin.authors.index') }}"
                       class="admin-sidebar-link flex items-center justify-between px-5 py-2.5 font-medium {{ request()->routeIs('admin.authors.*') ? 'is-active' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-users-gear w-4 text-center text-xs"></i>
                            <span>Author Management</span>
                        </div>
                        <span class="bg-[#fe5d70] text-white text-[9px] font-bold px-1.5 py-0.2 rounded-full uppercase">HOT</span>
                    </a>

                    <!-- Page Create / CMS Pages -->
                    <a href="{{ route('admin.pages.index') }}"
                       class="admin-sidebar-link flex items-center justify-between px-5 py-2.5 font-medium {{ request()->routeIs('admin.pages.*') ? 'is-active' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-regular fa-file-lines w-4 text-center text-xs"></i>
                            <span>Page Create & Pages</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[9px] text-[#71829e]"></i>
                    </a>

                    <!-- Articles -->
                    <div class="flex items-center justify-between px-5 py-2 font-medium admin-sidebar-link {{ request()->routeIs('admin.articles.*') ? 'is-active' : '' }}">
                        <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 flex-1">
                            <i class="fa-regular fa-newspaper w-4 text-center text-xs"></i>
                            <span>Articles</span>
                        </a>
                        <a href="{{ route('admin.articles.create') }}" title="Write New Article" class="p-1 rounded hover:bg-[#526380]/40 text-[#a0b1cc] hover:text-white transition-colors">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                        </a>
                    </div>

                    <!-- Architecture News -->
                    <div class="flex items-center justify-between px-5 py-2 font-medium admin-sidebar-link {{ request()->routeIs('admin.news.*') ? 'is-active' : '' }}">
                        <a href="{{ route('admin.news.index') }}" class="flex items-center gap-3 flex-1">
                            <i class="fa-solid fa-bullhorn w-4 text-center text-xs"></i>
                            <span>Architecture News</span>
                        </a>
                        <a href="{{ route('admin.news.create') }}" title="Post Architecture News" class="p-1 rounded hover:bg-[#526380]/40 text-[#a0b1cc] hover:text-white transition-colors">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                        </a>
                    </div>

                    <!-- Our Partners -->
                    <a href="{{ route('admin.partners.index') }}"
                       class="admin-sidebar-link flex items-center justify-between px-5 py-2.5 font-medium {{ request()->routeIs('admin.partners.*') ? 'is-active' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-handshake w-4 text-center text-xs"></i>
                            <span>Our Partners</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[9px] text-[#71829e]"></i>
                    </a>
                </div>

                <!-- SECTION 5: INQUIRIES & SETTINGS -->
                <div class="px-5 pt-4 pb-1.5 admin-sidebar-nav-heading">
                    Communications
                </div>

                <div class="space-y-0.5">
                    <!-- Product Inquiries -->
                    <a href="{{ route('admin.inquiries.index') }}"
                       class="admin-sidebar-link flex items-center justify-between px-5 py-2.5 font-medium {{ request()->routeIs('admin.inquiries.*') ? 'is-active' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-regular fa-envelope w-4 text-center text-xs"></i>
                            <span>Client Inquiries</span>
                        </div>
                        @if($newInquiriesBadge > 0)
                            <span class="bg-[#01a9ac] text-white text-[10px] font-extrabold px-1.5 py-0.2 rounded-full">
                                {{ $newInquiriesBadge }}
                            </span>
                        @endif
                    </a>

                    <!-- Settings & Status -->
                    <a href="{{ route('admin.settings.index') }}"
                       class="admin-sidebar-link flex items-center justify-between px-5 py-2.5 font-medium {{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-sliders w-4 text-center text-xs"></i>
                            <span>Settings & Status</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[9px] text-[#71829e]"></i>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Sidebar Footer / Admin Profile & Logout (Fixed at bottom) -->
        <div class="admin-sidebar-footer px-5 py-3.5 flex-shrink-0">
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 group min-w-0" title="Click to Edit Profile">
                    <img src="{{ Auth::guard('admin')->user()->avatar }}"
                         alt="{{ Auth::guard('admin')->user()->name ?? 'Admin' }}"
                         class="w-9 h-9 rounded-full object-cover border-2 border-[#526380] group-hover:border-[#fe5d70] transition-all">
                    <div class="min-w-0">
                        <span class="text-xs font-bold text-white block leading-tight truncate group-hover:text-[#fe5d70] transition-colors">{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</span>
                        <span class="text-[10px] text-[#868e96] uppercase tracking-wider font-semibold">SUPER ADMIN</span>
                    </div>
                </a>
                <form action="{{ route('admin.logout') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" class="text-[#fe5d70] hover:text-white font-semibold p-1.5 hover:bg-[#fe5d70]/20 rounded-lg transition-colors cursor-pointer" title="Sign Out">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
            <div class="mt-3 pt-2.5 border-t border-[#4a5874]/40 flex items-center justify-between">
                <a href="{{ route('home') }}" target="_blank" class="text-[11px] font-medium text-[#8f9eb3] hover:text-white flex items-center gap-1.5 transition-colors">
                    <span>Open Public Magazine</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-[#8f9eb3]"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- ================= MAIN RIGHT COLUMN WITH TOP HEADER ================= -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen">
        <!-- TOP HEADER -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30 px-4 sm:px-8 py-2.5 flex items-center justify-between shadow-2xs backdrop-blur-md bg-white/95">
            <!-- Left Side: Search, Fullscreen, Control Room Status & Public Site Link -->
            <div class="flex items-center gap-3">
                <!-- Search Icon -->
                <button type="button" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer" title="Quick Search">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
                <!-- Fullscreen Toggle Icon -->
                <button type="button" onclick="if (!document.fullscreenElement) { document.documentElement.requestFullscreen(); } else { document.exitFullscreen(); }" class="hidden sm:inline-flex p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer" title="Toggle Fullscreen">
                    <i class="fa-solid fa-expand text-xs"></i>
                </button>

                <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>

                <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="tracking-wide">Admin Control Room</span>
                </div>
                <span class="text-slate-300 hidden sm:inline">|</span>
                <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-900 font-medium transition-colors">
                    <span>View Public Magazine</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                </a>
            </div>

            <!-- Right Side: Header Quick Links & Admin Avatar with Dropdown -->
            <div class="flex items-center gap-2.5">
                <!-- Notifications Bell with Coral Badge (5) -->
                <button type="button" class="relative p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer" title="5 Notifications">
                    <i class="fa-regular fa-bell text-sm"></i>
                    <span class="absolute top-1 right-1 w-4 h-4 bg-[#fe5d70] text-white text-[9px] font-bold rounded-full flex items-center justify-center">5</span>
                </button>

                <!-- Messages Bubble with Cyan Badge (3) -->
                <button type="button" class="relative p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer" title="3 New Messages">
                    <i class="fa-regular fa-comments text-sm"></i>
                    <span class="absolute top-1 right-1 w-4 h-4 bg-[#01a9ac] text-white text-[9px] font-bold rounded-full flex items-center justify-center">3</span>
                </button>

                <!-- Settings Shortcut Button -->
                <a href="{{ route('admin.settings.index') }}" title="Site & System Settings" class="hidden sm:flex p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors">
                    <i class="fa-solid fa-gear text-sm"></i>
                </a>

                <!-- Admin Profile Dropdown Component -->
                <div class="relative admin-dropdown-container">
                    <button type="button"
                            id="desktop-admin-dropdown-btn"
                            onclick="toggleAdminDropdown('desktop-admin-dropdown-menu', 'desktop-admin-dropdown-arrow')"
                            aria-haspopup="true"
                            aria-expanded="false"
                            class="flex items-center gap-2.5 p-1 sm:px-2.5 sm:py-1.5 rounded-full sm:rounded-xl hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-900/10 cursor-pointer border border-transparent hover:border-slate-200">
                        <img src="{{ Auth::guard('admin')->user()->avatar }}"
                             alt="{{ Auth::guard('admin')->user()->name ?? 'Admin' }}"
                             class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover border-2 border-slate-200 shadow-2xs">
                        <div class="hidden sm:block text-left">
                            <span class="text-xs font-bold text-slate-900 block leading-tight">{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</span>
                            <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Super Admin</span>
                        </div>
                        <i id="desktop-admin-dropdown-arrow" class="admin-dropdown-arrow fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200 hidden sm:block"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="desktop-admin-dropdown-menu"
                         class="admin-dropdown-menu hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50 transition-all duration-150 transform opacity-0 scale-95 origin-top-right">
                        <!-- Dropdown Header -->
                        <div class="px-4 py-3 border-b border-slate-100 flex items-center gap-3">
                            <img src="{{ Auth::guard('admin')->user()->avatar }}"
                                 alt="{{ Auth::guard('admin')->user()->name ?? 'Admin' }}"
                                 class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-xs">
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold text-slate-900 truncate">{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</div>
                                <div class="text-[11px] text-slate-400 truncate">{{ Auth::guard('admin')->user()->email ?? 'admin@editorial.com' }}</div>
                                <span class="inline-block mt-1 text-[9px] font-extrabold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200 px-1.5 py-0.2 rounded">SUPER ADMIN</span>
                            </div>
                        </div>

                        <!-- Dropdown Navigation Links -->
                        <div class="py-1 text-xs">
                            <!-- 1. Edit Profile -->
                            <a href="{{ route('admin.profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-slate-700 hover:text-slate-950 hover:bg-slate-50 transition-colors font-medium">
                                <span class="w-8 h-8 rounded-lg bg-rose-50 text-[#fe5d70] flex items-center justify-center">
                                    <i class="fa-solid fa-user-pen text-xs"></i>
                                </span>
                                <div>
                                    <span class="font-bold block leading-tight text-slate-800">Edit Profile</span>
                                    <span class="text-[10px] text-slate-400">Change name, email, avatar & password</span>
                                </div>
                            </a>

                            <!-- 2. Settings -->
                            <a href="{{ route('admin.settings.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-slate-700 hover:text-slate-950 hover:bg-slate-50 transition-colors font-medium">
                                <span class="w-8 h-8 rounded-lg bg-cyan-50 text-[#01a9ac] flex items-center justify-center">
                                    <i class="fa-solid fa-sliders text-xs"></i>
                                </span>
                                <div>
                                    <span class="font-bold block leading-tight text-slate-800">Settings</span>
                                    <span class="text-[10px] text-slate-400">Branding, logo & site options</span>
                                </div>
                            </a>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-slate-100 my-1"></div>

                        <!-- 3. Logout -->
                        <form action="{{ route('admin.logout') }}" method="POST" class="p-1">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-colors font-semibold text-xs text-left cursor-pointer">
                                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                                </span>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

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
    </div>

    <!-- Dropdown Interaction Script -->
    <script>
        function toggleAdminDropdown(menuId, arrowId = null) {
            const menu = document.getElementById(menuId);
            const arrow = arrowId ? document.getElementById(arrowId) : null;
            if (!menu) return;

            const isHidden = menu.classList.contains('hidden');

            // Close any other open dropdown menus first
            document.querySelectorAll('.admin-dropdown-menu').forEach(otherMenu => {
                if (otherMenu !== menu && !otherMenu.classList.contains('hidden')) {
                    otherMenu.classList.remove('opacity-100', 'scale-100');
                    otherMenu.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        otherMenu.classList.add('hidden');
                    }, 150);
                }
            });
            document.querySelectorAll('.admin-dropdown-arrow').forEach(otherArrow => {
                if (otherArrow !== arrow) {
                    otherArrow.classList.remove('rotate-180');
                }
            });

            if (isHidden) {
                menu.classList.remove('hidden');
                requestAnimationFrame(() => {
                    menu.classList.remove('opacity-0', 'scale-95');
                    menu.classList.add('opacity-100', 'scale-100');
                });
                if (arrow) arrow.classList.add('rotate-180');
            } else {
                menu.classList.remove('opacity-100', 'scale-100');
                menu.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 150);
                if (arrow) arrow.classList.remove('rotate-180');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const containers = document.querySelectorAll('.admin-dropdown-container');
            containers.forEach(container => {
                if (!container.contains(event.target)) {
                    const menu = container.querySelector('.admin-dropdown-menu');
                    const arrow = container.querySelector('.admin-dropdown-arrow');
                    if (menu && !menu.classList.contains('hidden')) {
                        menu.classList.remove('opacity-100', 'scale-100');
                        menu.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            menu.classList.add('hidden');
                        }, 150);
                        if (arrow) arrow.classList.remove('rotate-180');
                    }
                }
            });
        });

        // Close dropdown on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.admin-dropdown-menu').forEach(menu => {
                    if (!menu.classList.contains('hidden')) {
                        menu.classList.remove('opacity-100', 'scale-100');
                        menu.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            menu.classList.add('hidden');
                        }, 150);
                    }
                });
                document.querySelectorAll('.admin-dropdown-arrow').forEach(arrow => {
                    arrow.classList.remove('rotate-180');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
