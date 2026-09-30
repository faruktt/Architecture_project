@extends('layouts.admin')

@section('title', 'Product Inquiries | nook Admin')

@section('content')
<div class="space-y-6">

    <div class="border-b border-slate-200 pb-5">
        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Product Inquiries</h1>
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
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.inquiries.show', $inq->id) }}" 
                                       title="View Inquiry" 
                                       class="w-8 h-8 rounded-lg bg-white hover:bg-slate-100 text-slate-700 hover:text-slate-950 border border-slate-300 flex items-center justify-center transition-all shadow-2xs hover:scale-105 hover:border-slate-400">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.inquiries.destroy', $inq->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this inquiry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete Inquiry" 
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
