@extends('layouts.author')

@section('title', 'My Projects | nook Author Studio')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-zinc-200 pb-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900">My Architecture Projects</h1>
            <p class="text-xs text-zinc-500 mt-1">Manage your project submissions, track review status, and edit drafts.</p>
        </div>
        <a href="{{ route('author.projects.create') }}" class="w-full sm:w-auto text-center px-5 py-2.5 bg-black text-white hover:bg-zinc-800 text-xs font-bold rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-sm">
            <span class="text-emerald-400 font-bold text-sm">+</span> Submit New Project
        </a>
    </div>

    <!-- Mobile Card View (Visible on small screens < 768px) -->
    <div class="block md:hidden space-y-3">
        @forelse($projects as $p)
            <div class="bg-white rounded-xl border border-zinc-200 p-4 shadow-sm space-y-3">
                <div class="flex items-start gap-3">
                    <img src="{{ $p->featured_image }}" class="w-16 h-16 rounded-lg object-cover border border-zinc-200 shrink-0">
                    <div class="flex-1 min-w-0">
                        <strong class="text-zinc-900 font-semibold text-sm block leading-snug line-clamp-2">{{ $p->title }}</strong>
                        <div class="flex flex-wrap items-center gap-1.5 mt-1">
                            <span class="text-[10px] font-medium bg-zinc-100 text-zinc-700 px-2 py-0.5 rounded">{{ $p->category }}</span>
                            <span class="text-[10px] font-medium text-zinc-400">&bull;</span>
                            <span class="text-[10px] font-medium text-zinc-500">{{ $p->country }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1 border-t border-zinc-100">
                    <div>
                        @if($p->status === 'approved')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live
                            </span>
                        @elseif($p->status === 'pending')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending Review
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                Rejected
                            </span>
                        @endif
                    </div>
                    <span class="text-[11px] text-zinc-400">{{ number_format($p->views_count) }} views</span>
                </div>

                <!-- Action Buttons -->
                <div class="grid {{ $p->status === 'approved' ? 'grid-cols-3' : 'grid-cols-2' }} gap-2 pt-2 border-t border-zinc-100 text-xs font-semibold text-center">
                    @if($p->status === 'approved')
                        <a href="{{ route('projects.show', $p->slug) }}" target="_blank" class="py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                            View Live
                        </a>
                    @endif
                    <a href="{{ route('author.projects.edit', $p->id) }}" class="py-2 bg-zinc-100 text-zinc-800 rounded-lg hover:bg-zinc-200 transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('author.projects.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this project?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-zinc-200 p-8 text-center text-zinc-500 text-xs">
                No projects submitted yet.
                <a href="{{ route('author.projects.create') }}" class="text-black font-bold block mt-2 hover:underline">+ Submit Your Project</a>
            </div>
        @endforelse
    </div>

    <!-- Desktop Table View (Hidden on mobile >= 768px) -->
    <div class="hidden md:block bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 uppercase font-semibold text-[10px]">
                    <tr>
                        <th class="px-6 py-3.5">Project</th>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-6 py-3.5">Country</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Views</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 text-zinc-700">
                    @forelse($projects as $p)
                        <tr class="hover:bg-zinc-50">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img src="{{ $p->featured_image }}" class="w-12 h-12 rounded object-cover border border-zinc-200 shrink-0">
                                <div>
                                    <strong class="text-zinc-900 block font-semibold text-xs">{{ $p->title }}</strong>
                                    <span class="text-[11px] text-zinc-400 block line-clamp-1 max-w-md">{{ $p->excerpt }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $p->category }}</td>
                            <td class="px-6 py-4">{{ $p->country }}</td>
                            <td class="px-6 py-4">
                                @if($p->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Approved & Live
                                    </span>
                                @elseif($p->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-zinc-600">{{ number_format($p->views_count) }}</td>
                            <td class="px-6 py-4 text-right space-x-3">
                                @if($p->status === 'approved')
                                    <a href="{{ route('projects.show', $p->slug) }}" target="_blank" class="text-blue-600 font-bold hover:underline">View Live</a>
                                @endif
                                <a href="{{ route('author.projects.edit', $p->id) }}" class="text-zinc-700 font-semibold hover:underline">Edit</a>
                                <form action="{{ route('author.projects.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this project?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-zinc-500">
                                No projects submitted yet.
                                <a href="{{ route('author.projects.create') }}" class="text-black font-bold block mt-2 hover:underline">+ Submit Your Project</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pt-4">
        {{ $projects->links() }}
    </div>

</div>
@endsection
