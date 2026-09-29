@extends('layouts.frontend')

@section('title', $product->title . ' | ' . $product->manufacturer . ' - nook')
@section('meta_description', Str::limit(strip_tags($product->short_description ?? $product->use_description), 160))

@section('content')
<div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Breadcrumb (Matching Image 3) -->
    <nav class="text-xs text-zinc-500 mb-4 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-black">ArchDaily &bull; nook</a>
        <span>&rsaquo;</span>
        <a href="{{ route('products.index') }}" class="hover:text-black">Products</a>
        <span>&rsaquo;</span>
        <a href="{{ route('products.index', ['category' => $product->category]) }}" class="hover:text-black">{{ $product->category }}</a>
        <span>&rsaquo;</span>
        <span class="text-zinc-900 font-medium truncate max-w-xs">{{ $product->title }}</span>
    </nav>

    <!-- Title & Action Buttons (Share, Save - Image 3 style) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-zinc-200 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900">
                {{ $product->title }} <span class="text-blue-900 font-semibold">| {{ $product->manufacturer }}</span>
            </h1>
        </div>

        <div class="flex items-center space-x-3 shrink-0">
            <!-- Share Button -->
            <button onclick="copyProductLink()" id="shareBtn" class="px-4 py-2 text-xs font-semibold text-zinc-800 bg-white border border-zinc-300 rounded hover:bg-zinc-50 transition-colors flex items-center gap-1.5 shadow-sm">
                <svg class="w-4 h-4 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                <span>Share</span>
            </button>

            <!-- Save Bookmark Button (Image 3 solid dark blue button style) -->
            <button onclick="toggleSaveProduct()" id="saveBtn" class="px-5 py-2 text-xs font-semibold text-white bg-[#003882] hover:bg-[#002860] rounded transition-colors flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                <span id="saveText">Save</span>
            </button>
        </div>
    </div>

    <!-- Product Showcase Grid (Matching Image 3 Layout) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">

        <!-- ================= LEFT AREA: HERO PREVIEW, GALLERY & SPECS (8 COLS) ================= -->
        <div class="lg:col-span-8 space-y-8">

            <!-- Main Feature Image Viewer -->
            <div class="bg-black/5 rounded overflow-hidden border border-zinc-200">
                <div class="aspect-[16/10] overflow-hidden bg-black flex items-center justify-center">
                    <img id="mainProductImage"
                         src="{{ $product->featured_image }}"
                         alt="{{ $product->title }}"
                         class="w-full h-full object-contain md:object-cover transition-all duration-300">
                </div>
            </div>

            <!-- Thumbnail Carousel / Strip with +11 overlay (Matching Image 3) -->
            @php
                $gallery = is_array($product->gallery) && count($product->gallery) > 0 ? $product->gallery : [$product->featured_image];
                // Ensure at least 5 thumbnails for visual parity with screenshot
                $displayThumbs = array_slice($gallery, 0, 5);
                $remainingCount = count($gallery) > 5 ? count($gallery) - 5 : 11;
            @endphp
            <div class="grid grid-cols-5 gap-2 sm:gap-3">
                @foreach($displayThumbs as $index => $img)
                    <div onclick="selectImage('{{ $img }}')"
                         class="cursor-pointer aspect-video bg-zinc-100 border-2 {{ $index === 0 ? 'border-blue-600' : 'border-zinc-200' }} hover:border-black rounded overflow-hidden relative thumb-box transition-all">
                        <img src="{{ $img }}" class="w-full h-full object-cover">
                        @if($index === 4)
                            <!-- +11 overlay badge on the 5th thumbnail as in Image 3 -->
                            <div class="absolute inset-0 bg-black/60 flex items-center justify-center text-white font-bold text-sm sm:text-base">
                                +{{ $remainingCount }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Detailed Specifications Table (Exact match to Image 3 breakdown) -->
            <div class="border-t border-zinc-200 pt-8 space-y-6">
                <h3 class="text-lg font-bold text-zinc-900 border-b border-zinc-200 pb-3">Product Specifications</h3>

                <dl class="divide-y divide-zinc-200 text-sm">
                    @if($product->use_description)
                        <div class="py-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <dt class="font-bold text-zinc-900">Description & Use</dt>
                            <dd class="sm:col-span-3 text-zinc-700 leading-relaxed prose prose-zinc max-w-none [&_img]:rounded-xl [&_img]:my-4 [&_img]:max-w-full [&_img]:shadow-sm">
                                {!! $product->use_description !!}
                            </dd>
                        </div>
                    @endif

                    @if($product->applications)
                        <div class="py-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <dt class="font-bold text-zinc-900">Applications</dt>
                            <dd class="sm:col-span-3 text-zinc-700 leading-relaxed">{{ $product->applications }}</dd>
                        </div>
                    @endif

                    @if($product->characteristics)
                        <div class="py-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <dt class="font-bold text-zinc-900">Characteristics</dt>
                            <dd class="sm:col-span-3 text-zinc-700 leading-relaxed">{{ $product->characteristics }}</dd>
                        </div>
                    @endif

                    <div class="py-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <dt class="font-bold text-zinc-900">Manufacturer</dt>
                        <dd class="sm:col-span-3 text-zinc-700 font-semibold">{{ $product->manufacturer }}</dd>
                    </div>

                    <div class="py-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <dt class="font-bold text-zinc-900">Category</dt>
                        <dd class="sm:col-span-3 text-zinc-700">{{ $product->category }}</dd>
                    </div>

                    <div class="py-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <dt class="font-bold text-zinc-900">BIM Files</dt>
                        <dd class="sm:col-span-3 text-zinc-700 flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold {{ $product->has_bim ? 'bg-emerald-100 text-emerald-800' : 'bg-zinc-100 text-zinc-800' }}">
                                {{ $product->has_bim ? 'Available for Archicad, Revit, BIMx' : 'Not specified' }}
                            </span>
                        </dd>
                    </div>

                    @if($product->website_url)
                        <div class="py-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <dt class="font-bold text-zinc-900">Website</dt>
                            <dd class="sm:col-span-3 text-blue-700">
                                <a href="{{ $product->website_url }}" target="_blank" rel="noopener noreferrer" class="hover:underline flex items-center gap-1 font-medium">
                                    {{ $product->website_url }}
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Full Description -->
            @if($product->short_description)
                <div class="border-t border-zinc-200 pt-6">
                    <h4 class="text-sm font-bold text-zinc-900 mb-2 uppercase tracking-wide">Overview</h4>
                    <p class="text-sm text-zinc-700 leading-relaxed">
                        {{ $product->short_description }}
                    </p>
                </div>
            @endif

        </div>


        <!-- ================= RIGHT SIDEBAR: REGION NOTICE & MANUFACTURER CONTACT (4 COLS) ================= -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Region Availability Alert Box (Exact match to Image 3 Light Blue Box) -->
            <div class="bg-[#e8f1f9] border border-[#bcd2e8] text-[#1a5276] px-5 py-3.5 rounded text-xs sm:text-[13px] font-medium leading-relaxed">
                This product page is available in Bangladesh & Worldwide.
            </div>

            <!-- Contact Manufacturer Card (Exact match to Image 3) -->
            <div class="bg-white border border-zinc-200 rounded-lg p-6 shadow-sm space-y-5">
                <!-- Manufacturer Header -->
                <div class="flex items-center space-x-4 pb-4 border-b border-zinc-100">
                    <div class="w-14 h-14 bg-zinc-100 rounded-lg border border-zinc-200 flex items-center justify-center p-2 shrink-0">
                        <img src="{{ $product->manufacturer_logo ?? 'https://ui-avatars.com/api/?name=' . urlencode($product->manufacturer) . '&background=003882&color=fff' }}"
                             alt="{{ $product->manufacturer }}"
                             class="max-w-full max-h-full object-contain">
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider block">Contact Manufacturer</span>
                        <h3 class="text-lg font-bold text-zinc-900 tracking-tight">{{ $product->manufacturer }}</h3>
                    </div>
                </div>

                <!-- Action Button 1: Website Link (Added by author/admin, opens external url!) -->
                <div>
                    @if($product->website_url)
                        <a href="{{ $product->website_url }}" target="_blank" rel="noopener noreferrer"
                           class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-zinc-300 rounded text-xs font-bold text-zinc-800 hover:bg-zinc-50 hover:border-black transition-colors">
                            <svg class="w-4 h-4 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            <span>Website</span>
                        </a>
                    @else
                        <button disabled class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-zinc-200 rounded text-xs font-medium text-zinc-400 cursor-not-allowed">
                            <span>No Website Link Listed</span>
                        </button>
                    @endif
                </div>

                <!-- Action Button 2: Phone -->
                <div>
                    @if($product->phone)
                        <a href="tel:{{ $product->phone }}"
                           class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-zinc-300 rounded text-xs font-bold text-zinc-800 hover:bg-zinc-50 hover:border-black transition-colors">
                            <svg class="w-4 h-4 text-zinc-700" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 4V3z"/></svg>
                            <span>Phone: {{ $product->phone }}</span>
                        </a>
                    @else
                        <div class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-zinc-200 rounded text-xs font-medium text-zinc-500 bg-zinc-50">
                            <span>Phone Available On Request</span>
                        </div>
                    @endif
                </div>

                <!-- Action Button 3: SEND MESSAGE (Image 3 solid dark blue button) -->
                <div>
                    <button onclick="openInquiryModal()"
                            class="w-full py-3 px-4 bg-[#003882] hover:bg-[#002860] text-white text-xs font-bold uppercase tracking-wider rounded transition-colors shadow flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>SEND MESSAGE</span>
                    </button>
                </div>
            </div>

            <!-- Author & Follow Box -->
            @if($product->author)
                <div class="bg-white border border-zinc-200 rounded-lg p-5 shadow-sm space-y-4">
                    <span class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider block">Submitted By Author</span>
                    <div class="flex items-center space-x-3">
                        <img src="{{ $product->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($product->author->name) }}" class="w-11 h-11 rounded-full object-cover border border-zinc-200">
                        <div>
                            <a href="{{ route('author.profile', $product->author->username) }}" class="text-sm font-bold text-zinc-900 hover:underline">
                                {{ $product->author->name }}
                            </a>
                            <p class="text-xs text-zinc-500">{{ $product->author->company ?? 'Architecture Specialist' }}</p>
                            <p class="text-[11px] text-zinc-400"><span id="sidebarFollowersCount">{{ $product->author->followers()->count() }}</span> Followers</p>
                        </div>
                    </div>

                    <!-- Follow Button -->
                    <button onclick="handleFollowAuthor({{ $product->author->id }})" id="sidebarFollowBtn"
                            class="w-full py-2 px-3 text-xs font-bold rounded transition-colors {{ $isFollowing ? 'bg-zinc-100 text-zinc-800 border border-zinc-300 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'bg-black text-white hover:bg-zinc-800' }}">
                        {{ $isFollowing ? '✓ Following' : '+ Follow Author' }}
                    </button>
                </div>
            @endif

        </div>

    </div>

</div>

<!-- Modal: Send Message to Manufacturer -->
<div id="inquiryModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-lg w-full p-6 shadow-2xl relative border border-zinc-200">
        <button onclick="closeInquiryModal()" class="absolute top-4 right-4 text-zinc-400 hover:text-black text-xl font-bold">&times;</button>
        <div class="mb-4">
            <h3 class="text-lg font-bold text-zinc-900">Contact {{ $product->manufacturer }}</h3>
            <p class="text-xs text-zinc-500">Inquiry regarding: <strong class="text-zinc-800">{{ $product->title }}</strong></p>
        </div>

        <form action="{{ route('products.inquiry', $product->id) }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Your Full Name *</label>
                <input type="text" name="name" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:border-black focus:outline-none" placeholder="e.g. Ar. Rafiqul Islam">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Email Address *</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:border-black focus:outline-none" placeholder="architect@studio.com">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Phone Number</label>
                    <input type="tel" name="phone" class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:border-black focus:outline-none" placeholder="+880 1712 345678">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Architecture Firm / Company</label>
                <input type="text" name="company" class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:border-black focus:outline-none" placeholder="e.g. Studio Forma">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Message / Project Inquiry *</label>
                <textarea name="message" rows="4" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:border-black focus:outline-none" placeholder="Inquire about pricing, BIM object downloads, regional distribution, or sample requests..."></textarea>
            </div>
            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeInquiryModal()" class="px-4 py-2 text-xs font-semibold text-zinc-600 hover:text-black">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#003882] hover:bg-[#002860] rounded shadow">Send Message &rarr;</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function selectImage(src) {
        document.getElementById('mainProductImage').src = src;
    }

    function openInquiryModal() {
        document.getElementById('inquiryModal').classList.remove('hidden');
    }

    function closeInquiryModal() {
        document.getElementById('inquiryModal').classList.add('hidden');
    }

    function copyProductLink() {
        navigator.clipboard.writeText(window.location.href);
        const btn = document.getElementById('shareBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = `<span class="text-emerald-700 font-bold">✓ Copied!</span>`;
        setTimeout(() => { btn.innerHTML = originalText; }, 2000);
    }

    function toggleSaveProduct() {
        const saveText = document.getElementById('saveText');
        const saveBtn = document.getElementById('saveBtn');
        if (saveText.innerText === 'Save') {
            saveText.innerText = 'Saved';
            saveBtn.classList.add('bg-emerald-700');
        } else {
            saveText.innerText = 'Save';
            saveBtn.classList.remove('bg-emerald-700');
        }
    }

    function handleFollowAuthor(authorId) {
        fetch(`/architect/${authorId}/follow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (response.status === 401) {
                window.location.href = "{{ route('author.login') }}";
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                const btn = document.getElementById('sidebarFollowBtn');
                const countEl = document.getElementById('sidebarFollowersCount');
                if (data.is_following) {
                    btn.innerText = '✓ Following';
                    btn.className = 'w-full py-2 px-3 text-xs font-bold rounded transition-colors bg-zinc-100 text-zinc-800 border border-zinc-300 hover:bg-red-50 hover:text-red-600 hover:border-red-200';
                } else {
                    btn.innerText = '+ Follow Author';
                    btn.className = 'w-full py-2 px-3 text-xs font-bold rounded transition-colors bg-black text-white hover:bg-zinc-800';
                }
                if (countEl) {
                    countEl.innerText = data.followers_count;
                }
            } else if (data && data.message) {
                alert(data.message);
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endpush
@endsection
