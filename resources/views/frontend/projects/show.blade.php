@extends('layouts.frontend')

@section('title', $project->title . ' | nook MAGAZINE')
@section('meta_description', Str::limit(strip_tags($project->excerpt), 160))

@section('content')
<article class="max-w-[1080px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <nav class="text-xs text-zinc-500 mb-6 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-black">nook</a>
        <span>&rsaquo;</span>
        <a href="{{ route('projects.index') }}" class="hover:text-black">Projects</a>
        <span>&rsaquo;</span>
        <a href="{{ route('projects.index', ['country' => $project->country]) }}" class="hover:text-black">{{ $project->country }}</a>
        <span>&rsaquo;</span>
        <span class="text-zinc-900 font-medium truncate max-w-xs">{{ $project->title }}</span>
    </nav>

    <!-- Header Section -->
    <header class="mb-8 border-b border-zinc-200 pb-8">
        <div class="flex items-center gap-2 text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">
            <span>{{ $project->category }}</span>
            <span>&bull;</span>
            <span>{{ $project->country }}</span>
            @if($project->city)
                <span>&bull;</span>
                <span>{{ $project->city }}</span>
            @endif
        </div>

        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-zinc-900 leading-tight">
            {{ $project->title }}
        </h1>

        @if($project->subtitle)
            <p class="text-base text-zinc-500 mt-2 font-medium">
                {{ $project->subtitle }}
            </p>
        @endif

        <!-- Author Byline -->
        @if($project->author)
            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-zinc-100">
                <img src="{{ $project->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->author->name) }}" class="w-10 h-10 rounded-full object-cover border border-zinc-200">
                <div>
                    <a href="{{ route('author.profile', $project->author->username) }}" class="text-xs font-bold text-zinc-900 hover:underline">
                        {{ $project->author->name }}
                    </a>
                    <p class="text-[11px] text-zinc-500">{{ $project->author->title ?? 'Architect' }} &bull; {{ $project->author->company ?? $project->country }}</p>
                </div>
            </div>
        @endif
    </header>

    <!-- Main Hero Image -->
    <div class="mb-10 overflow-hidden bg-zinc-100 border border-zinc-200 rounded">
        <img src="{{ $project->featured_image }}" alt="{{ $project->title }}" class="w-full max-h-[600px] object-cover">
    </div>

    <!-- Metadata Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-5 bg-zinc-50 border border-zinc-200 rounded mb-10 text-xs">
        <div>
            <span class="block text-zinc-400 font-medium uppercase text-[10px]">Location</span>
            <strong class="text-zinc-800 text-sm">{{ $project->city ?? $project->country }}</strong>
        </div>
        <div>
            <span class="block text-zinc-400 font-medium uppercase text-[10px]">Year Built</span>
            <strong class="text-zinc-800 text-sm">{{ $project->year ?? '2024' }}</strong>
        </div>
        <div>
            <span class="block text-zinc-400 font-medium uppercase text-[10px]">Gross Area</span>
            <strong class="text-zinc-800 text-sm">{{ $project->area ?? '450 m²' }}</strong>
        </div>
        <div>
            <span class="block text-zinc-400 font-medium uppercase text-[10px]">Typology</span>
            <strong class="text-zinc-800 text-sm">{{ $project->category }}</strong>
        </div>
    </div>

    <!-- Content Story (Text & Embedded Photos from Rich Editor) -->
    <style>
        .project-editorial-narrative img {
            display: block;
            width: 100%;
            max-width: 100%;
            height: auto;
            border-radius: 14px;
            margin: 2rem auto;
            box-shadow: 0 8px 30px -4px rgba(0, 0, 0, 0.07);
            border: 1px solid #f1f5f9;
        }
        .project-editorial-narrative p {
            margin-bottom: 1.5rem;
            color: #27272a;
            font-size: 16px;
            line-height: 1.8;
        }
        .project-editorial-narrative h2, .project-editorial-narrative h3 {
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #09090b;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
        }
        .project-editorial-narrative blockquote {
            border-left: 3px solid #18181b;
            padding-left: 1.25rem;
            margin: 2rem 0;
            font-style: italic;
            color: #52525b;
        }
    </style>
    <div class="project-editorial-narrative prose max-w-none mb-14">
        {!! $project->content !!}
    </div>

    <!-- Gallery Grid -->
    @if(is_array($project->gallery) && count($project->gallery) > 1)
        <div class="border-t border-zinc-200 pt-10 mb-12">
            <h3 class="text-lg font-bold text-zinc-900 mb-6 uppercase tracking-wider text-xs">Project Photography & Gallery</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($project->gallery as $gImg)
                    <div class="aspect-[16/11] overflow-hidden bg-zinc-100 border border-zinc-200 rounded">
                        <img src="{{ $gImg }}" alt="Gallery Image" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ================= AUTHOR SECTION & FOLLOW BUTTON (SPECIFIED IN USER PROMPT) ================= -->
    <!-- "project ar niche author nam deya thakbe arekjon author follow korte parbe" -->
    @if($project->author)
        <div class="border-t-2 border-zinc-900 pt-8 pb-10 mt-12 bg-white p-6 sm:p-8 rounded-lg border border-zinc-200 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <!-- Author Info -->
                <div class="flex items-start gap-4">
                    <img src="{{ $project->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->author->name) }}"
                         alt="{{ $project->author->name }}"
                         class="w-16 h-16 rounded-full object-cover border-2 border-zinc-200 shrink-0">
                    <div>
                        <span class="text-[11px] font-bold tracking-wider text-zinc-400 uppercase block mb-1">Project Architect & Contributor</span>
                        <h3 class="text-xl font-bold text-zinc-900 leading-tight">
                            <a href="{{ route('author.profile', $project->author->username) }}" class="hover:underline">
                                {{ $project->author->name }}
                            </a>
                        </h3>
                        <p class="text-xs text-zinc-600 mt-0.5">
                            {{ $project->author->title ?? 'Principal Architect' }} &bull; {{ $project->author->company ?? $project->author->country }}
                        </p>
                        @if($project->author->bio)
                            <p class="text-xs text-zinc-600 mt-2 max-w-xl leading-relaxed">
                                {{ $project->author->bio }}
                            </p>
                        @endif
                        <div class="text-xs text-zinc-500 mt-3 flex items-center gap-4">
                            <span><strong id="authorFollowersCount" class="text-black font-semibold">{{ $project->author->followers()->count() }}</strong> Followers</span>
                            <span>&bull;</span>
                            <span><strong>{{ $project->author->approvedProjects()->count() }}</strong> Published Projects</span>
                        </div>
                    </div>
                </div>

                <!-- Follow / Unfollow Interactive Button -->
                <div class="shrink-0 w-full sm:w-auto">
                    <button onclick="handleFollowProjectAuthor({{ $project->author->id }})" id="projectFollowBtn"
                            class="w-full sm:w-auto px-6 py-2.5 text-xs font-bold rounded transition-colors shadow-sm {{ $isFollowing ? 'bg-zinc-100 text-zinc-800 border border-zinc-300 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'bg-black text-white hover:bg-zinc-800' }}">
                        {{ $isFollowing ? '✓ Following' : '+ Follow Author' }}
                    </button>
                    <p class="text-[10px] text-zinc-400 text-center sm:text-right mt-1.5">
                        Stay updated with new projects
                    </p>
                </div>
            </div>
        </div>
    @endif

</article>

@push('scripts')
<script>
    function handleFollowProjectAuthor(authorId) {
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
                const btn = document.getElementById('projectFollowBtn');
                const countEl = document.getElementById('authorFollowersCount');
                if (data.is_following) {
                    btn.innerText = '✓ Following';
                    btn.className = 'w-full sm:w-auto px-6 py-2.5 text-xs font-bold rounded transition-colors shadow-sm bg-zinc-100 text-zinc-800 border border-zinc-300 hover:bg-red-50 hover:text-red-600 hover:border-red-200';
                } else {
                    btn.innerText = '+ Follow Author';
                    btn.className = 'w-full sm:w-auto px-6 py-2.5 text-xs font-bold rounded transition-colors shadow-sm bg-black text-white hover:bg-zinc-800';
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
