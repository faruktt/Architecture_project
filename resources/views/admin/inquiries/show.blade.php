@extends('layouts.admin')

@section('title', 'View Inquiry | nook Admin')

@section('content')
<div class="max-w-3xl mx-auto py-2 space-y-6">

    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">Client Communication</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Client Product Inquiry</h1>
            <p class="text-xs text-slate-500 mt-1">Received on {{ $inquiry->created_at->format('F d, Y at H:i A') }}</p>
        </div>
        <a href="{{ route('admin.inquiries.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-semibold flex items-center gap-1 transition-colors">
            <span>&larr; Back to Inquiries</span>
        </a>
    </div>

    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 space-y-6 shadow-sm text-xs">
        <div class="border-b border-slate-100 pb-4">
            <span class="text-slate-400 uppercase text-[10px] font-bold block mb-1">Inquiry Regarding Product</span>
            @if($inquiry->product)
                <h2 class="text-base font-bold text-slate-900">{{ $inquiry->product->title }}</h2>
                <p class="text-slate-500 text-xs mt-0.5">Manufacturer: <strong class="text-slate-800">{{ $inquiry->product->manufacturer }}</strong></p>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-4">
            <div>
                <span class="text-slate-400 uppercase text-[10px] font-bold block mb-0.5">Sender Name</span>
                <strong class="text-slate-900 text-sm font-bold">{{ $inquiry->name }}</strong>
            </div>
            <div>
                <span class="text-slate-400 uppercase text-[10px] font-bold block mb-0.5">Company / Firm</span>
                <span class="text-slate-700 font-medium">{{ $inquiry->company ?? 'Not specified' }}</span>
            </div>
            <div>
                <span class="text-slate-400 uppercase text-[10px] font-bold block mb-0.5">Email</span>
                <a href="mailto:{{ $inquiry->email }}" class="text-blue-600 hover:underline font-mono">{{ $inquiry->email }}</a>
            </div>
            <div>
                <span class="text-slate-400 uppercase text-[10px] font-bold block mb-0.5">Phone</span>
                <span class="text-slate-700 font-medium">{{ $inquiry->phone ?? 'Not specified' }}</span>
            </div>
        </div>

        <div>
            <span class="text-slate-400 uppercase text-[10px] font-bold block mb-2">Message Body</span>
            <div class="p-5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm leading-relaxed whitespace-pre-line font-normal">
                {{ $inquiry->message }}
            </div>
        </div>

        <div class="pt-4 flex items-center justify-between">
            <a href="mailto:{{ $inquiry->email }}?subject=Regarding {{ $inquiry->product ? urlencode($inquiry->product->title) : 'Product' }}" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition-all shadow-sm hover:shadow flex items-center gap-2">
                <span>Reply via Direct Email</span>
                <span>&rarr;</span>
            </a>

            <form action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST" onsubmit="return confirm('Delete this inquiry?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-semibold px-3 py-1.5 rounded-lg hover:bg-rose-50 transition-colors">
                    Delete Inquiry
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
