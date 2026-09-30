@extends('layouts.admin')

@section('title', 'Project Management | nook Editorial Control')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Projects</h1>
        </div>
        <a href="{{ route('admin.projects.create') }}" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition-all shadow-xs flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus text-emerald-400 text-xs"></i>
            <span>Add Project</span>
        </a>
    </div>

    <!-- Status Tabs -->
    <div class="flex flex-wrap items-center gap-2 text-xs">
        <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ !$status ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200 hover:bg-slate-50' }}">
            All Projects ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.projects.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white text-amber-800 hover:text-amber-900 border border-amber-200 hover:bg-amber-50/50' }}">
            Pending Review ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.projects.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-emerald-700 hover:text-emerald-800 border border-emerald-200 hover:bg-emerald-50/50' }}">
            Approved & Live ({{ $counts['approved'] }})
        </a>
        <a href="{{ route('admin.projects.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-rose-700 hover:text-rose-800 border border-rose-200 hover:bg-rose-50/50' }}">
            Rejected ({{ $counts['rejected'] }})
        </a>
    </div>

    <!-- Projects Table -->
    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[700px]">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Cover & Title</th>
                        <th class="px-6 py-3.5">Author</th>
                        <th class="px-6 py-3.5">Category & Region</th>
                        <th class="px-6 py-3.5">Review Status</th>
                        <th class="px-6 py-3.5">Features</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($projects as $p)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3.5">
                                <img src="{{ $p->featured_image }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shrink-0 shadow-2xs">
                                <div>
                                    <strong class="text-slate-900 block font-bold text-xs">{{ $p->title }}</strong>
                                    <span class="text-[11px] text-slate-500 block line-clamp-1 max-w-sm mt-0.5">{{ $p->excerpt }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($p->author)
                                    <a href="{{ route('admin.authors.show', $p->author->id) }}" class="text-slate-900 font-semibold hover:underline block">
                                        {{ $p->author->name }}
                                    </a>
                                    <span class="text-[11px] text-slate-500">{{ $p->author->company ?? $p->author->country }}</span>
                                @else
                                    <span class="text-slate-400 font-medium">Editorial Direct</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-700 border border-slate-200/80 px-2 py-0.5 rounded-md text-[11px] font-medium">{{ $p->category }}</span>
                                <span class="text-[11px] text-slate-500 block mt-1">{{ $p->country }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($p->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Approved & Live
                                    </span>
                                @elseif($p->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 space-y-1.5 min-w-[200px]">
                                <!-- Project of the Week (Single exclusive) -->
                                <div class="flex items-center gap-1.5">
                                    <form action="{{ route('admin.projects.set-feature', [$p->id, 'is_featured']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border transition-colors {{ $p->is_featured ? 'bg-amber-100 text-amber-900 border-amber-300 shadow-2xs hover:bg-amber-200' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-slate-100 hover:text-slate-700' }}" title="Click to set/unset exclusive Project of the Week">
                                            <span>{{ $p->is_featured ? '★ Project of Week' : '+ Project of Week' }}</span>
                                        </button>
                                    </form>
                                </div>

                                <!-- Nook Spotlight (Single exclusive) -->
                                <div class="flex items-center gap-1.5">
                                    <form action="{{ route('admin.projects.set-feature', [$p->id, 'is_spotlight']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border transition-colors {{ $p->is_spotlight ? 'bg-purple-100 text-purple-900 border-purple-300 shadow-2xs hover:bg-purple-200' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-slate-100 hover:text-slate-700' }}" title="Click to set/unset exclusive Nook Spotlight">
                                            <span>{{ $p->is_spotlight ? '★ Nook Spotlight' : '+ Nook Spotlight' }}</span>
                                        </button>
                                    </form>
                                </div>

                                <!-- Main Hero Story (Single exclusive) -->
                                <div class="flex items-center gap-1.5">
                                    <form action="{{ route('admin.projects.set-feature', [$p->id, 'is_hero_story']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border transition-colors {{ $p->is_hero_story ? 'bg-blue-100 text-blue-900 border-blue-300 shadow-2xs hover:bg-blue-200' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-slate-100 hover:text-slate-700' }}" title="Click to set/unset exclusive Main Hero Story (under top 2 cards)">
                                            <span>{{ $p->is_hero_story ? '★ Main Hero Story' : '+ Main Hero Story' }}</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- View Public Magazine Project -->
                                    <a href="{{ route('projects.show', $p->slug) }}" 
                                       target="_blank" 
                                       title="View on Website" 
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-all shadow-2xs hover:scale-105">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>

                                    <!-- Check & Edit -->
                                    <a href="{{ route('admin.projects.edit', $p->id) }}" 
                                       title="Check & Edit" 
                                       class="w-8 h-8 rounded-lg bg-white hover:bg-slate-100 text-slate-700 hover:text-slate-950 border border-slate-300 flex items-center justify-center transition-all shadow-2xs hover:scale-105 hover:border-slate-400">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    @if($p->status === 'pending')
                                        <form action="{{ route('admin.projects.approve', $p->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    title="Approve & Publish" 
                                                    class="w-8 h-8 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center transition-all shadow-xs hover:scale-105 cursor-pointer">
                                                <i class="fa-solid fa-check text-xs"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.projects.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this project?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete Project" 
                                                class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 flex items-center justify-center transition-all shadow-2xs hover:scale-105 hover:text-rose-700 cursor-pointer">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                No projects found in this section.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $projects->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
