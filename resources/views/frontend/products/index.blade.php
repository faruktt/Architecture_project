@extends('layouts.frontend')

@section('title', 'Architecture Products | Specifications, Equipment & Building Materials')

@section('content')
<div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section (Matching Image 2 Exactly) -->
    <div class="border-b border-zinc-200 pb-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-baseline gap-2 mb-2">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900">
                Architecture Products
            </h1>
            <span class="text-zinc-500 font-normal text-xl sm:text-2xl">| {{ number_format($totalCount) }} results</span>
        </div>
        <p class="text-sm text-zinc-600 max-w-3xl leading-relaxed">
            Top products for architects recently published on ArchDaily & nook. The most inspiring materials, equipment, and more, from the world’s best manufacturers.
        </p>
    </div>

    <!-- Filter Pills Bar & View Switcher (Exact match to Image 2 Filter Strip) -->
    <div class="flex flex-wrap items-center justify-between gap-3 pb-6 border-b border-zinc-200 mb-8">

        <!-- Filter Buttons / Pills -->
        <div class="flex flex-wrap items-center gap-2 text-xs">

            <!-- Category Filter Dropdown -->
            <div class="relative group">
                <button type="button" class="px-3.5 py-1.5 border border-zinc-300 rounded hover:border-black transition-colors flex items-center gap-1.5 font-medium {{ $category ? 'bg-zinc-900 text-white border-zinc-900' : 'bg-white text-zinc-800' }}">
                    <span>Categories{{ $category ? ': ' . $category : '' }}</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="absolute left-0 mt-1 w-56 bg-white border border-zinc-200 rounded shadow-xl py-1 z-30 hidden group-hover:block">
                    <a href="{{ route('products.index', array_merge(request()->query(), ['category' => null])) }}" class="block px-3 py-1.5 text-xs hover:bg-zinc-100 {{ !$category ? 'font-bold' : '' }}">All Categories</a>
                    @foreach($categories as $cat)
                        <a href="{{ route('products.index', array_merge(request()->query(), ['category' => $cat])) }}" class="block px-3 py-1.5 text-xs hover:bg-zinc-100 {{ $category === $cat ? 'font-bold text-black bg-zinc-50' : 'text-zinc-700' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Country/Region Filter Dropdown -->
            <div class="relative group">
                <button type="button" class="px-3.5 py-1.5 border border-zinc-300 rounded hover:border-black transition-colors flex items-center gap-1.5 font-medium {{ $country ? 'bg-zinc-900 text-white border-zinc-900' : 'bg-white text-zinc-800' }}">
                    <span>Country/Region{{ $country ? ': ' . $country : '' }}</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="absolute left-0 mt-1 w-48 bg-white border border-zinc-200 rounded shadow-xl py-1 z-30 hidden group-hover:block">
                    <a href="{{ route('products.index', array_merge(request()->query(), ['country' => null])) }}" class="block px-3 py-1.5 text-xs hover:bg-zinc-100 {{ !$country ? 'font-bold' : '' }}">All Regions</a>
                    @foreach($countries as $cnt)
                        <a href="{{ route('products.index', array_merge(request()->query(), ['country' => $cnt])) }}" class="block px-3 py-1.5 text-xs hover:bg-zinc-100 {{ $country === $cnt ? 'font-bold text-black bg-zinc-50' : 'text-zinc-700' }}">
                            {{ $cnt }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Show Only BIM Files Toggle Pill -->
            <a href="{{ route('products.index', array_merge(request()->query(), ['bim_only' => $bimOnly ? null : 1])) }}"
               class="px-3.5 py-1.5 border rounded transition-colors flex items-center gap-1.5 font-medium {{ $bimOnly ? 'bg-black text-white border-black' : 'border-zinc-300 bg-white text-zinc-800 hover:border-black' }}">
                <span class="w-2 h-2 rounded-full {{ $bimOnly ? 'bg-emerald-400' : 'bg-zinc-400' }}"></span>
                Show Only BIM Files
            </a>

            <!-- Manufacturers Dropdown -->
            <div class="relative group">
                <button type="button" class="px-3.5 py-1.5 border border-zinc-300 rounded hover:border-black transition-colors flex items-center gap-1.5 font-medium {{ $manufacturer ? 'bg-zinc-900 text-white border-zinc-900' : 'bg-white text-zinc-800' }}">
                    <span>Manufacturers{{ $manufacturer ? ': ' . $manufacturer : '' }}</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="absolute left-0 mt-1 w-52 bg-white border border-zinc-200 rounded shadow-xl py-1 z-30 hidden group-hover:block max-h-60 overflow-y-auto">
                    <a href="{{ route('products.index', array_merge(request()->query(), ['manufacturer' => null])) }}" class="block px-3 py-1.5 text-xs hover:bg-zinc-100">All Manufacturers</a>
                    @foreach($manufacturers as $mfg)
                        <a href="{{ route('products.index', array_merge(request()->query(), ['manufacturer' => $mfg])) }}" class="block px-3 py-1.5 text-xs hover:bg-zinc-100 {{ $manufacturer === $mfg ? 'font-bold' : '' }}">
                            {{ $mfg }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Additional Filter Pills (Properties, Article Type, Use, Materials) matching Image 2 -->
            <button class="px-3.5 py-1.5 border border-zinc-300 rounded bg-white text-zinc-700 hover:border-black transition-colors font-medium">Properties</button>
            <button class="px-3.5 py-1.5 border border-zinc-300 rounded bg-white text-zinc-700 hover:border-black transition-colors font-medium">Article Type</button>
            <button class="px-3.5 py-1.5 border border-zinc-300 rounded bg-white text-zinc-700 hover:border-black transition-colors font-medium">Use</button>
            <button class="px-3.5 py-1.5 border border-zinc-300 rounded bg-white text-zinc-700 hover:border-black transition-colors font-medium">Materials</button>

            @if($category || $country || $manufacturer || $bimOnly || $search)
                <a href="{{ route('products.index') }}" class="text-xs font-semibold text-red-600 hover:underline ml-2">Clear All</a>
            @endif
        </div>

        <!-- Right Side: View Grid / List Toggle & Author Submit Product shortcut -->
        <div class="flex items-center space-x-3">
            @auth('author')
                <a href="{{ route('author.products.create') }}" class="text-xs font-semibold bg-zinc-900 text-white hover:bg-black px-3 py-1.5 rounded transition-colors flex items-center gap-1">
                    <span>+ Submit Product</span>
                </a>
            @endauth

            <div class="flex items-center border border-zinc-300 rounded p-0.5 bg-white text-zinc-700">
                <button class="p-1 hover:text-black rounded bg-zinc-100" title="Grid View">
                    <!-- 3x3 Grid Icon -->
                    <svg class="w-4 h-4 text-black" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button class="p-1 hover:text-black text-zinc-400" title="List View">
                    <!-- List lines Icon -->
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>

    </div>

    <!-- Products Grid (Matching Image 2 Layout) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($products as $product)
            <div class="group flex flex-col justify-between">
                <div>
                    <!-- Product Image -->
                    <div class="aspect-[16/11] overflow-hidden bg-zinc-100 border border-zinc-200 mb-3 relative">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ $product->featured_image }}"
                                 alt="{{ $product->title }}"
                                 class="w-full h-full object-cover editorial-image-hover">
                        </a>
                        @if($product->has_bim)
                            <div class="absolute top-2 right-2">
                                <span class="bg-black/80 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow">
                                    BIM
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Manufacturer in Uppercase (Image 2 style) -->
                    <div class="text-[11px] font-bold tracking-wider text-zinc-500 uppercase mb-1">
                        {{ $product->manufacturer }}
                    </div>

                    <!-- Product Title (Image 2 style) -->
                    <h3 class="text-sm font-bold text-zinc-900 group-hover:text-blue-900 transition-colors leading-snug line-clamp-2">
                        <a href="{{ route('products.show', $product->slug) }}">
                            {{ $product->title }}
                        </a>
                    </h3>
                </div>

                <div class="pt-2 text-xs text-zinc-500 flex items-center justify-between">
                    <span>{{ $product->category }}</span>
                    @if($product->website_url)
                        <span class="text-blue-600 text-[11px] font-medium flex items-center gap-0.5">
                            Official Site &nearr;
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-zinc-50 border border-zinc-200 rounded">
                <svg class="w-12 h-12 text-zinc-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                <h3 class="text-base font-semibold text-zinc-800">No products match your filter criteria</h3>
                <p class="text-xs text-zinc-500 mt-1">Try selecting a different category or clearing filters.</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-4 px-4 py-2 bg-black text-white text-xs font-semibold rounded">Reset All Filters</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12 pt-6 border-t border-zinc-200">
        {{ $products->links() }}
    </div>

</div>
@endsection
