@extends('layouts.admin')

@section('title', 'Product Inquiries | nook Admin')

@section('content')
<div class="space-y-6">

    <div class="border-b border-slate-200 pb-5">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">Lead Intelligence</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Client Product Inquiries</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Review contact messages sent by architects and visitors directly to manufacturers.</p>
    </div>

    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[650px]">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Sender</th>
                        <th class="px-6 py-3.5">Product & Manufacturer</th>
                        <th class="px-6 py-3.5">Message Snippet</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Received</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($inquiries as $inq)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <strong class="text-slate-900 block font-bold text-xs">{{ $inq->name }}</strong>
                                <span class="text-[11px] text-slate-500 font-mono">{{ $inq->email }}</span>
                                @if($inq->phone)
                                    <span class="text-[10px] text-slate-400 block mt-0.5">{{ $inq->phone }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($inq->product)
                                    <strong class="text-slate-900 block text-xs font-semibold">{{ $inq->product->title }}</strong>
                                    <span class="text-[10px] text-slate-500 uppercase font-bold">{{ $inq->product->manufacturer }}</span>
                                @else
                                    <span class="text-slate-400">Deleted Product</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate text-slate-500">
                                {{ $inq->message }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $inq->status === 'new' ? 'bg-teal-50 text-teal-800 border border-teal-200' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $inq->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-medium">
                                {{ $inq->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.inquiries.show', $inq->id) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 hover:underline font-bold px-2 py-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>View</span>
                                </a>
                                <form action="{{ route('admin.inquiries.destroy', $inq->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this inquiry?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 text-rose-600 hover:text-rose-800 font-medium px-2 py-1 hover:bg-rose-50 rounded">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                No client inquiries received yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
