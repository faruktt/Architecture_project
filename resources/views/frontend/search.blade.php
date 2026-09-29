@extends('layouts.frontend')

@section('title', 'Search: ' . ($query ?: 'Explore') . ' | nook MAGAZINE')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Search Header -->
    <div class="max-w-3xl mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            @if($query)
                Search Results for <span class="text-amber-600">"{{ $query }}"</span>
            @else
                Explore nook Editorial Index
            @endif
        </h1>
        <p class="text-xs text-slate-500 mt-1">Found {{ $totalCount }} matching item{{ $totalCount === 1 ? '' : 's' }} across architecture projects, building systems, and news articles.</p>

        <!-- Search Bar with Button -->
        <form action="{{ route('search') }}" method="GET" class="mt-4 flex items-center gap-2">
            <div class="relative flex-grow">
                <input type="text" name="q" value="{{ $query }}" placeholder="Search projects, materials, architects..." class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                Search
            </button>
        </form>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3 mb-6 text-xs">
        <a href="{{ route('search', ['q' => $query, 'tab' => 'all']) }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-all {{ $tab === 'all' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900' }}">
            All Results ({{ $totalCount }})
        </a>
        <a href="{{ route('search', ['q' => $query, 'tab' => 'projects']) }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-all {{ $tab === 'projects' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900' }}">
            Projects ({{ $projects->count() }})
        </a>
        <a href="{{ route('search', ['q' => $query, 'tab' => 'products']) }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-all {{ $tab === 'products' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900' }}">
            Products & Materials ({{ $products->count() }})
        </a>
        <a href="{{ route('search', ['q' => $query, 'tab' => 'articles']) }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-all {{ $tab === 'articles' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900' }}">
            Articles ({{ $articles->count() }})
        </a>
    </div>

    <!-- Results Grid -->
    @if($totalCount === 0)
        <div class="p-12 text-center bg-white border border-slate-200 rounded-2xl">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <h3 class="text-sm font-bold text-slate-800">No results found for "{{ $query }}"</h3>
            <p class="text-xs text-slate-500 mt-1">Try searching with a different term, city name, or material type.</p>
        </div>
    @else
        <!-- Projects Section -->
        @if($projects->count() > 0)
            <div class="mb-10">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Architecture Projects</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($projects as $p)
                        <a href="{{ route('projects.show', $p->slug) }}" class="group block bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs hover:shadow-md transition-all">
                            <div class="aspect-[16/10] overflow-hidden bg-slate-100">
                                <img src="{{ $p->featured_image }}" alt="{{ $p->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-4">
                                <span class="text-[10px] uppercase font-bold text-amber-600 tracking-wider block mb-1">{{ $p->category }} &bull; {{ $p->country }}</span>
                                <h3 class="text-xs font-bold text-slate-900 line-clamp-1 group-hover:text-amber-700 transition-colors">{{ $p->title }}</h3>
                                <p class="text-[11px] text-slate-500 line-clamp-2 mt-1">{{ $p->excerpt }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Products Section -->
        @if($products->count() > 0)
            <div class="mb-10">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <span>Products & Materials</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($products as $prod)
                        <a href="{{ route('products.show', $prod->slug) }}" class="group block bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs hover:shadow-md transition-all">
                            <div class="aspect-square overflow-hidden bg-slate-100 p-4 flex items-center justify-center">
                                <img src="{{ $prod->featured_image }}" alt="{{ $prod->title }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-4 border-t border-slate-100">
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1">{{ $prod->manufacturer }}</span>
                                <h3 class="text-xs font-bold text-slate-900 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $prod->title }}</h3>
                                <span class="text-[11px] text-slate-500 block mt-1">{{ $prod->category }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Articles Section -->
        @if($articles->count() > 0)
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    <span>News & Editorial Articles</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach($articles as $art)
                        <a href="{{ route('articles.show', $art->slug) }}" class="group block bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs hover:shadow-md transition-all">
                            <div class="aspect-[16/9] overflow-hidden bg-slate-100">
                                <img src="{{ $art->image }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-4">
                                <span class="text-[10px] uppercase font-bold text-rose-600 tracking-wider block mb-1">{{ $art->category }}</span>
                                <h3 class="text-xs font-bold text-slate-900 line-clamp-2 group-hover:text-rose-700 transition-colors">{{ $art->title }}</h3>
                                <p class="text-[11px] text-slate-500 line-clamp-2 mt-1">{{ $art->summary }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

</div>
@endsection
