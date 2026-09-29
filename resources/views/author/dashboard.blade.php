@extends('layouts.author')

@section('title', 'Author Dashboard | nook MAGAZINE Studio')

@section('content')
<div class="space-y-8">

    <!-- Welcome Banner -->
    <div class="bg-white border border-zinc-200 rounded-xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('author.profile.edit') }}" class="relative group block" title="Click to Change Avatar">
                <img src="{{ $author->avatar }}" class="w-16 h-16 rounded-full object-cover border-2 border-zinc-200 shadow group-hover:opacity-90 transition-opacity">
                <span class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity text-[10px] font-bold">
                    Edit
                </span>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">{{ $author->name }}</h1>
                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Verified Author</span>
                </div>
                <p class="text-xs text-zinc-500 mt-0.5">{{ $author->title ?? 'Architect' }} &bull; {{ $author->company ?? $author->country }}</p>
                <div class="flex items-center gap-3 text-xs text-zinc-600 mt-2">
                    <span><strong>{{ $author->followers_count }}</strong> Followers</span>
                    <span>&bull;</span>
                    <span><strong>{{ $author->following_count }}</strong> Following</span>
                </div>
            </div>
        </div>

        <!-- Quick Submit Actions & Edit Profile -->
        <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 sm:gap-2.5 w-full md:w-auto">
            <a href="{{ route('author.profile.edit') }}" class="px-3 sm:px-4 py-2 sm:py-2.5 bg-zinc-900 hover:bg-black text-white rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>Edit Profile</span>
            </a>
            <a href="{{ route('author.projects.create') }}" class="px-3 sm:px-4 py-2 sm:py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                <span class="text-emerald-200 font-bold text-sm">+</span> <span>Project</span>
            </a>
            <a href="{{ route('author.products.create') }}" class="px-3 sm:px-4 py-2 sm:py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                <span class="text-blue-200 font-bold text-sm">+</span> <span>Product</span>
            </a>
            <a href="{{ route('author.profile', $author->username) }}" target="_blank" class="px-3 sm:px-3.5 py-2 sm:py-2.5 bg-zinc-100 text-zinc-800 hover:bg-zinc-200 rounded-lg text-xs font-bold transition-colors border border-zinc-300 flex items-center justify-center gap-1">
                <span>Profile &nearr;</span>
            </a>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-5 rounded-xl border border-zinc-200 shadow-sm">
            <span class="text-xs text-zinc-400 font-medium uppercase tracking-wider block mb-1">Approved Projects</span>
            <div class="text-2xl sm:text-3xl font-black text-emerald-600">{{ $approvedProjectsCount }}</div>
            <span class="text-[11px] text-zinc-400 mt-1 block">Live on {{ \App\Models\Setting::logoText() }} MAGAZINE</span>
        </div>

        <div class="bg-white p-5 rounded-xl border border-zinc-200 shadow-sm">
            <span class="text-xs text-zinc-400 font-medium uppercase tracking-wider block mb-1">Pending Projects</span>
            <div class="text-2xl sm:text-3xl font-black text-amber-500">{{ $pendingProjectsCount }}</div>
            <span class="text-[11px] text-zinc-400 mt-1 block">Awaiting editor review</span>
        </div>

        <div class="bg-white p-5 rounded-xl border border-zinc-200 shadow-sm">
            <span class="text-xs text-zinc-400 font-medium uppercase tracking-wider block mb-1">Approved Products</span>
            <div class="text-2xl sm:text-3xl font-black text-blue-600">{{ $approvedProductsCount }}</div>
            <span class="text-[11px] text-zinc-400 mt-1 block">Published in catalog</span>
        </div>

        <div class="bg-white p-5 rounded-xl border border-zinc-200 shadow-sm">
            <span class="text-xs text-zinc-400 font-medium uppercase tracking-wider block mb-1">Pending Products</span>
            <div class="text-2xl sm:text-3xl font-black text-amber-500">{{ $pendingProductsCount }}</div>
            <span class="text-[11px] text-zinc-400 mt-1 block">Awaiting editor review</span>
        </div>
    </div>

    <!-- Recent Project Submissions Section -->
    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-zinc-200 flex items-center justify-between">
            <h3 class="text-xs sm:text-sm font-bold text-zinc-900 uppercase tracking-wide">My Project Submissions</h3>
            <a href="{{ route('author.projects.create') }}" class="text-xs font-bold text-emerald-700 hover:underline">+ New Project</a>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-zinc-200">
            @forelse($recentProjects as $p)
                <div class="p-4 space-y-2">
                    <div class="flex items-start gap-3">
                        <img src="{{ $p->featured_image }}" class="w-12 h-12 rounded object-cover border border-zinc-200 shrink-0">
                        <div class="flex-1 min-w-0">
                            <strong class="text-zinc-900 font-semibold text-xs block leading-snug truncate">{{ $p->title }}</strong>
                            <span class="text-[10px] text-zinc-400 block">{{ $p->category }} &bull; {{ $p->country }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] pt-1">
                        @if($p->status === 'approved')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Live
                            </span>
                        @elseif($p->status === 'pending')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                <span class="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span> Pending Review
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                Rejected
                            </span>
                        @endif

                        <div class="flex items-center gap-2">
                            @if($p->status === 'approved')
                                <a href="{{ route('projects.show', $p->slug) }}" target="_blank" class="text-blue-600 font-semibold hover:underline">View</a>
                            @endif
                            <a href="{{ route('author.projects.edit', $p->id) }}" class="text-zinc-700 font-semibold hover:underline">Edit</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-zinc-500 text-xs">
                    You haven't submitted any architectural projects yet.
                    <a href="{{ route('author.projects.create') }}" class="text-emerald-700 font-bold block mt-1">+ Submit your first project</a>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 uppercase font-semibold text-[10px]">
                    <tr>
                        <th class="px-6 py-3">Project Title</th>
                        <th class="px-6 py-3">Typology / Country</th>
                        <th class="px-6 py-3">Review Status</th>
                        <th class="px-6 py-3">Date Submitted</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 text-zinc-700">
                    @forelse($recentProjects as $p)
                        <tr class="hover:bg-zinc-50">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img src="{{ $p->featured_image }}" class="w-10 h-10 rounded object-cover border border-zinc-200">
                                <div>
                                    <strong class="text-zinc-900 block">{{ $p->title }}</strong>
                                    <span class="text-[11px] text-zinc-400">{{ Str::limit($p->excerpt, 60) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="block font-medium">{{ $p->category }}</span>
                                <span class="text-[11px] text-zinc-400">{{ $p->country }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($p->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Approved & Live
                                    </span>
                                @elseif($p->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending Admin Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-zinc-500">
                                {{ $p->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if($p->status === 'approved')
                                    <a href="{{ route('projects.show', $p->slug) }}" target="_blank" class="text-blue-600 hover:underline">View Live</a>
                                @endif
                                <a href="{{ route('author.projects.edit', $p->id) }}" class="text-zinc-700 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-zinc-500">
                                You haven't submitted any architectural projects yet.
                                <a href="{{ route('author.projects.create') }}" class="text-emerald-700 font-bold hover:underline block mt-1">Submit your first project now &rarr;</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Product Submissions Section -->
    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-zinc-200 flex items-center justify-between">
            <h3 class="text-xs sm:text-sm font-bold text-zinc-900 uppercase tracking-wide">My Product Specifications</h3>
            <a href="{{ route('author.products.create') }}" class="text-xs font-bold text-blue-700 hover:underline">+ New Product</a>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-zinc-200">
            @forelse($recentProducts as $prod)
                <div class="p-4 space-y-2">
                    <div class="flex items-start gap-3">
                        <img src="{{ $prod->featured_image }}" class="w-10 h-10 rounded object-cover border border-zinc-200 shrink-0">
                        <div class="flex-1 min-w-0">
                            <strong class="text-zinc-900 font-semibold text-xs block leading-snug truncate">{{ $prod->title }}</strong>
                            <span class="text-[10px] text-zinc-500 uppercase font-bold block">{{ $prod->manufacturer }} &bull; {{ $prod->category }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] pt-1">
                        @if($prod->status === 'approved')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Live
                            </span>
                        @elseif($prod->status === 'pending')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                <span class="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span> Pending Review
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                Rejected
                            </span>
                        @endif

                        <div class="flex items-center gap-2">
                            @if($prod->status === 'approved')
                                <a href="{{ route('products.show', $prod->slug) }}" target="_blank" class="text-blue-600 font-semibold hover:underline">View</a>
                            @endif
                            <a href="{{ route('author.products.edit', $prod->id) }}" class="text-zinc-700 font-semibold hover:underline">Edit</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-zinc-500 text-xs">
                    No architectural products submitted yet.
                    <a href="{{ route('author.products.create') }}" class="text-blue-700 font-bold block mt-1">+ Submit an architectural product</a>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 uppercase font-semibold text-[10px]">
                    <tr>
                        <th class="px-6 py-3">Product & Manufacturer</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Website Link</th>
                        <th class="px-6 py-3">Review Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 text-zinc-700">
                    @forelse($recentProducts as $prod)
                        <tr class="hover:bg-zinc-50">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img src="{{ $prod->featured_image }}" class="w-10 h-10 rounded object-cover border border-zinc-200">
                                <div>
                                    <strong class="text-zinc-900 block">{{ $prod->title }}</strong>
                                    <span class="text-[11px] text-zinc-500 uppercase font-semibold">{{ $prod->manufacturer }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $prod->category }}</td>
                            <td class="px-6 py-4">
                                @if($prod->website_url)
                                    <a href="{{ $prod->website_url }}" target="_blank" class="text-blue-600 hover:underline truncate max-w-xs block">{{ $prod->website_url }}</a>
                                @else
                                    <span class="text-zinc-400">None</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($prod->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Approved & Live
                                    </span>
                                @elseif($prod->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        Pending Admin Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if($prod->status === 'approved')
                                    <a href="{{ route('products.show', $prod->slug) }}" target="_blank" class="text-blue-600 hover:underline">View Live</a>
                                @endif
                                <a href="{{ route('author.products.edit', $prod->id) }}" class="text-zinc-700 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-zinc-500">
                                No architectural products submitted yet.
                                <a href="{{ route('author.products.create') }}" class="text-blue-700 font-bold hover:underline block mt-1">Submit an architectural product now &rarr;</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
