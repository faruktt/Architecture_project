@extends('layouts.frontend')

@section('title', $author->name . ' - Architect Portfolio | nook')

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Author Profile Header -->
    <div class="bg-white border border-zinc-200 rounded-xl p-6 sm:p-10 mb-10 shadow-sm">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
            <img src="{{ $author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) }}"
                 alt="{{ $author->name }}"
                 class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover border-4 border-zinc-100 shadow-md">

            <div class="flex-grow space-y-2">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900">{{ $author->name }}</h1>
                        <p class="text-sm text-zinc-500 font-medium">{{ $author->title ?? 'Architect' }} &bull; {{ $author->company ?? $author->country }}</p>
                    </div>

                    <!-- Follow Button -->
                    <div>
                        @if($currentAuthor && $currentAuthor->id === $author->id)
                            <a href="{{ route('author.profile.edit') }}" class="px-5 py-2 text-xs font-bold rounded bg-zinc-100 text-zinc-800 border border-zinc-300 hover:bg-zinc-200">
                                Edit Profile
                            </a>
                        @else
                            <button onclick="handleAuthorFollow({{ $author->id }})" id="authorPageFollowBtn"
                                    class="px-6 py-2.5 text-xs font-bold rounded shadow-sm transition-colors {{ $isFollowing ? 'bg-zinc-100 text-zinc-800 border border-zinc-300 hover:bg-red-50 hover:text-red-600' : 'bg-black text-white hover:bg-zinc-800' }}">
                                {{ $isFollowing ? '✓ Following' : '+ Follow Author' }}
                            </button>
                        @endif
                    </div>
                </div>

                @if($author->bio)
                    <p class="text-sm text-zinc-700 max-w-2xl leading-relaxed pt-2">
                        {{ $author->bio }}
                    </p>
                @endif

                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-6 pt-3 text-xs text-zinc-600 border-t border-zinc-100 mt-4">
                    <div><strong id="followersCountText" class="text-zinc-900 font-bold text-sm">{{ $author->followers()->count() }}</strong> Followers</div>
                    <div><strong class="text-zinc-900 font-bold text-sm">{{ $author->following()->count() }}</strong> Following</div>
                    <div><strong class="text-zinc-900 font-bold text-sm">{{ $author->approvedProjects()->count() }}</strong> Projects</div>
                    <div><strong class="text-zinc-900 font-bold text-sm">{{ $author->approvedProducts()->count() }}</strong> Products</div>
                    @if($author->website)
                        <div><a href="{{ $author->website }}" target="_blank" class="text-blue-600 hover:underline">Portfolio Site &nearr;</a></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Author Portfolio Sections -->
    <div class="space-y-12">
        <!-- Projects Section -->
        <div>
            <h2 class="text-xl font-bold text-zinc-900 border-b border-zinc-200 pb-3 mb-6">
                Published Architectural Projects ({{ $projects->total() }})
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($projects as $p)
                    <div class="group border border-zinc-200 rounded overflow-hidden bg-white shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="aspect-[16/11] overflow-hidden bg-zinc-100">
                                <a href="{{ route('projects.show', $p->slug) }}">
                                    <img src="{{ $p->featured_image }}" alt="{{ $p->title }}" class="w-full h-full object-cover editorial-image-hover">
                                </a>
                            </div>
                            <div class="p-4">
                                <div class="text-[11px] text-zinc-400 font-semibold uppercase">{{ $p->category }} &bull; {{ $p->country }}</div>
                                <h3 class="text-sm font-bold text-zinc-900 group-hover:text-black mt-1 leading-snug">
                                    <a href="{{ route('projects.show', $p->slug) }}">{{ $p->title }}</a>
                                </h3>
                                <p class="text-xs text-zinc-600 line-clamp-2 mt-1">{{ $p->excerpt }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="col-span-3 text-xs text-zinc-500 py-6 text-center">No projects published yet by this author.</p>
                @endforelse
            </div>
        </div>

        <!-- Products Section -->
        @if($products->count() > 0)
            <div>
                <h2 class="text-xl font-bold text-zinc-900 border-b border-zinc-200 pb-3 mb-6">
                    Published Products & Specifications ({{ $products->total() }})
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($products as $prod)
                        <div class="group border border-zinc-200 rounded overflow-hidden bg-white shadow-sm">
                            <div class="aspect-[16/11] overflow-hidden bg-zinc-100">
                                <a href="{{ route('products.show', $prod->slug) }}">
                                    <img src="{{ $prod->featured_image }}" alt="{{ $prod->title }}" class="w-full h-full object-cover editorial-image-hover">
                                </a>
                            </div>
                            <div class="p-4">
                                <div class="text-[10px] font-bold text-zinc-400 uppercase">{{ $prod->manufacturer }}</div>
                                <h3 class="text-xs font-bold text-zinc-900 group-hover:text-blue-900 mt-1">
                                    <a href="{{ route('products.show', $prod->slug) }}">{{ $prod->title }}</a>
                                </h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
    function handleAuthorFollow(authorId) {
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
                const btn = document.getElementById('authorPageFollowBtn');
                const countText = document.getElementById('followersCountText');
                if (data.is_following) {
                    btn.innerText = '✓ Following';
                    btn.className = 'px-6 py-2.5 text-xs font-bold rounded shadow-sm transition-colors bg-zinc-100 text-zinc-800 border border-zinc-300 hover:bg-red-50 hover:text-red-600';
                } else {
                    btn.innerText = '+ Follow Author';
                    btn.className = 'px-6 py-2.5 text-xs font-bold rounded shadow-sm transition-colors bg-black text-white hover:bg-zinc-800';
                }
                if (countText) {
                    countText.innerText = data.followers_count;
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
