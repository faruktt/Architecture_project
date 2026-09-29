@extends('layouts.frontend')

@section('title', 'Architectural Projects | nook MAGAZINE')

@section('content')
<div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-zinc-200 pb-6 mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Curated Architecture Projects</h1>
            <p class="text-sm text-zinc-500 mt-1">Discover built projects, interior masterworks, and spatial concepts across Asia and the world.</p>
        </div>

        @auth('author')
            <a href="{{ route('author.projects.create') }}" class="px-4 py-2 bg-black text-white hover:bg-zinc-800 text-xs font-semibold rounded transition-colors self-start md:self-auto flex items-center gap-1.5">
                <span class="text-emerald-400 font-bold">+</span> Submit Your Project
            </a>
        @endauth
    </div>

    <!-- Filters & Search Bar -->
    <form action="{{ route('projects.index') }}" method="GET" class="mb-8 grid grid-cols-1 sm:grid-cols-4 gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search projects by keyword, architect, or city..." class="sm:col-span-2 px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">

        <select name="category" onchange="this.form.submit()" class="px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black bg-white">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>

        <select name="country" onchange="this.form.submit()" class="px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black bg-white">
            <option value="">All Countries</option>
            @foreach($countries as $cnt)
                <option value="{{ $cnt }}" {{ $country === $cnt ? 'selected' : '' }}>{{ $cnt }}</option>
            @endforeach
        </select>
    </form>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($projects as $proj)
            <div class="group flex flex-col justify-between border-b border-zinc-100 pb-6">
                <div>
                    <div class="aspect-[16/11] overflow-hidden bg-zinc-100 border border-zinc-200 mb-3 relative">
                        <a href="{{ route('projects.show', $proj->slug) }}">
                            <img src="{{ $proj->featured_image }}" alt="{{ $proj->title }}" class="w-full h-full object-cover editorial-image-hover">
                        </a>
                        <div class="absolute bottom-2 left-2">
                            <span class="badge-editorial px-2.5 py-1 text-black font-semibold text-[10px]">
                                {{ $proj->country }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-[11px] text-zinc-400 font-medium mb-1">
                        <span>{{ $proj->category }}</span>
                        @if($proj->city)
                            <span>&bull;</span>
                            <span>{{ $proj->city }}</span>
                        @endif
                    </div>

                    <h3 class="text-base font-semibold text-zinc-900 group-hover:text-black transition-colors leading-snug">
                        <a href="{{ route('projects.show', $proj->slug) }}">{{ $proj->title }}</a>
                    </h3>

                    <p class="text-xs text-zinc-600 line-clamp-2 mt-1 leading-relaxed">
                        {{ $proj->excerpt }}
                    </p>
                </div>

                @if($proj->author)
                    <div class="pt-3 border-t border-zinc-100 mt-3 flex items-center justify-between text-xs">
                        <a href="{{ route('author.profile', $proj->author->username) }}" class="flex items-center gap-2 group/author">
                            <img src="{{ $proj->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($proj->author->name) }}" class="w-5 h-5 rounded-full object-cover">
                            <span class="font-medium text-zinc-700 group-hover/author:text-black">{{ $proj->author->name }}</span>
                        </a>
                        <span class="text-[11px] text-zinc-400">{{ $proj->views_count }} views</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-zinc-50 border border-zinc-200 rounded">
                <p class="text-zinc-600 text-sm">No architecture projects found matching your filter criteria.</p>
                <a href="{{ route('projects.index') }}" class="inline-block mt-3 px-4 py-2 bg-black text-white text-xs font-semibold rounded">Clear Filters</a>
            </div>
        @endforelse
    </div>

    <div class="mt-12 pt-6 border-t border-zinc-200">
        {{ $projects->links() }}
    </div>

</div>
@endsection
