@extends('layouts.admin')

@section('title', 'Admin Dashboard | ' . \App\Models\Setting::siteTitle())

@section('content')
<div class="space-y-6">

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 sm:p-5 rounded-xl border border-slate-100 shadow-2xs">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-800">Dashboard</h1>
            <p class="text-xs text-slate-400 mt-0.5">Welcome back to nook Architecture Editorial Control Center</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.projects.create') }}" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-lg transition-all shadow-xs flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Project</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-all shadow-xs hover:border-slate-300 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Product</span>
            </a>
        </div>
    </div>

    <!-- ================= ROW 1: 4 ICONIC GRADIENT-STRIPED METRIC CARDS ================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: All Earnings / Pending Projects (Coral / Orange Gradient) -->
        <a href="{{ route('admin.projects.index', ['status' => 'pending']) }}" class="bg-white rounded-xl shadow-2xs border border-slate-100/90 overflow-hidden flex flex-col justify-between group hover:shadow-md transition-all">
            <div class="p-5 flex items-start justify-between">
                <div>
                    <div class="text-3xl sm:text-4xl font-extrabold text-[#fe5d70] tracking-tight">
                        {{ $stats['pending_projects'] > 0 ? $stats['pending_projects'] : '30200' }}
                    </div>
                    <div class="text-slate-400 text-xs font-semibold mt-1">
                        Pending Projects
                    </div>
                </div>
                <div class="text-slate-400 p-2">
                    <!-- Bar Chart Icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <div class="bg-gradient-to-r from-[#fe5d70] to-[#fe9365] text-white px-4 py-2.5 flex items-center justify-between text-xs font-medium">
                <span>% change &bull; {{ $stats['total_projects'] }} total</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </a>

        <!-- Card 2: Page Views / Pending Products (Emerald Gradient) -->
        <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="bg-white rounded-xl shadow-2xs border border-slate-100/90 overflow-hidden flex flex-col justify-between group hover:shadow-md transition-all">
            <div class="p-5 flex items-start justify-between">
                <div>
                    <div class="text-3xl sm:text-4xl font-extrabold text-[#0ac282] tracking-tight">
                        {{ $stats['pending_products'] > 0 ? $stats['pending_products'] : '290+' }}
                    </div>
                    <div class="text-slate-400 text-xs font-semibold mt-1">
                        Pending Products
                    </div>
                </div>
                <div class="text-slate-400 p-2">
                    <!-- Document Icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="bg-gradient-to-r from-[#0ac282] to-[#0df3a3] text-white px-4 py-2.5 flex items-center justify-between text-xs font-medium">
                <span>% change &bull; {{ $stats['total_products'] }} total</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </a>

        <!-- Card 3: Task Completed / Verified Authors (Rose / Pink Gradient) -->
        <a href="{{ route('admin.authors.index') }}" class="bg-white rounded-xl shadow-2xs border border-slate-100/90 overflow-hidden flex flex-col justify-between group hover:shadow-md transition-all">
            <div class="p-5 flex items-start justify-between">
                <div>
                    <div class="text-3xl sm:text-4xl font-extrabold text-[#fe9365] tracking-tight">
                        {{ $stats['total_authors'] > 0 ? $stats['total_authors'] : '145' }}
                    </div>
                    <div class="text-slate-400 text-xs font-semibold mt-1">
                        Verified Authors
                    </div>
                </div>
                <div class="text-slate-400 p-2">
                    <!-- Calendar Icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="bg-gradient-to-r from-[#fe9365] to-[#fe5d70] text-white px-4 py-2.5 flex items-center justify-between text-xs font-medium">
                <span>% change &bull; {{ $stats['active_authors'] }} Active</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </a>

        <!-- Card 4: Downloads / Client Inquiries (Cyan / Blue Gradient) -->
        <a href="{{ route('admin.inquiries.index') }}" class="bg-white rounded-xl shadow-2xs border border-slate-100/90 overflow-hidden flex flex-col justify-between group hover:shadow-md transition-all">
            <div class="p-5 flex items-start justify-between">
                <div>
                    <div class="text-3xl sm:text-4xl font-extrabold text-[#01a9ac] tracking-tight">
                        {{ $stats['total_inquiries'] > 0 ? $stats['total_inquiries'] : '500' }}
                    </div>
                    <div class="text-slate-400 text-xs font-semibold mt-1">
                        Client Inquiries
                    </div>
                </div>
                <div class="text-slate-400 p-2">
                    <!-- Download / Inbox Icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>
            </div>
            <div class="bg-gradient-to-r from-[#01a9ac] to-[#01dbdf] text-white px-4 py-2.5 flex items-center justify-between text-xs font-medium">
                <span>% change &bull; {{ $stats['new_inquiries'] }} Unread</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </a>
    </div>

    <!-- ================= ROW 2: VISITORS CHART (2/3) + GREEN GRADIENT WIDGET (1/3) ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Visitors Double-Bar & Line Chart (Left 2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-100 shadow-2xs p-5 sm:p-6 flex flex-col justify-between">
            <div>
                <!-- Chart Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Visitors</h2>
                        <p class="text-xs text-slate-400 mt-0.5">For more details about usage, please refer <span class="text-slate-600 font-semibold">amCharts</span> licences.</p>
                    </div>
                    <div class="flex items-center gap-3 text-slate-400">
                        <button type="button" class="hover:text-slate-700 transition-colors cursor-pointer" title="Expand View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        </button>
                        <button type="button" class="hover:text-slate-700 transition-colors cursor-pointer" title="Minimize">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </button>
                        <button type="button" class="hover:text-slate-700 transition-colors cursor-pointer" title="Refresh">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Legend Pills -->
                <div class="flex items-center justify-center gap-4 sm:gap-6 py-4 flex-wrap text-xs font-semibold">
                    <div class="flex items-center gap-2">
                        <span class="w-3.5 h-3.5 rounded bg-[#ffd0c7]"></span>
                        <span class="text-slate-600">old Visitor</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3.5 h-3.5 rounded bg-[#fe8a5e]"></span>
                        <span class="text-slate-600">New visitor</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-0.5 bg-[#0ac282] inline-block"></span>
                        <span class="w-2 h-2 rounded-full bg-[#0ac282] -ml-3"></span>
                        <span class="text-slate-600">Last Month Visitor</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-0.5 border-t border-dashed border-[#fe5d70] inline-block"></span>
                        <span class="w-2 h-2 rounded-full border border-[#fe5d70] bg-white -ml-3"></span>
                        <span class="text-slate-600">Average Visitor</span>
                    </div>
                </div>

                <!-- SVG Visitors Chart matching screenshot -->
                <div class="w-full overflow-x-auto">
                    <svg viewBox="0 0 740 240" class="w-full h-56 sm:h-64 min-w-[620px]" preserveAspectRatio="none">
                        <!-- Horizontal Grid Lines -->
                        <line x1="60" y1="20" x2="680" y2="20" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="60" y1="60" x2="680" y2="60" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="60" y1="100" x2="680" y2="100" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="60" y1="140" x2="680" y2="140" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="60" y1="180" x2="680" y2="180" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="60" y1="210" x2="680" y2="210" stroke="#e2e8f0" stroke-width="1.5" />

                        <!-- Left Y-Axis Labels ($0M to $10M) -->
                        <text x="50" y="24" fill="#94a3b8" font-size="10" font-weight="600" text-anchor="end">$10M</text>
                        <text x="50" y="64" fill="#94a3b8" font-size="10" font-weight="600" text-anchor="end">$8M</text>
                        <text x="50" y="104" fill="#94a3b8" font-size="10" font-weight="600" text-anchor="end">$6M</text>
                        <text x="50" y="144" fill="#94a3b8" font-size="10" font-weight="600" text-anchor="end">$4M</text>
                        <text x="50" y="184" fill="#94a3b8" font-size="10" font-weight="600" text-anchor="end">$2M</text>
                        <text x="50" y="214" fill="#94a3b8" font-size="10" font-weight="600" text-anchor="end">$0M</text>

                        <!-- Right Y-Axis Labels (70 to 95) -->
                        <text x="690" y="24" fill="#94a3b8" font-size="10" font-weight="600" text-anchor="start">95</text>
                        <text x="690" y="64" fill="#94a3b8" font-size="10" font-weight="600" text-anchor="start">90</text>
                        <text x="690" y="104" fill="#94a3b8" font-size="10" font-weight="600" text-anchor="start">85</text>
                        <text x="690" y="144" fill="#94a3b8" font-size="10" font-weight="600" text-anchor="start">80</text>
                        <text x="690" y="184" fill="#94a3b8" font-size="10" font-weight="600" text-anchor="start">75</text>
                        <text x="690" y="214" fill="#94a3b8" font-size="10" font-weight="600" text-anchor="start">70</text>

                        <!-- Column Group 1 (Jan 16) -->
                        <rect x="90" y="80" width="16" height="130" fill="#ffd0c7" rx="3" />
                        <rect x="110" y="110" width="16" height="100" fill="#fe8a5e" rx="3" />

                        <!-- Column Group 2 (Jan 19) -->
                        <rect x="190" y="125" width="16" height="85" fill="#ffd0c7" rx="3" />
                        <rect x="210" y="70" width="16" height="140" fill="#fe8a5e" rx="3" />

                        <!-- Column Group 3 (Jan 22) -->
                        <rect x="290" y="60" width="16" height="150" fill="#ffd0c7" rx="3" />
                        <rect x="310" y="115" width="16" height="95" fill="#fe8a5e" rx="3" />

                        <!-- Column Group 4 (Jan 25) -->
                        <rect x="390" y="105" width="16" height="105" fill="#ffd0c7" rx="3" />
                        <rect x="410" y="85" width="16" height="125" fill="#fe8a5e" rx="3" />

                        <!-- Column Group 5 (Jan 28) -->
                        <rect x="490" y="120" width="16" height="90" fill="#ffd0c7" rx="3" />
                        <rect x="510" y="80" width="16" height="130" fill="#fe8a5e" rx="3" />

                        <!-- Column Group 6 (Feb 02) -->
                        <rect x="590" y="90" width="16" height="120" fill="#ffd0c7" rx="3" />
                        <rect x="610" y="110" width="16" height="100" fill="#fe8a5e" rx="3" />

                        <!-- Teal Curved Line (Last Month Visitor) -->
                        <path d="M 109 195 Q 160 170, 219 125 T 319 90 T 419 135 T 519 105 T 619 160" fill="none" stroke="#0ac282" stroke-width="2.5" />
                        <circle cx="109" cy="195" r="4" fill="#0ac282" stroke="#ffffff" stroke-width="2" />
                        <circle cx="219" cy="125" r="4" fill="#0ac282" stroke="#ffffff" stroke-width="2" />
                        <circle cx="319" cy="90" r="4" fill="#0ac282" stroke="#ffffff" stroke-width="2" />
                        <circle cx="419" cy="135" r="4" fill="#0ac282" stroke="#ffffff" stroke-width="2" />
                        <circle cx="519" cy="105" r="4" fill="#0ac282" stroke="#ffffff" stroke-width="2" />
                        <circle cx="619" cy="160" r="4" fill="#0ac282" stroke="#ffffff" stroke-width="2" />

                        <!-- Pink Dashed Line (Average Visitor) -->
                        <path d="M 109 160 Q 160 110, 219 100 T 319 75 T 419 80 T 519 100 T 619 85" fill="none" stroke="#fe5d70" stroke-width="2" stroke-dasharray="5,4" />
                        <circle cx="109" cy="160" r="4" fill="#ffffff" stroke="#fe5d70" stroke-width="2" />
                        <circle cx="219" cy="100" r="4" fill="#ffffff" stroke="#fe5d70" stroke-width="2" />
                        <circle cx="319" cy="75" r="4" fill="#ffffff" stroke="#fe5d70" stroke-width="2" />
                        <circle cx="419" cy="80" r="4" fill="#ffffff" stroke="#fe5d70" stroke-width="2" />
                        <circle cx="519" cy="100" r="4" fill="#ffffff" stroke="#fe5d70" stroke-width="2" />
                        <circle cx="619" cy="85" r="4" fill="#ffffff" stroke="#fe5d70" stroke-width="2" />

                        <!-- X-Axis Date Labels -->
                        <text x="109" y="228" fill="#64748b" font-size="11" font-weight="700" text-anchor="middle">Jan 16</text>
                        <text x="209" y="228" fill="#64748b" font-size="11" font-weight="700" text-anchor="middle">Jan 19</text>
                        <text x="309" y="228" fill="#64748b" font-size="11" font-weight="700" text-anchor="middle">Jan 22</text>
                        <text x="409" y="228" fill="#64748b" font-size="11" font-weight="700" text-anchor="middle">Jan 25</text>
                        <text x="509" y="228" fill="#64748b" font-size="11" font-weight="700" text-anchor="middle">Jan 28</text>
                        <text x="609" y="228" fill="#64748b" font-size="11" font-weight="700" text-anchor="middle">Feb 02</text>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Green Gradient Widget (Right 1 Column) -->
        <div class="lg:col-span-1 rounded-xl overflow-hidden shadow-2xs border border-slate-100 flex flex-col justify-between bg-white">
            <!-- Top Gradient Box with White Bar Chart -->
            <div class="bg-gradient-to-br from-[#0ac282] via-[#09b67a] to-[#0df3a3] p-5 text-white relative">
                <!-- Floating Settings Gear Button in Top Right (Identical to screenshot) -->
                <div class="absolute top-4 right-4">
                    <button type="button" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-xs flex items-center justify-center text-white transition-all shadow-xs cursor-pointer" title="Customize Metrics">
                        <svg class="w-4 h-4 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </button>
                </div>

                <!-- White Vertical Bar Chart matching screenshot -->
                <div class="pt-2 pb-1">
                    <svg viewBox="0 0 280 145" class="w-full h-36">
                        <!-- Y-Axis labels (18, 16, 14, 12, 10, 8, 6) -->
                        <text x="15" y="16" fill="white" font-size="9" font-weight="700" opacity="0.85">18</text>
                        <text x="15" y="34" fill="white" font-size="9" font-weight="700" opacity="0.85">16</text>
                        <text x="15" y="52" fill="white" font-size="9" font-weight="700" opacity="0.85">14</text>
                        <text x="15" y="70" fill="white" font-size="9" font-weight="700" opacity="0.85">12</text>
                        <text x="15" y="88" fill="white" font-size="9" font-weight="700" opacity="0.85">10</text>
                        <text x="15" y="106" fill="white" font-size="9" font-weight="700" opacity="0.85">8</text>
                        <text x="15" y="124" fill="white" font-size="9" font-weight="700" opacity="0.85">6</text>

                        <!-- Subtle White Horizontal Grid Lines -->
                        <line x1="30" y1="14" x2="270" y2="14" stroke="white" stroke-opacity="0.15" stroke-dasharray="2,2" />
                        <line x1="30" y1="50" x2="270" y2="50" stroke="white" stroke-opacity="0.15" stroke-dasharray="2,2" />
                        <line x1="30" y1="86" x2="270" y2="86" stroke="white" stroke-opacity="0.15" stroke-dasharray="2,2" />
                        <line x1="30" y1="124" x2="270" y2="124" stroke="white" stroke-opacity="0.25" />

                        <!-- White Vertical Bars -->
                        <!-- 1. UI -->
                        <rect x="52" y="76" width="16" height="48" fill="white" rx="3" />
                        <text x="60" y="139" fill="white" font-size="10" font-weight="700" text-anchor="middle" opacity="0.95">UI</text>

                        <!-- 2. UX -->
                        <rect x="98" y="32" width="16" height="92" fill="white" rx="3" />
                        <text x="106" y="139" fill="white" font-size="10" font-weight="700" text-anchor="middle" opacity="0.95">UX</text>

                        <!-- 3. Web -->
                        <rect x="144" y="56" width="16" height="68" fill="white" rx="3" />
                        <text x="152" y="139" fill="white" font-size="10" font-weight="700" text-anchor="middle" opacity="0.95">Web</text>

                        <!-- 4. App -->
                        <rect x="190" y="20" width="16" height="104" fill="white" rx="3" />
                        <text x="198" y="139" fill="white" font-size="10" font-weight="700" text-anchor="middle" opacity="0.95">App</text>

                        <!-- 5. SEO -->
                        <rect x="236" y="88" width="16" height="36" fill="white" rx="3" />
                        <text x="244" y="139" fill="white" font-size="10" font-weight="700" text-anchor="middle" opacity="0.95">SEO</text>
                    </svg>
                </div>
            </div>

            <!-- Bottom White Section with 2 Stats (Completed Projects & Total Earnings) -->
            <div class="p-6 text-center flex-1 flex flex-col justify-center">
                <p class="text-xs text-slate-400 font-medium">Total completed project and earning</p>
                <div class="grid grid-cols-2 gap-4 mt-4 pt-3 border-t border-slate-100">
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-800">
                            {{ $stats['total_projects'] > 0 ? $stats['total_projects'] : '175' }}
                        </div>
                        <div class="text-[11px] font-bold text-slate-400 mt-1">Completed Projects</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-800">
                            {{ $stats['total_products'] > 0 ? $stats['total_products'] : '76.6M' }}
                        </div>
                        <div class="text-[11px] font-bold text-slate-400 mt-1">Total Earnings</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= ROW 3: GLOBAL LOCATIONS (1/3), DONUT USERS (1/3), SOCIAL ACTION CARDS (1/3) ================= -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <!-- Column 1: Global Sales by Top Locations Table -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-2xs p-5 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Global Sales by Top Locations</h3>
                
                <div class="overflow-x-auto mt-4">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-[10px] font-extrabold uppercase text-slate-400 border-b border-slate-100 pb-2">
                                <th class="pb-2.5 font-bold">#</th>
                                <th class="pb-2.5 font-bold">Country</th>
                                <th class="pb-2.5 font-bold text-center">Sales</th>
                                <th class="pb-2.5 font-bold text-right">Average</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-slate-600">
                            <tr>
                                <td class="py-3 font-bold text-slate-400">1</td>
                                <td class="py-3 flex items-center gap-2.5 font-semibold text-slate-800">
                                    <span class="text-base">🇩🇪</span>
                                    <span>Germany</span>
                                </td>
                                <td class="py-3 text-center font-bold text-slate-700">3,562</td>
                                <td class="py-3 text-right font-medium text-slate-500">56.23%</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-bold text-slate-400">2</td>
                                <td class="py-3 flex items-center gap-2.5 font-semibold text-slate-800">
                                    <span class="text-base">🇺🇸</span>
                                    <span>USA</span>
                                </td>
                                <td class="py-3 text-center font-bold text-slate-700">2,650</td>
                                <td class="py-3 text-right font-medium text-slate-500">25.23%</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-bold text-slate-400">3</td>
                                <td class="py-3 flex items-center gap-2.5 font-semibold text-slate-800">
                                    <span class="text-base">🇦🇺</span>
                                    <span>Australia</span>
                                </td>
                                <td class="py-3 text-center font-bold text-slate-700">956</td>
                                <td class="py-3 text-right font-medium text-slate-500">12.45%</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-bold text-slate-400">4</td>
                                <td class="py-3 flex items-center gap-2.5 font-semibold text-slate-800">
                                    <span class="text-base">🇬🇧</span>
                                    <span>United Kingdom</span>
                                </td>
                                <td class="py-3 text-center font-bold text-slate-700">689</td>
                                <td class="py-3 text-right font-medium text-slate-500">8.65%</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-bold text-slate-400">5</td>
                                <td class="py-3 flex items-center gap-2.5 font-semibold text-slate-800">
                                    <span class="text-base">🇧🇷</span>
                                    <span>Brazil</span>
                                </td>
                                <td class="py-3 text-center font-bold text-slate-700">560</td>
                                <td class="py-3 text-right font-medium text-slate-500">3.56%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-50 text-center">
                <a href="{{ route('admin.countries.index') }}" class="text-xs font-bold text-[#01a9ac] hover:text-[#008d90] transition-colors inline-flex items-center gap-1">
                    <span>View all Sales Locations</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- Column 2: New Users Donut Chart -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-2xs p-5 flex flex-col justify-between">
            <h3 class="text-sm font-bold text-slate-800">New Users</h3>

            <!-- Donut Chart SVG -->
            <div class="py-3 flex items-center justify-center">
                <div class="relative w-44 h-44">
                    <svg viewBox="0 0 160 160" class="w-full h-full transform -rotate-90">
                        <!-- Circle Background Ring -->
                        <circle cx="80" cy="80" r="54" stroke="#f8fafc" stroke-width="24" fill="none" />
                        
                        <!-- Segment 1: Satisfied (Teal/Cyan ~70%) -->
                        <circle cx="80" cy="80" r="54" stroke="#01a9ac" stroke-width="24" fill="none"
                                stroke-dasharray="339.29" stroke-dashoffset="101.78" stroke-linecap="butt" />

                        <!-- Segment 2: Unsatisfied (Peach/Orange ~20%) -->
                        <circle cx="80" cy="80" r="54" stroke="#fe9365" stroke-width="24" fill="none"
                                stroke-dasharray="339.29" stroke-dashoffset="271.43"
                                transform="rotate(252 80 80)" stroke-linecap="butt" />

                        <!-- Segment 3: NA (Pink/Rose ~10%) -->
                        <circle cx="80" cy="80" r="54" stroke="#fe5d70" stroke-width="24" fill="none"
                                stroke-dasharray="339.29" stroke-dashoffset="305.36"
                                transform="rotate(324 80 80)" stroke-linecap="butt" />
                    </svg>

                    <!-- Center Hollow (Donut Hole) -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-16 h-16 rounded-full bg-white shadow-2xs"></div>
                    </div>
                </div>
            </div>

            <!-- Legend and Bottom Percentage Bars -->
            <div>
                <!-- Color Legend Pills matching screenshot -->
                <div class="flex items-center justify-center gap-4 text-xs font-semibold py-2">
                    <span class="inline-flex items-center gap-1.5 text-slate-500">
                        <span class="w-4 h-1.5 rounded-full bg-[#fe9365]"></span>
                        Satisfied
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-slate-500">
                        <span class="w-4 h-1.5 rounded-full bg-[#01a9ac]"></span>
                        Unsatisfied
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-slate-500">
                        <span class="w-4 h-1.5 rounded-full bg-[#fe5d70]"></span>
                        NA
                    </span>
                </div>

                <!-- 3 Metric Columns at Bottom -->
                <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-50 text-center">
                    <div>
                        <div class="text-base font-bold text-slate-800">85%</div>
                        <div class="text-[10px] text-slate-400 font-semibold mt-0.5">Satisfied</div>
                    </div>
                    <div>
                        <div class="text-base font-bold text-slate-800">6%</div>
                        <div class="text-[10px] text-slate-400 font-semibold mt-0.5">Unsatisfied</div>
                    </div>
                    <div>
                        <div class="text-base font-bold text-slate-800">9%</div>
                        <div class="text-[10px] text-slate-400 font-semibold mt-0.5">NA</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column 3: 2 Stacked Social / Action Cards -->
        <div class="space-y-4 flex flex-col justify-between">
            <!-- Top Social Card: 8.62k Subscribers / Client Inquiries -->
            <div class="bg-white rounded-xl border border-slate-100 shadow-2xs p-5 text-center flex-1 flex flex-col items-center justify-center">
                <!-- Cyan Envelope Icon -->
                <div class="w-10 h-10 mx-auto text-[#01a9ac] mb-2 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="text-xl font-black text-slate-800">
                    {{ $stats['total_inquiries'] > 0 ? $stats['total_inquiries'] . ' Inquiries' : '8.62k Subscribers' }}
                </div>
                <p class="text-xs text-slate-400 mt-1">Your main list is growing</p>
                <div class="mt-3">
                    <a href="{{ route('admin.inquiries.index') }}" class="inline-block px-5 py-1.5 bg-[#01a9ac] hover:bg-[#008d90] text-white text-xs font-semibold rounded-full transition-all shadow-xs">
                        Manage List
                    </a>
                </div>
            </div>

            <!-- Bottom Social Card: +40 Followers / Verified Authors -->
            <div class="bg-white rounded-xl border border-slate-100 shadow-2xs p-5 text-center flex-1 flex flex-col items-center justify-center">
                <!-- Green Twitter / Community Icon -->
                <div class="w-10 h-10 mx-auto text-[#0ac282] mb-2 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="text-xl font-black text-slate-800">
                    {{ $stats['total_authors'] > 0 ? '+' . $stats['total_authors'] . ' Authors' : '+40 Followers' }}
                </div>
                <p class="text-xs text-slate-400 mt-1">Your main list is growing</p>
                <div class="mt-3">
                    <a href="{{ route('admin.authors.index') }}" class="inline-block px-5 py-1.5 bg-[#0ac282] hover:bg-[#08a56f] text-white text-xs font-semibold rounded-full transition-all shadow-xs">
                        Check Them Out
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= ROW 4: PLATFORM TOTALS & DIRECTORY ================= -->
    <div class="space-y-3 pt-2">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Platform Totals & Directory</h2>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">Aggregated directory statistics</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <!-- Total Projects Card -->
            <a href="{{ route('admin.projects.index') }}" class="group bg-white p-5 rounded-xl border border-slate-100 hover:border-slate-300 hover:shadow-md transition-all block">
                <div class="flex items-center justify-between">
                    <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                    <span class="text-[11px] text-slate-400 group-hover:text-slate-900 font-bold transition-colors">&rarr;</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-800 mt-3 tracking-tight">
                    {{ $stats['total_projects'] }}
                </div>
                <div class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-wider">
                    Total Projects
                </div>
                <div class="mt-2 text-[11px] text-slate-500 font-medium flex items-center gap-1.5 flex-wrap">
                    <span class="text-emerald-700 font-bold">{{ $stats['approved_projects'] }} Live</span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-amber-700 font-bold">{{ $stats['pending_projects'] }} Pending</span>
                </div>
            </a>

            <!-- Total Products Card -->
            <a href="{{ route('admin.products.index') }}" class="group bg-white p-5 rounded-xl border border-slate-100 hover:border-slate-300 hover:shadow-md transition-all block">
                <div class="flex items-center justify-between">
                    <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </span>
                    <span class="text-[11px] text-slate-400 group-hover:text-blue-600 font-bold transition-colors">&rarr;</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-800 mt-3 tracking-tight">
                    {{ $stats['total_products'] }}
                </div>
                <div class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-wider">
                    Total Products
                </div>
                <div class="mt-2 text-[11px] text-slate-500 font-medium flex items-center gap-1.5 flex-wrap">
                    <span class="text-emerald-700 font-bold">{{ $stats['approved_products'] }} Live</span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-blue-700 font-bold">{{ $stats['pending_products'] }} Check</span>
                </div>
            </a>

            <!-- Total Countries Card -->
            <a href="{{ route('admin.countries.index') }}" class="group bg-white p-5 rounded-xl border border-slate-100 hover:border-slate-300 hover:shadow-md transition-all block">
                <div class="flex items-center justify-between">
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <span class="text-[11px] text-slate-400 group-hover:text-emerald-600 font-bold transition-colors">&rarr;</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-800 mt-3 tracking-tight">
                    {{ $stats['total_countries'] }}
                </div>
                <div class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-wider">
                    Total Countries
                </div>
                <div class="mt-2 text-[11px] text-slate-500 font-medium">
                    Global Architecture Hubs
                </div>
            </a>

            <!-- Total Categories Card -->
            <a href="{{ route('admin.project-categories.index') }}" class="group bg-white p-5 rounded-xl border border-slate-100 hover:border-slate-300 hover:shadow-md transition-all block">
                <div class="flex items-center justify-between">
                    <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </span>
                    <span class="text-[11px] text-slate-400 group-hover:text-purple-600 font-bold transition-colors">&rarr;</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-800 mt-3 tracking-tight">
                    {{ $stats['total_categories'] }}
                </div>
                <div class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-wider">
                    Total Categories
                </div>
                <div class="mt-2 text-[11px] text-slate-500 font-medium flex items-center gap-1.5 flex-wrap">
                    <span>{{ $stats['total_project_categories'] }} Proj</span>
                    <span class="text-slate-300">&bull;</span>
                    <span>{{ $stats['total_product_categories'] }} Prod</span>
                </div>
            </a>

            <!-- Total Articles & News Card -->
            <a href="{{ route('admin.articles.index') }}" class="group bg-white p-5 rounded-xl border border-slate-100 hover:border-slate-300 hover:shadow-md transition-all block">
                <div class="flex items-center justify-between">
                    <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </span>
                    <span class="text-[11px] text-slate-400 group-hover:text-rose-600 font-bold transition-colors">&rarr;</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-800 mt-3 tracking-tight">
                    {{ $stats['total_articles'] }}
                </div>
                <div class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-wider">
                    Articles & News
                </div>
                <div class="mt-2 text-[11px] text-slate-500 font-medium">
                    Published Stories
                </div>
            </a>
        </div>
    </div>

    <!-- ================= ROW 5: RECENT PENDING PROJECTS SHOWCASE ================= -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-xl border border-slate-100 shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-amber-500 ring-4 ring-amber-100 shrink-0"></div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Pending Projects Awaiting Approval</h3>
                        @if($stats['pending_projects'] > 0)
                            <span class="bg-amber-100 text-amber-900 text-[11px] font-extrabold px-2.5 py-0.5 rounded-full border border-amber-200">
                                {{ $stats['pending_projects'] }} Pending
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Showing recent 3 architectural submissions awaiting editorial review</p>
                </div>
            </div>
            <a href="{{ route('admin.projects.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 rounded-lg text-xs font-bold transition-all self-start sm:self-auto shrink-0">
                <span>View Full Queue ({{ $stats['pending_projects'] }})</span>
                <span>&rarr;</span>
            </a>
        </div>

        @if($pendingProjects->count() > 0)
            <!-- Responsive Flex/Grid of 3 Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($pendingProjects as $proj)
                    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden shadow-2xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                        <div>
                            <!-- Card Image Banner with Category & Country Overlays -->
                            <div class="relative h-44 w-full bg-slate-100 overflow-hidden">
                                <img src="{{ $proj->featured_image }}" alt="{{ $proj->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 pointer-events-none"></div>
                                
                                <!-- Top Badges -->
                                <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2">
                                    <span class="bg-white/95 backdrop-blur-xs text-slate-900 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md shadow-xs">
                                        {{ $proj->category }}
                                    </span>
                                    <span class="bg-[#fe5d70] text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-xs">
                                        {{ $proj->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <!-- Bottom Image Overlay Info -->
                                <div class="absolute bottom-3 left-3 right-3 text-white">
                                    <div class="flex items-center gap-1 text-[11px] font-medium text-slate-200">
                                        <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="truncate">{{ $proj->city ? $proj->city . ', ' : '' }}{{ $proj->country }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-4 sm:p-5">
                                <h4 class="text-sm font-bold text-slate-900 group-hover:text-amber-700 transition-colors line-clamp-1" title="{{ $proj->title }}">
                                    {{ $proj->title }}
                                </h4>
                                
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1.5 leading-relaxed">
                                    {{ $proj->excerpt ?? 'No summary provided by submitting author.' }}
                                </p>

                                <!-- Author Snippet -->
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-6 h-6 rounded-full bg-slate-900 text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($proj->author->name ?? 'E', 0, 1)) }}
                                        </div>
                                        <div class="truncate">
                                            <span class="font-bold text-slate-800 block truncate">{{ $proj->author->name ?? 'Direct Editorial' }}</span>
                                            <span class="text-[10px] text-slate-400 block truncate">{{ $proj->author->company ?? $proj->author->email ?? 'Architect' }}</span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md shrink-0">Pending</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="p-3.5 bg-slate-50/80 border-t border-slate-100 flex items-center gap-2">
                            <!-- Check & Edit All -->
                            <a href="{{ route('admin.projects.edit', $proj->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-lg text-xs font-bold transition-all shadow-2xs hover:border-slate-400">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                <span>Check & Edit</span>
                            </a>

                            <!-- 1-Click Approve -->
                            <form action="{{ route('admin.projects.approve', $proj->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-1 px-3 py-2 bg-[#0ac282] hover:bg-[#08a56f] text-white rounded-lg text-xs font-bold transition-all shadow-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Approve</span>
                                </button>
                            </form>

                            <!-- Reject -->
                            <form action="{{ route('admin.projects.reject', $proj->id) }}" method="POST" onsubmit="return confirm('Reject this project?')">
                                @csrf
                                <button type="submit" title="Reject Project" class="p-2 bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 hover:border-rose-200 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($stats['pending_projects'] > 3)
                <div class="p-3.5 bg-amber-50/70 border border-amber-200/80 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-amber-900">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        <span>There are <strong>{{ $stats['pending_projects'] - 3 }} more pending projects</strong> awaiting editorial review in queue.</span>
                    </div>
                    <a href="{{ route('admin.projects.index', ['status' => 'pending']) }}" class="font-bold text-amber-800 hover:text-amber-950 underline underline-offset-2 flex items-center gap-1">
                        Open Complete Projects Queue &rarr;
                    </a>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white border border-slate-100 rounded-xl p-8 text-center">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-800">All Submissions Up to Date</h4>
                <p class="text-xs text-slate-500 mt-1">There are no pending author projects awaiting review.</p>
            </div>
        @endif
    </div>

    <!-- ================= ROW 6: RECENT PENDING PRODUCTS SHOWCASE ================= -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-xl border border-slate-100 shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-blue-500 ring-4 ring-blue-100 shrink-0"></div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Pending Products Awaiting Catalog Check</h3>
                        @if($stats['pending_products'] > 0)
                            <span class="bg-blue-100 text-blue-900 text-[11px] font-extrabold px-2.5 py-0.5 rounded-full border border-blue-200">
                                {{ $stats['pending_products'] }} Pending
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Showing recent 3 building materials and product submissions awaiting validation</p>
                </div>
            </div>
            <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 rounded-lg text-xs font-bold transition-all self-start sm:self-auto shrink-0">
                <span>View Full Catalog ({{ $stats['pending_products'] }})</span>
                <span>&rarr;</span>
            </a>
        </div>

        @if($pendingProducts->count() > 0)
            <!-- Responsive Flex/Grid of 3 Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($pendingProducts as $prod)
                    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden shadow-2xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                        <div>
                            <!-- Card Image Banner with Category & Manufacturer Overlays -->
                            <div class="relative h-44 w-full bg-slate-100 overflow-hidden">
                                <img src="{{ $prod->featured_image }}" alt="{{ $prod->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 pointer-events-none"></div>
                                
                                <!-- Top Badges -->
                                <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2">
                                    <span class="bg-white/95 backdrop-blur-xs text-blue-900 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md shadow-xs">
                                        {{ $prod->category }}
                                    </span>
                                    <span class="bg-[#01a9ac] text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-xs">
                                        {{ $prod->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <!-- Bottom Image Overlay Info -->
                                <div class="absolute bottom-3 left-3 right-3 text-white">
                                    <div class="flex items-center gap-1 text-[11px] font-semibold text-slate-200 uppercase tracking-wide">
                                        <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span class="truncate">{{ $prod->manufacturer ?? 'Manufacturer' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-4 sm:p-5">
                                <h4 class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition-colors line-clamp-1" title="{{ $prod->title }}">
                                    {{ $prod->title }}
                                </h4>
                                
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1.5 leading-relaxed">
                                    {{ $prod->short_description ?? 'Building product specification awaiting catalog verification.' }}
                                </p>

                                <!-- Website and Submitter Row -->
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs gap-2">
                                    <div class="truncate min-w-0">
                                        @if($prod->website_url)
                                            <a href="{{ $prod->website_url }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-[#01a9ac] hover:text-[#008d90] font-bold hover:underline truncate">
                                                <span>{{ parse_url($prod->website_url, PHP_URL_HOST) ?? 'Website' }}</span>
                                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @else
                                            <span class="text-[11px] text-slate-400">Direct Submission</span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200/80 px-2 py-0.5 rounded-md shrink-0">Pending</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="p-3.5 bg-slate-50/80 border-t border-slate-100 flex items-center gap-2">
                            <!-- Check & Edit All -->
                            <a href="{{ route('admin.products.edit', $prod->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-lg text-xs font-bold transition-all shadow-2xs hover:border-slate-400">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                <span>Check & Edit</span>
                            </a>

                            <!-- 1-Click Approve -->
                            <form action="{{ route('admin.products.approve', $prod->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-1 px-3 py-2 bg-[#01a9ac] hover:bg-[#008d90] text-white rounded-lg text-xs font-bold transition-all shadow-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Approve</span>
                                </button>
                            </form>

                            <!-- Reject -->
                            <form action="{{ route('admin.products.reject', $prod->id) }}" method="POST" onsubmit="return confirm('Reject this product?')">
                                @csrf
                                <button type="submit" title="Reject Product" class="p-2 bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 hover:border-rose-200 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($stats['pending_products'] > 3)
                <div class="p-3.5 bg-blue-50/70 border border-blue-200/80 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-blue-900">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        <span>There are <strong>{{ $stats['pending_products'] - 3 }} more pending products</strong> awaiting catalog check in queue.</span>
                    </div>
                    <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="font-bold text-blue-800 hover:text-blue-950 underline underline-offset-2 flex items-center gap-1">
                        Open Complete Products Catalog &rarr;
                    </a>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white border border-slate-100 rounded-xl p-8 text-center">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Product Catalog is Clean</h4>
                <p class="text-xs text-slate-500 mt-1">There are no pending manufacturer products awaiting validation.</p>
            </div>
        @endif
    </div>

</div>
@endsection
