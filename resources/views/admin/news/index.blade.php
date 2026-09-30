@extends('layouts.admin')

@section('title', 'Architecture News | nook Admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Architecture News</h1>
        </div>
        <a href="{{ route('admin.news.create') }}" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition-all shadow-xs flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus text-blue-400 text-xs"></i>
            <span>Post News</span>
        </a>
    </div>

    @if (session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[650px]">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Cover & Headline</th>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-6 py-3.5">Badge</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Published</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($news as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3.5">
                                <div class="relative w-12 h-12 shrink-0">
                                    <img src="{{ $item->image }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-2xs">
                                    @if($item->badge_text)
                                        <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[8px] font-bold px-1 rounded-sm shadow-xs">
                                            {{ $item->badge_text }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <strong class="text-slate-900 block font-bold text-xs">{{ $item->title }}</strong>
                                    <span class="text-[11px] text-slate-500 line-clamp-1 max-w-sm mt-0.5">{{ $item->summary }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-800 border border-blue-200/80 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase">{{ $item->category }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->badge_text)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 border border-slate-200 rounded-md font-medium text-[11px] text-slate-700">
                                        {{ $item->badge_text }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px]">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $item->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-medium">
                                {{ $item->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('news.show', $item->slug) }}" 
                                       target="_blank" 
                                       title="View on Website" 
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-all shadow-2xs hover:scale-105">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.news.edit', $item->id) }}" 
                                       title="Edit News" 
                                       class="w-8 h-8 rounded-lg bg-white hover:bg-slate-100 text-slate-700 hover:text-slate-950 border border-slate-300 flex items-center justify-center transition-all shadow-2xs hover:scale-105 hover:border-slate-400">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this news item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete News" 
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
                                No architecture news published yet. Click above to post one!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pt-4">
        {{ $news->links() }}
    </div>

</div>
@endsection
