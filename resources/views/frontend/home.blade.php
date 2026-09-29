@extends('layouts.frontend')

@section('title', 'nook MAGAZINE | Architectural Projects, Interiors & Design Showcase')

@section('content')
<div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if($selectedCountry)
        <div class="mb-6 flex items-center justify-between bg-zinc-100 px-4 py-2.5 rounded border border-zinc-200">
            <div class="text-xs text-zinc-700">
                Filtering architecture projects in: <strong class="text-black text-sm uppercase tracking-wide">{{ $selectedCountry }}</strong>
            </div>
            <a href="{{ route('home') }}" class="text-xs font-semibold text-zinc-900 hover:text-red-600 flex items-center gap-1">
                Show All Regions &rarr;
            </a>
        </div>
    @endif

    <!-- Two-column Magazine Layout (Direct match of Image 1) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">

        <!-- ================= LEFT COLUMN: HERO & EDITORIAL STORIES (approx 68% / 8 cols) ================= -->
        <div class="lg:col-span-8 space-y-10">

            <!-- Top Row: "Project of the week" and "Nook Spotlight" (Image 1 top left & center) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Card 1: Project of the week -->
                @if($projectOfTheWeek)
                    <div class="group relative overflow-hidden bg-zinc-100 border border-zinc-200">
                        <a href="{{ route('projects.show', $projectOfTheWeek->slug) }}" class="block">
                            <div class="aspect-[16/11] overflow-hidden relative">
                                <img src="{{ $projectOfTheWeek->featured_image }}"
                                     alt="{{ $projectOfTheWeek->title }}"
                                     class="w-full h-full object-cover editorial-image-hover">
                                <!-- Badge bottom left -->
                                <div class="absolute bottom-3 left-3">
                                    <span class="badge-editorial px-3 py-1.5 inline-block text-black shadow-sm">
                                        Project of the week
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                <!-- Card 2: Nook Spotlight -->
                @if($nookSpotlight)
                    <div class="group relative overflow-hidden bg-zinc-100 border border-zinc-200">
                        <a href="{{ route('projects.show', $nookSpotlight->slug) }}" class="block">
                            <div class="aspect-[16/11] overflow-hidden relative">
                                <img src="{{ $nookSpotlight->featured_image }}"
                                     alt="{{ $nookSpotlight->title }}"
                                     class="w-full h-full object-cover editorial-image-hover">
                                <!-- Badge bottom left -->
                                <div class="absolute bottom-3 left-3">
                                    <span class="badge-editorial px-3 py-1.5 inline-block text-black shadow-sm">
                                        Nook Spotlight
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Main Editorial Hero Feature (Image 1 Primary Article) -->
            @if($heroStory)
                <article class="border-b border-zinc-200 pb-10">
                    <!-- Title & Meta -->
                    <div class="mb-4">
                        <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight text-zinc-900 leading-snug hover:text-zinc-700 transition-colors">
                            <a href="{{ route('projects.show', $heroStory->slug) }}">
                                {{ $heroStory->title }}
                            </a>
                        </h2>
                        <div class="text-xs text-zinc-500 mt-2 flex items-center gap-2">
                            <span>{{ $heroStory->created_at ? $heroStory->created_at->diffForHumans() : 'Recently' }}</span>
                            <span>|</span>
                            <span class="text-zinc-600">{{ $heroStory->collaboration ?? 'In Collaboration' }}</span>
                            @if($heroStory->author)
                                <span>|</span>
                                <a href="{{ route('author.profile', $heroStory->author->username) }}" class="text-zinc-800 font-semibold hover:underline">
                                    {{ $heroStory->author->name }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Hero Main Photo -->
                    <div class="overflow-hidden border border-zinc-200 bg-zinc-100 mb-6">
                        <a href="{{ route('projects.show', $heroStory->slug) }}">
                            <img src="{{ $heroStory->featured_image }}"
                                 alt="{{ $heroStory->title }}"
                                 class="w-full max-h-[500px] object-cover hover:opacity-95 transition-opacity">
                        </a>
                    </div>

                    <!-- Lead Paragraph Excerpt (Matching text in Image 1) -->
                    <p class="text-sm md:text-[15px] leading-relaxed text-zinc-700 font-normal">
                        {{ $heroStory->excerpt }}
                    </p>

                    <div class="mt-4">
                        <a href="{{ route('projects.show', $heroStory->slug) }}" class="inline-flex items-center text-xs font-bold text-black uppercase tracking-wider hover:underline">
                            Read full story &rarr;
                        </a>
                    </div>
                </article>
            @endif

            <!-- Lower Feed: More Editorial Articles / Curated Projects -->
            <div class="space-y-8 pt-2">
                @foreach($editorialStories as $story)
                    <article class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-center border-b border-zinc-100 pb-6 group">
                        <div class="sm:col-span-5 aspect-[16/10] overflow-hidden bg-zinc-100 border border-zinc-200">
                            <a href="{{ route('projects.show', $story->slug) }}">
                                <img src="{{ $story->featured_image }}"
                                     alt="{{ $story->title }}"
                                     class="w-full h-full object-cover editorial-image-hover">
                            </a>
                        </div>
                        <div class="sm:col-span-7 space-y-2">
                            <div class="flex items-center gap-2 text-[11px] text-zinc-400 font-medium">
                                <span class="bg-zinc-100 text-zinc-800 px-2 py-0.5 rounded font-semibold">{{ $story->country }}</span>
                                <span>&bull;</span>
                                <span>{{ $story->category }}</span>
                                <span>&bull;</span>
                                <span>{{ $story->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 group-hover:text-black transition-colors leading-snug">
                                <a href="{{ route('projects.show', $story->slug) }}">
                                    {{ $story->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-zinc-600 line-clamp-2 leading-relaxed">
                                {{ $story->excerpt }}
                            </p>
                            @if($story->author)
                                <div class="text-[11px] text-zinc-500 pt-1">
                                    By <a href="{{ route('author.profile', $story->author->username) }}" class="font-medium text-zinc-800 hover:underline">{{ $story->author->name }}</a>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

        </div>


        <!-- ================= RIGHT COLUMN: PROPERTY SELL POST & PRODUCTS CATALOG (approx 32% / 4 cols) ================= -->
        <div class="lg:col-span-4 space-y-10">

            <!-- Section 1: PROPERTY SELL POST (Exact match to Image 1 Right Column) -->
            <div>
                <!-- Heading -->
                <div class="border-b-2 border-black pb-1 mb-5">
                    <h3 class="text-base font-bold tracking-tight text-black uppercase">
                        PROPERTY SELL POST
                    </h3>
                </div>

                <!-- Property Cards List (Products Only) -->
                <div class="space-y-6">
                    @forelse($propertySellPosts as $prop)
                        <div class="group">
                            <div class="relative overflow-hidden bg-zinc-100 border border-zinc-200 mb-2">
                                <a href="{{ route('products.show', $prop->slug) }}" class="block">
                                    <div class="aspect-[4/3] overflow-hidden">
                                        <img src="{{ $prop->featured_image }}"
                                             alt="{{ $prop->title }}"
                                             class="w-full h-full object-cover editorial-image-hover">
                                    </div>
                                    <!-- Country/Region Badge on Image -->
                                    <div class="absolute bottom-3 left-3">
                                        <span class="badge-editorial px-3 py-1.5 inline-block text-black shadow-sm font-semibold">
                                            {{ $prop->country_region ?? $prop->manufacturer }}
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="flex items-center justify-between pt-1">
                                <a href="{{ route('products.show', $prop->slug) }}" class="text-xs font-bold text-zinc-900 hover:text-black transition-colors">
                                    Read more &raquo;
                                </a>
                                @if($prop->price)
                                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                        {{ $prop->price }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-zinc-500">No products currently selected for property sell post.</p>
                    @endforelse
                </div>
            </div>

            <!-- Section 2: Products Catalog Teaser Box (Exact match to Image 1 Right Box) -->
            <div>
                <div class="relative overflow-hidden bg-zinc-900 border border-zinc-800 text-white group">
                    <a href="{{ route('products.index') }}" class="block">
                        <div class="aspect-[4/3] relative overflow-hidden">
                            <img src="{{ $catalogProduct->featured_image ?? 'https://images.unsplash.com/photo-1517991104123-1d56a6e81ed9?auto=format&fit=crop&w=800&q=80' }}"
                                 alt="Products Catalog"
                                 class="w-full h-full object-cover opacity-85 group-hover:scale-105 transition-transform duration-500">

                            <!-- Top-Left Badge: "Products Catalog" -->
                            <div class="absolute top-3 left-3">
                                <span class="badge-editorial px-3 py-1 text-black font-semibold text-[11px] shadow">
                                    Products Catalog
                                </span>
                            </div>

                            <!-- Dramatic overlay text: "Experience The Glow of Perfection" -->
                            <div class="absolute inset-0 flex flex-col justify-center px-6 text-center bg-black/30 backdrop-brightness-90">
                                <span class="text-xs uppercase tracking-[0.25em] text-amber-200 font-light mb-1">Architectural Lighting</span>
                                <h4 class="text-xl sm:text-2xl font-serif italic text-white drop-shadow">
                                    Experience The <span class="text-amber-400 not-italic font-bold">Glow</span> of Perfection
                                </h4>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- "See more »" Link below the card -->
                <div class="pt-2">
                    <a href="{{ route('products.index') }}" class="text-xs font-bold text-zinc-900 hover:text-black transition-colors">
                        See more &raquo;
                    </a>
                </div>
            </div>

            <!-- Section 3: Latest Architectural News / Articles -->
            @if($articles->count() > 0)
                <div class="border-t border-zinc-200 pt-6">
                    <div class="border-b border-zinc-200 pb-2 mb-4 flex items-center justify-between">
                        <h4 class="text-xs font-bold text-black uppercase tracking-wider">Latest Articles & News</h4>
                        <a href="{{ route('articles.index') }}" class="text-[11px] text-zinc-500 hover:text-black">View all &rarr;</a>
                    </div>
                    <div class="space-y-4">
                        @foreach($articles as $art)
                            <div class="group">
                                <div class="text-[10px] text-zinc-400 uppercase font-semibold">{{ $art->category }}</div>
                                <h5 class="text-xs font-semibold text-zinc-800 group-hover:text-black transition-colors leading-snug">
                                    <a href="{{ route('articles.show', $art->slug) }}">{{ $art->title }}</a>
                                </h5>
                                <p class="text-[11px] text-zinc-500 line-clamp-1 mt-0.5">{{ $art->summary }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
