@php
    $sidebarProducts = isset($selectedProducts) && $selectedProducts->count() > 0 
        ? $selectedProducts 
        : \App\Models\Product::approved()->latest()->take(3)->get();
@endphp

<aside class="space-y-8">
    <!-- Top Placeholder / Sponsor Box (Image 1 & 2 Gray Box) -->
    <div class="w-full bg-[#f4f4f4] rounded-sm min-h-[220px] flex items-center justify-center border border-zinc-100">
        <span class="text-[11px] uppercase tracking-wider text-zinc-300 font-medium">Advertisement</span>
    </div>

    <!-- 1. SELECTED PRODUCTS Widget -->
    <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-900 border-b border-transparent pb-3">
            SELECTED PRODUCTS
        </h3>

        <div class="space-y-4">
            @forelse($sidebarProducts as $p)
                <a href="{{ route('products.show', $p->slug) }}" class="flex items-start gap-3.5 group">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 shrink-0 bg-zinc-100 border border-zinc-200 rounded-sm overflow-hidden">
                        <img src="{{ $p->featured_image }}" alt="{{ $p->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-zinc-900 group-hover:text-blue-800 transition-colors leading-snug line-clamp-2">
                            {{ $p->title }}
                        </h4>
                        <p class="text-[11px] text-zinc-500 mt-1 line-clamp-1">
                            {{ $p->manufacturer }}
                        </p>
                    </div>
                </a>
            @empty
                <!-- Fallback items matching screenshot exactly -->
                <div class="flex items-start gap-3.5">
                    <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=150&q=80" class="w-16 h-16 rounded-sm object-cover border border-zinc-200">
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900 leading-snug">Residential AC Mini Split Range - airHome™</h4>
                        <p class="text-[11px] text-zinc-500 mt-0.5">Hitachi Air Conditioning</p>
                    </div>
                </div>
                <div class="flex items-start gap-3.5">
                    <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=150&q=80" class="w-16 h-16 rounded-sm object-cover border border-zinc-200">
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900 leading-snug">SiteSupervisor - Construction App</h4>
                        <p class="text-[11px] text-zinc-500 mt-0.5">SiteSupervisor</p>
                    </div>
                </div>
                <div class="flex items-start gap-3.5">
                    <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=150&q=80" class="w-16 h-16 rounded-sm object-cover border border-zinc-200">
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900 leading-snug">Bathroom Equipment - Metallic Towel Rings</h4>
                        <p class="text-[11px] text-zinc-500 mt-0.5">Sanco</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- 2. ARCHITECTURE PUBLICATIONS Widget -->
    <div class="pt-2">
        <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-900 pb-3">
            ARCHITECTURE PUBLICATIONS
        </h3>

        <div class="flex items-center gap-3.5">
            <div class="w-16 h-16 sm:w-20 sm:h-20 shrink-0 bg-zinc-800 rounded-sm overflow-hidden relative shadow-2xs">
                <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=200&q=80" alt="TC 175" class="w-full h-full object-cover">
                <span class="absolute bottom-1 left-1 text-[8px] font-bold text-white bg-black/60 px-1 rounded-2xs">MONEO BROCK</span>
            </div>
            <div class="flex-1">
                <h4 class="text-xs font-bold text-zinc-900 leading-snug">
                    TC 175- Moneo Brock
                </h4>
            </div>
        </div>

        <div class="text-right mt-3">
            <a href="{{ route('articles.index') }}" class="text-[11px] text-blue-700 hover:text-blue-900 hover:underline font-semibold inline-flex items-center gap-0.5">
                <span>More publications &raquo;</span>
            </a>
        </div>
    </div>

    <!-- 3. ARCHITECTURE COMPETITIONS Widget -->
    <div class="pt-2">
        <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-900 pb-3">
            ARCHITECTURE COMPETITIONS
        </h3>

        <div class="flex items-center gap-3.5">
            <div class="w-16 h-16 sm:w-20 sm:h-20 shrink-0 bg-zinc-900 rounded-sm overflow-hidden relative shadow-2xs">
                <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=200&q=80" alt="Competition" class="w-full h-full object-cover">
                <span class="absolute bottom-1 left-1 text-[8px] font-bold text-white bg-black/60 px-1 rounded-2xs">AI &times; Gaudi</span>
            </div>
            <div class="flex-1">
                <h4 class="text-xs font-bold text-zinc-900 leading-snug">
                    AI x Gaudi: Architecture Competition + AI Course
                </h4>
            </div>
        </div>

        <div class="text-right mt-3">
            <a href="{{ route('news.index') }}" class="text-[11px] text-blue-700 hover:text-blue-900 hover:underline font-semibold inline-flex items-center gap-0.5">
                <span>More competitions &raquo;</span>
            </a>
        </div>
    </div>
</aside>
