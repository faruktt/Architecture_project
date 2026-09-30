@extends('layouts.admin')

@section('title', 'Author Management | nook Editorial Control')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Authors & Architects</h1>
        </div>
        <div class="flex items-center gap-2 bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs">
            <span class="text-xs text-slate-600 font-medium">Total: <strong class="text-slate-900">{{ $counts['total'] }}</strong> ({{ $counts['active'] }} Active, {{ $counts['banned'] }} Suspended)</span>
        </div>
    </div>

    <!-- Search & Filter -->
    <form action="{{ route('admin.authors.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search authors by name, firm, country, or email..." class="px-3.5 py-2.5 text-xs bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none w-full sm:w-80 shadow-2xs">

        <select name="status" onchange="this.form.submit()" class="px-3.5 py-2.5 text-xs bg-white border border-slate-300 text-slate-700 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none shadow-2xs">
            <option value="">All Statuses</option>
            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active Only</option>
            <option value="banned" {{ $status === 'banned' ? 'selected' : '' }}>Suspended / Banned</option>
        </select>

        @if($search || $status)
            <a href="{{ route('admin.authors.index') }}" class="text-xs text-rose-600 hover:underline font-semibold">Clear Search</a>
        @endif
    </form>

    <!-- Authors Table -->
    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[650px]">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Author</th>
                        <th class="px-6 py-3.5">Firm / Designation</th>
                        <th class="px-6 py-3.5">Country</th>
                        <th class="px-6 py-3.5">Submissions</th>
                        <th class="px-6 py-3.5">Followers</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($authors as $author)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3.5">
                                <img src="{{ $author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0 shadow-2xs">
                                <div>
                                    <strong class="text-slate-900 block font-bold text-xs">{{ $author->name }}</strong>
                                    <span class="text-[11px] text-slate-500 font-mono">{{ $author->email }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-900 block">{{ $author->title ?? 'Architect' }}</span>
                                <span class="text-[11px] text-slate-500">{{ $author->company ?? 'Independent' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-700 font-medium">{{ $author->country }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-800 font-bold px-2 py-0.5 rounded text-[11px]">{{ $author->projects_count }} projects</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-purple-700 font-bold bg-purple-50 px-2 py-0.5 rounded text-[11px]">{{ $author->followers_count }} followers</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($author->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        Suspended
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.authors.show', $author->id) }}" 
                                       title="View Submissions" 
                                       class="w-8 h-8 rounded-lg bg-white hover:bg-slate-100 text-slate-700 hover:text-slate-950 border border-slate-300 flex items-center justify-center transition-all shadow-2xs hover:scale-105 hover:border-slate-400">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>

                                    <form action="{{ route('admin.authors.toggle-status', $author->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                title="{{ $author->status === 'active' ? 'Suspend Author' : 'Reactivate Author' }}" 
                                                class="w-8 h-8 rounded-lg {{ $author->status === 'active' ? 'bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200' }} flex items-center justify-center transition-all shadow-2xs hover:scale-105 cursor-pointer">
                                            @if($author->status === 'active')
                                                <i class="fa-solid fa-ban text-xs"></i>
                                            @else
                                                <i class="fa-solid fa-check text-xs"></i>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                No authors found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($authors->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $authors->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
