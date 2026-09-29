@extends('layouts.admin')

@section('title', 'Admin Dashboard | nook Editorial Control')

@section('content')
<div class="space-y-8">

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md">Executive Overview</span>
                <span class="text-xs text-slate-400">&bull;</span>
                <span class="text-xs text-slate-500 font-medium">{{ now()->format('l, F j, Y') }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Editorial Administration Dashboard</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Review pending architect submissions, curate catalogs, and supervise publication pipeline.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.projects.create') }}" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-all shadow-sm hover:shadow flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add Project</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-800 text-xs font-bold rounded-xl transition-all shadow-2xs hover:border-slate-400 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add Product</span>
            </a>
        </div>
    </div>

    <!-- Premium Metrics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Projects Pending Card -->
        <div class="bg-white p-6 rounded-2xl border transition-all duration-200 shadow-sm hover:shadow-md {{ $stats['pending_projects'] > 0 ? 'border-amber-300 ring-2 ring-amber-100/60' : 'border-slate-200/90' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider">Pending Projects</span>
                <span class="flex h-2.5 w-2.5 relative">
                    @if($stats['pending_projects'] > 0)
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                    @else
                        <span class="inline-flex rounded-full h-2.5 w-2.5 bg-slate-300"></span>
                    @endif
                </span>
            </div>
            <div class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                {{ $stats['pending_projects'] }}
            </div>
            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                <span>Awaiting review</span>
                <span class="text-slate-400 font-semibold">{{ $stats['total_projects'] }} total</span>
            </div>
        </div>

        <!-- Products Pending Card -->
        <div class="bg-white p-6 rounded-2xl border transition-all duration-200 shadow-sm hover:shadow-md {{ $stats['pending_products'] > 0 ? 'border-blue-300 ring-2 ring-blue-100/60' : 'border-slate-200/90' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider">Pending Products</span>
                <span class="flex h-2.5 w-2.5 relative">
                    @if($stats['pending_products'] > 0)
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                    @else
                        <span class="inline-flex rounded-full h-2.5 w-2.5 bg-slate-300"></span>
                    @endif
                </span>
            </div>
            <div class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                {{ $stats['pending_products'] }}
            </div>
            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                <span>Awaiting catalog check</span>
                <span class="text-slate-400 font-semibold">{{ $stats['total_products'] }} total</span>
            </div>
        </div>

        <!-- Authors Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider">Verified Authors</span>
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
            </div>
            <div class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                {{ $stats['total_authors'] }}
            </div>
            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                <span>Registered Community</span>
                <span class="text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded">{{ $stats['active_authors'] }} Active</span>
            </div>
        </div>

        <!-- Inquiries Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider">Client Inquiries</span>
                <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
            </div>
            <div class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                {{ $stats['total_inquiries'] }}
            </div>
            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                <span>Manufacturer Leads</span>
                @if($stats['new_inquiries'] > 0)
                    <span class="text-teal-800 font-bold bg-teal-50 px-1.5 py-0.5 rounded">{{ $stats['new_inquiries'] }} unread</span>
                @else
                    <span class="text-slate-400">All read</span>
                @endif
            </div>
        </div>
    </div>

    <!-- ================= PENDING PROJECTS QUEUE ================= -->
    <!-- "autor submit your project a click korle login korte hobe then project ar info input feild puron kore submit korle pending a thakbe admin check deye all edit korte parbe then aprov ekorle show hobe" -->
    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4.5 bg-white border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Pending Projects Awaiting Admin Approval</h3>
                    <p class="text-[11px] text-slate-500">Authors submitted these projects for editorial validation</p>
                </div>
                @if($pendingProjects->count() > 0)
                    <span class="ml-2 bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                        {{ $pendingProjects->count() }} Urgent
                    </span>
                @endif
            </div>
            <a href="{{ route('admin.projects.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1 transition-colors">
                <span>View Full Queue</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[700px]">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Project Title</th>
                        <th class="px-6 py-3.5">Submitting Author</th>
                        <th class="px-6 py-3.5">Category & Country</th>
                        <th class="px-6 py-3.5">Submitted</th>
                        <th class="px-6 py-3.5 text-right">Review Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($pendingProjects as $proj)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3.5">
                                <img src="{{ $proj->featured_image }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shrink-0 shadow-2xs">
                                <div>
                                    <strong class="text-slate-900 block font-bold text-xs">{{ $proj->title }}</strong>
                                    <span class="text-[11px] text-slate-500 block line-clamp-1 max-w-sm mt-0.5">{{ $proj->excerpt }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($proj->author)
                                    <div class="font-semibold text-slate-900">{{ $proj->author->name }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $proj->author->company ?? $proj->author->email }}</div>
                                @else
                                    <span class="text-slate-400 font-medium">Editorial Direct</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-700 border border-slate-200/80 px-2 py-0.5 rounded-md text-[11px] font-medium">{{ $proj->category }}</span>
                                <span class="text-[11px] text-slate-500 block mt-1">{{ $proj->country }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-[11px] font-medium">
                                {{ $proj->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <!-- Admin Check & Edit All Button (Specified in User Prompt) -->
                                <a href="{{ route('admin.projects.edit', $proj->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg font-bold transition-all shadow-2xs hover:border-slate-400">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    <span>Check & Edit All</span>
                                </a>

                                <!-- 1-Click Approve -->
                                <form action="{{ route('admin.projects.approve', $proj->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold transition-all shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>Approve</span>
                                    </button>
                                </form>

                                <!-- Quick Reject -->
                                <form action="{{ route('admin.projects.reject', $proj->id) }}" method="POST" class="inline" onsubmit="return confirm('Reject this project?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg font-semibold transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span>Reject</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2 font-bold text-sm">✓</div>
                                <span class="font-medium text-xs text-slate-500">No pending projects awaiting review. All submissions are up to date!</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= PENDING PRODUCTS QUEUE ================= -->
    <!-- "product ao same vabe autor submit korbe admin check deye edit korle approve korbe ,, porduct ar website link add kora jabe" -->
    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4.5 bg-white border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Pending Products Awaiting Admin Approval</h3>
                    <p class="text-[11px] text-slate-500">Building systems, materials and manufacturer listings with external website links</p>
                </div>
                @if($pendingProducts->count() > 0)
                    <span class="ml-2 bg-blue-50 text-blue-800 border border-blue-200 text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                        {{ $pendingProducts->count() }} Urgent
                    </span>
                @endif
            </div>
            <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1 transition-colors">
                <span>View Full Catalog</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[700px]">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Product & Manufacturer</th>
                        <th class="px-6 py-3.5">Submitting Author</th>
                        <th class="px-6 py-3.5">Website Link</th>
                        <th class="px-6 py-3.5">Submitted</th>
                        <th class="px-6 py-3.5 text-right">Review Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($pendingProducts as $prod)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3.5">
                                <img src="{{ $prod->featured_image }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shrink-0 shadow-2xs">
                                <div>
                                    <strong class="text-slate-900 block font-bold text-xs">{{ $prod->title }}</strong>
                                    <span class="text-[11px] text-slate-500 uppercase font-semibold mt-0.5 block">{{ $prod->manufacturer }} &bull; {{ $prod->category }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($prod->author)
                                    <div class="font-semibold text-slate-900">{{ $prod->author->name }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $prod->author->email }}</div>
                                @else
                                    <span class="text-slate-400 font-medium">Direct Editorial</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($prod->website_url)
                                    <a href="{{ $prod->website_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline truncate max-w-xs block font-mono text-[11px]">
                                        {{ $prod->website_url }} &nearr;
                                    </a>
                                @else
                                    <span class="text-slate-400">None</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-[11px] font-medium">
                                {{ $prod->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <!-- Admin Check & Edit All -->
                                <a href="{{ route('admin.products.edit', $prod->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg font-bold transition-all shadow-2xs hover:border-slate-400">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    <span>Check & Edit All</span>
                                </a>

                                <!-- 1-Click Approve -->
                                <form action="{{ route('admin.products.approve', $prod->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold transition-all shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>Approve</span>
                                    </button>
                                </form>

                                <!-- Reject -->
                                <form action="{{ route('admin.products.reject', $prod->id) }}" method="POST" class="inline" onsubmit="return confirm('Reject this product?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg font-semibold transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span>Reject</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2 font-bold text-sm">✓</div>
                                <span class="font-medium text-xs text-slate-500">No pending products awaiting review.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
