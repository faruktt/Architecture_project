@extends('layouts.admin')

@section('title', 'Product Catalog Management | nook Admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Products</h1>
        </div>
        <a href="{{ route('admin.products.create') }}" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition-all shadow-xs flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus text-blue-400 text-xs"></i>
            <span>Add Product</span>
        </a>
    </div>

    <!-- Status Tabs -->
    <div class="flex flex-wrap items-center gap-2 text-xs">
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ !$status ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200 hover:bg-slate-50' }}">
            All Products ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white text-amber-800 hover:text-amber-900 border border-amber-200 hover:bg-amber-50/50' }}">
            Pending Review ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-emerald-700 hover:text-emerald-800 border border-emerald-200 hover:bg-emerald-50/50' }}">
            Approved & Live ({{ $counts['approved'] }})
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-rose-700 hover:text-rose-800 border border-rose-200 hover:bg-rose-50/50' }}">
            Rejected ({{ $counts['rejected'] }})
        </a>
    </div>

    <!-- Products Table -->
    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[700px]">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Product & Manufacturer</th>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-6 py-3.5">Website Link</th>
                        <th class="px-6 py-3.5">Review Status</th>
                        <th class="px-6 py-3.5">Property Sell</th>
                        <th class="px-6 py-3.5">BIM</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @forelse($products as $prod)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3.5">
                                <img src="{{ $prod->featured_image }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shrink-0 shadow-2xs">
                                <div>
                                    <strong class="text-slate-900 block font-bold text-xs">{{ $prod->title }}</strong>
                                    <span class="text-[11px] text-slate-500 font-semibold uppercase mt-0.5 block">{{ $prod->manufacturer }}</span>
                                    @if($prod->author)
                                        <span class="text-[10px] text-slate-400 block">Submitted by: {{ $prod->author->name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-700 border border-slate-200/80 px-2 py-0.5 rounded-md text-[11px] font-medium">{{ $prod->category }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($prod->website_url)
                                    <a href="{{ $prod->website_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline truncate max-w-xs block font-mono text-[11px]">
                                        {{ $prod->website_url }} &nearr;
                                    </a>
                                @else
                                    <span class="text-slate-400">None</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($prod->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Approved & Live
                                    </span>
                                @elseif($prod->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.products.toggle-property-sell', $prod->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold border transition-colors {{ $prod->is_property_sell ? 'bg-emerald-100 text-emerald-900 border-emerald-300 shadow-2xs hover:bg-emerald-200' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-slate-100 hover:text-slate-700' }}" title="Click to toggle homepage Property Sell Post display">
                                        <span>{{ $prod->is_property_sell ? '★ In Property Sell' : '+ Add to Property Sell' }}</span>
                                    </button>
                                </form>
                                @if($prod->price)
                                    <span class="text-[10px] text-slate-500 font-semibold block mt-1">{{ $prod->price }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($prod->bim_file_url)
                                    <span class="inline-block bg-blue-50 text-blue-800 border border-blue-200 text-[10px] font-bold px-2 py-0.5 rounded">BIM Ready</span>
                                @else
                                    <span class="text-slate-400 text-[11px]">No BIM</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- View Public Product -->
                                    <a href="{{ route('products.show', $prod->slug) }}" 
                                       target="_blank" 
                                       title="View on Website" 
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-all shadow-2xs hover:scale-105">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>

                                    <!-- Check & Edit -->
                                    <a href="{{ route('admin.products.edit', $prod->id) }}" 
                                       title="Check & Edit" 
                                       class="w-8 h-8 rounded-lg bg-white hover:bg-slate-100 text-slate-700 hover:text-slate-950 border border-slate-300 flex items-center justify-center transition-all shadow-2xs hover:scale-105 hover:border-slate-400">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    @if($prod->status === 'pending')
                                        <form action="{{ route('admin.products.approve', $prod->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    title="Approve & Publish" 
                                                    class="w-8 h-8 rounded-lg bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition-all shadow-xs hover:scale-105 cursor-pointer">
                                                <i class="fa-solid fa-check text-xs"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete Product" 
                                                class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 flex items-center justify-center transition-all shadow-2xs hover:scale-105 hover:text-rose-700 cursor-pointer">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                No products found in this section.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
