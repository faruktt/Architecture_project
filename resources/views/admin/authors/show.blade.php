@extends('layouts.admin')

@section('title', 'Author Detail: ' . $author->name)

@section('content')
<div class="max-w-5xl mx-auto py-2 space-y-6">

    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Author Profile</h1>
        </div>
        <a href="{{ route('admin.authors.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-semibold flex items-center gap-1 transition-colors">
            <span>&larr; Back to Authors</span>
        </a>
    </div>

    <!-- Profile Header Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-start justify-between gap-6 shadow-sm">
        <div class="flex items-center gap-5">
            <img src="{{ $author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) }}" class="w-20 h-20 rounded-full object-cover border-2 border-slate-200 shadow-sm">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-slate-900">{{ $author->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $author->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                        {{ $author->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">{{ $author->title ?? 'Architect' }} &bull; {{ $author->company ?? 'Independent' }} &bull; {{ $author->country }}</p>
                <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $author->email }}</p>
                @if($author->bio)
                    <p class="text-xs text-slate-600 mt-3 max-w-xl leading-relaxed">{{ $author->bio }}</p>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-2 shrink-0">
            <a href="{{ route('author.profile', $author->username) }}" target="_blank" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold text-center shadow-xs transition-all">
                View Public Page &nearr;
            </a>
            <form action="{{ route('admin.authors.toggle-status', $author->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold shadow-2xs transition-all">
                    {{ $author->status === 'active' ? 'Suspend Account' : 'Reactivate Account' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Author Projects -->
    <div class="space-y-4">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Submitted Projects ({{ $author->projects->count() }})</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($author->projects as $p)
                <div class="bg-white border border-slate-200/90 rounded-2xl p-4 flex gap-4 items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <img src="{{ $p->featured_image }}" class="w-14 h-14 rounded-xl object-cover border border-slate-200 shrink-0">
                        <div>
                            <strong class="text-slate-900 block text-xs font-bold">{{ $p->title }}</strong>
                            <span class="text-[11px] text-slate-500">{{ $p->category }} &bull; {{ $p->country }}</span>
                            <div class="mt-1">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $p->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($p->status === 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                    {{ $p->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.projects.edit', $p->id) }}" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-bold rounded-lg shadow-2xs transition-all">
                        Edit &rarr;
                    </a>
                </div>
            @empty
                <div class="col-span-2 p-6 bg-white border border-slate-200 rounded-2xl text-center text-slate-400 text-xs">
                    No projects submitted by this architect yet.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Author Products -->
    <div class="space-y-4">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Submitted Products ({{ $author->products->count() }})</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($author->products as $pr)
                <div class="bg-white border border-slate-200/90 rounded-2xl p-4 flex gap-4 items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <img src="{{ $pr->featured_image }}" class="w-14 h-14 rounded-xl object-cover border border-slate-200 shrink-0">
                        <div>
                            <strong class="text-slate-900 block text-xs font-bold">{{ $pr->title }}</strong>
                            <span class="text-[11px] text-slate-500">{{ $pr->manufacturer }} &bull; {{ $pr->category }}</span>
                            <div class="mt-1">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $pr->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($pr->status === 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                    {{ $pr->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.products.edit', $pr->id) }}" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-bold rounded-lg shadow-2xs transition-all">
                        Edit &rarr;
                    </a>
                </div>
            @empty
                <div class="col-span-2 p-6 bg-white border border-slate-200 rounded-2xl text-center text-slate-400 text-xs">
                    No products submitted yet.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
