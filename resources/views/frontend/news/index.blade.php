@extends('layouts.frontend')

@section('title', 'Architecture News | nook Architecture')

@section('content')
<div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Breadcrumb (Image 2 style) -->
    <nav class="flex items-center space-x-1.5 text-xs text-zinc-500 mb-2">
        <a href="{{ route('home') }}" class="hover:text-zinc-900 transition-colors">ArchDaily</a>
        <span>&rsaquo;</span>
        <span class="text-zinc-800 font-medium">Architecture News</span>
    </nav>

    <!-- Page Title (Image 2 style) -->
    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-900 mb-6">
        Architecture News
    </h1>

    <!-- 2-Column News Feed Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

        <!-- ================= MAIN NEWS FEED (8 COLS) ================= -->
        <main class="lg:col-span-8 space-y-12">
            @forelse($news as $item)
                <article class="space-y-4 pb-10 border-b border-zinc-200 last:border-b-0">
                    <!-- Headline Title -->
                    <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900 hover:text-blue-900 transition-colors leading-snug">
                        <a href="{{ route('news.show', $item->slug) }}">
                            {{ $item->title }}
                        </a>
                    </h2>

                    <!-- Meta: TimeAgo & Audio available -->
                    <div class="flex items-center gap-4 text-xs text-zinc-500">
                        <span>{{ $item->published_at ? $item->published_at->diffForHumans() : $item->created_at->diffForHumans() }}</span>
                        @if($item->has_audio)
                            <span class="inline-flex items-center gap-1.5 text-zinc-600">
                                <svg class="w-3.5 h-3.5 text-zinc-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"/>
                                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>
                                </svg>
                                <span>Audio available</span>
                            </span>
                        @endif
                    </div>

                    <!-- Featured Image with Video Badge (Image 2 style) -->
                    <div class="relative overflow-hidden bg-zinc-100 rounded-sm">
                        <a href="{{ route('news.show', $item->slug) }}">
                            <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-full aspect-[16/10] sm:aspect-[16/9] object-cover hover:scale-[1.01] transition-transform duration-300">
                        </a>

                        <!-- Top-left Pill Badge (Image 2: [▷ Videos]) -->
                        @if($item->badge_text || $item->has_video)
                            <div class="absolute top-3 left-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/95 backdrop-blur-xs text-[#003882] text-xs font-bold rounded-full border border-zinc-300 shadow-sm">
                                    <svg class="w-3 h-3 fill-current text-blue-700" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    <span>{{ $item->badge_text ?: 'Videos' }}</span>
                                </span>
                            </div>
                        @endif

                        <!-- Small copyright / info icon bottom left -->
                        <div class="absolute bottom-2.5 left-2.5 text-white/80 hover:text-white bg-black/40 backdrop-blur-2xs p-1 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>

                    <!-- Summary / Excerpt Paragraphs with subtle blue highlights -->
                    <div class="text-zinc-700 text-sm leading-relaxed space-y-3 pt-1">
                        @php
                            $paragraphs = explode("\n", trim($item->summary));
                        @endphp
                        @foreach($paragraphs as $para)
                            @if(trim($para))
                                <p>{{ trim($para) }}</p>
                            @endif
                        @endforeach
                    </div>

                    <!-- Gallery Thumbnail Strip (5 Thumbs with +8 overlay) -->
                    @php
                        $gallery = is_array($item->gallery) && count($item->gallery) > 0 ? $item->gallery : [];
                        // Fallback sample architectural thumbnails if none uploaded yet
                        if (empty($gallery)) {
                            $gallery = [
                                'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=300&q=80',
                                'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=300&q=80',
                                'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=300&q=80',
                                'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=300&q=80',
                                'https://images.unsplash.com/photo-1479839672679-a46483c0e7c8?auto=format&fit=crop&w=300&q=80',
                                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=300&q=80',
                                'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=300&q=80',
                                'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=300&q=80',
                                'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=300&q=80',
                                'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=300&q=80',
                                'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=300&q=80',
                                'https://images.unsplash.com/photo-1512915922686-57c11dde9b6b?auto=format&fit=crop&w=300&q=80',
                            ];
                        }
                        $displayThumbs = array_slice($gallery, 0, 5);
                        $remainingCount = count($gallery) > 4 ? (count($gallery) - 4) : 8;
                    @endphp

                    <div class="grid grid-cols-5 gap-2 sm:gap-2.5 pt-2">
                        @foreach($displayThumbs as $i => $tImg)
                            <div class="aspect-square bg-zinc-100 rounded-xs overflow-hidden relative group">
                                <a href="{{ route('news.show', $item->slug) }}" class="block w-full h-full">
                                    <img src="{{ $tImg }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                    @if($i === 4)
                                        <!-- +8 overlay on the 5th thumbnail (Image 2) -->
                                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center text-white font-bold text-base sm:text-lg">
                                            +{{ $remainingCount }}
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <!-- Post Footer Actions: Save button & Read more » -->
                    <div class="flex items-center justify-between pt-4">
                        <button onclick="toggleSaveNews('{{ $item->id }}', this)" class="inline-flex items-center gap-2 px-4 py-2 bg-[#003882] hover:bg-[#002860] text-white text-xs font-bold rounded-xs transition-colors shadow-2xs cursor-pointer">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                            <span>Save this article</span>
                        </button>

                        <a href="{{ route('news.show', $item->slug) }}" class="text-xs font-bold text-[#003882] hover:underline inline-flex items-center gap-1">
                            <span>Read more &raquo;</span>
                        </a>
                    </div>
                </article>
            @empty
                <div class="py-12 text-center text-zinc-400">
                    No architecture news found in this category.
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="pt-6">
                {{ $news->links() }}
            </div>
        </main>

        <!-- ================= RIGHT SIDEBAR (4 COLS - ARCHDAILY LAYOUT) ================= -->
        <div class="lg:col-span-4 lg:pl-4">
            @include('frontend.partials.editorial-sidebar', ['selectedProducts' => $selectedProducts])
        </div>

    </div>
</div>

<script>
function toggleSaveNews(id, btn) {
    const isSaved = btn.getAttribute('data-saved') === 'true';
    if (!isSaved) {
        btn.setAttribute('data-saved', 'true');
        btn.classList.remove('bg-[#003882]');
        btn.classList.add('bg-emerald-700');
        btn.querySelector('span').textContent = 'Saved to Collection';
    } else {
        btn.setAttribute('data-saved', 'false');
        btn.classList.remove('bg-emerald-700');
        btn.classList.add('bg-[#003882]');
        btn.querySelector('span').textContent = 'Save this article';
    }
}
</script>
@endsection
