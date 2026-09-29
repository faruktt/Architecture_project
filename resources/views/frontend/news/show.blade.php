@extends('layouts.frontend')

@section('title', $newsItem->title . ' | Architecture News')
@section('meta_description', Str::limit(strip_tags($newsItem->summary), 160))

@section('content')
<div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-1.5 text-xs text-zinc-500 mb-3">
        <a href="{{ route('home') }}" class="hover:text-zinc-900 transition-colors">ArchDaily</a>
        <span>&rsaquo;</span>
        <a href="{{ route('news.index') }}" class="hover:text-zinc-900 transition-colors">Architecture News</a>
        <span>&rsaquo;</span>
        <span class="text-zinc-800 font-medium truncate max-w-xs">{{ $newsItem->category }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

        <!-- ================= NEWS STORY BODY (8 COLS) ================= -->
        <main class="lg:col-span-8 space-y-6">
            <header class="space-y-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-blue-900 uppercase tracking-wider bg-blue-50 px-2 py-0.5 rounded">
                        {{ $newsItem->category }}
                    </span>
                    @if($newsItem->badge_text)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-blue-600 text-white rounded-full text-[11px] font-bold shadow-2xs">
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            <span>{{ $newsItem->badge_text }}</span>
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-zinc-900 leading-tight">
                    {{ $newsItem->title }}
                </h1>

                <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-zinc-500 py-3 border-y border-zinc-200">
                    <div class="flex items-center gap-3">
                        <span>Reported by <strong class="text-zinc-800">{{ $newsItem->author_name }}</strong></span>
                        <span>&bull;</span>
                        <span>{{ $newsItem->published_at ? $newsItem->published_at->format('F d, Y') : $newsItem->created_at->format('F d, Y') }}</span>
                    </div>

                    <div class="flex items-center gap-3">
                        @if($newsItem->has_audio)
                            <span class="inline-flex items-center gap-1.5 text-zinc-700 bg-zinc-100 px-2.5 py-1 rounded text-xs font-medium">
                                <svg class="w-3.5 h-3.5 text-zinc-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
                                <span>Audio available</span>
                            </span>
                        @endif

                        <button onclick="toggleSave(this)" class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#003882] hover:bg-[#002860] text-white rounded text-xs font-semibold cursor-pointer">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                            <span>Save</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <div class="relative rounded-sm overflow-hidden bg-zinc-100 border border-zinc-200">
                <img src="{{ $newsItem->image }}" alt="{{ $newsItem->title }}" class="w-full aspect-[16/10] sm:aspect-[16/9] object-cover">
                @if($newsItem->badge_text)
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/95 backdrop-blur-xs text-[#003882] text-xs font-bold rounded-full border border-zinc-300 shadow-sm">
                            <svg class="w-3 h-3 fill-current text-blue-700" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            <span>{{ $newsItem->badge_text }}</span>
                        </span>
                    </div>
                @endif
            </div>

            <!-- Lead Excerpt -->
            <div class="text-base sm:text-lg font-medium text-zinc-800 leading-relaxed italic border-l-4 border-blue-600 pl-4 py-1">
                {{ $newsItem->summary }}
            </div>

            <!-- Rich Body Content with Text and Embedded Photos -->
            <div class="prose prose-zinc max-w-none text-zinc-800 text-sm sm:text-base leading-relaxed space-y-4 pt-4 [&_img]:rounded-xl [&_img]:my-6 [&_img]:max-w-full [&_img]:shadow-md [&_img]:mx-auto [&_p]:mb-4 [&_h2]:text-xl sm:[&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-zinc-900 [&_h2]:mt-8 [&_h2]:mb-3 [&_blockquote]:border-l-4 [&_blockquote]:border-zinc-900 [&_blockquote]:pl-4 [&_blockquote]:italic">
                {!! $newsItem->content !!}
            </div>

            <!-- Gallery Strip if available -->
            @if(is_array($newsItem->gallery) && count($newsItem->gallery) > 0)
                <div class="pt-8 border-t border-zinc-200 space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-900">Event & Documentation Gallery</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($newsItem->gallery as $gPic)
                            <a href="{{ $gPic }}" target="_blank" class="aspect-square bg-zinc-100 rounded-sm overflow-hidden border border-zinc-200 group block">
                                <img src="{{ $gPic }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Back navigation -->
            <div class="pt-8 border-t border-zinc-200 flex items-center justify-between">
                <a href="{{ route('news.index') }}" class="text-xs font-bold text-blue-900 hover:underline flex items-center gap-1">
                    <span>&larr; Back to Architecture News</span>
                </a>
            </div>
        </main>

        <!-- ================= RIGHT SIDEBAR (4 COLS) ================= -->
        <div class="lg:col-span-4 lg:pl-4">
            @include('frontend.partials.editorial-sidebar', ['selectedProducts' => $selectedProducts ?? collect([])])
        </div>

    </div>
</div>

<script>
function toggleSave(btn) {
    if (btn.classList.contains('bg-[#003882]')) {
        btn.classList.remove('bg-[#003882]');
        btn.classList.add('bg-emerald-700');
        btn.querySelector('span').textContent = 'Saved';
    } else {
        btn.classList.remove('bg-emerald-700');
        btn.classList.add('bg-[#003882]');
        btn.querySelector('span').textContent = 'Save';
    }
}
</script>
@endsection
