@extends('layouts.admin')

@section('title', 'News & Articles | nook Admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Articles</h1>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition-all shadow-xs flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus text-rose-400 text-xs"></i>
            <span>Write Article</span>
        </a>
    </div>

    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[650px]">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Cover & Title</th>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-6 py-3.5">Author</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Published</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($articles as $art)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3.5">
                                <img src="{{ $art->image }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shrink-0 shadow-2xs">
                                <div>
                                    <strong class="text-slate-900 block font-bold text-xs">{{ $art->title }}</strong>
                                    <span class="text-[11px] text-slate-500 line-clamp-1 max-w-sm mt-0.5">{{ $art->summary }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-rose-50 text-rose-800 border border-rose-200/80 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase">{{ $art->category }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-medium">{{ $art->author_name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $art->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $art->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-medium">
                                {{ $art->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('articles.show', $art->slug) }}" 
                                       target="_blank" 
                                       title="View on Website" 
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-all shadow-2xs hover:scale-105">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.articles.edit', $art->id) }}" 
                                       title="Edit Article" 
                                       class="w-8 h-8 rounded-lg bg-white hover:bg-slate-100 text-slate-700 hover:text-slate-950 border border-slate-300 flex items-center justify-center transition-all shadow-2xs hover:scale-105 hover:border-slate-400">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this article?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete Article" 
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
                                No articles published yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
