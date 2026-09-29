@extends('layouts.admin')

@section('title', 'Add New Product | nook Admin')

@section('content')
<div class="max-w-4xl mx-auto py-2 space-y-6">

    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">Catalog Direct Entry</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Add New Architectural Product</h1>
            <p class="text-xs text-slate-500 mt-1">Add building systems, materials, fixtures, and external manufacturer links.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-semibold flex items-center gap-1 transition-colors">
            <span>&larr; Back to Products</span>
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/90 shadow-sm text-xs">
        @csrf

        @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs">
            <div class="font-bold mb-1.5 flex items-center gap-1.5 text-rose-900">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                    <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                    <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"/>
                </svg>
                <span>Please correct the following errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Assign Author / Manufacturer (Optional)</label>
                    <select name="author_id" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        <option value="">Editorial In-House (No author assigned)</option>
                        @foreach($authors as $a)
                            <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->company ?? $a->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Publication Status *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-bold focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        <option value="approved" selected>Approved & Live</option>
                        <option value="pending">Pending Review</option>
                        <option value="rejected">Draft / Rejected</option>
                    </select>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="flex items-center gap-2 font-semibold text-slate-700 cursor-pointer p-2.5 rounded-xl border border-emerald-200 bg-emerald-50/50 hover:bg-emerald-50">
                    <input type="checkbox" name="is_property_sell" value="1" {{ old('is_property_sell') ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-0">
                    <div>
                        <span class="block text-slate-900 font-bold">Feature in "PROPERTY SELL POST"</span>
                        <span class="text-[10px] text-slate-500 font-normal">Show on homepage right sidebar "PROPERTY SELL POST"</span>
                    </div>
                </label>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Price / Price Range (Optional)</label>
                    <input type="text" name="price" value="{{ old('price') }}" class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="e.g. $450 or Contact for Price">
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Product Title *</label>
                    <input type="text" name="title" required value="{{ old('title') }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="e.g. Minimalist Frameless Glazing System">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Manufacturer / Brand Name *</label>
                    <input type="text" name="manufacturer" required value="{{ old('manufacturer') }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="e.g. Sky-Frame">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Category *</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">BIM / 3D Model Interactive URL</label>
                    <input type="text" name="bim_file_url" value="{{ old('bim_file_url') }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-mono focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="https://bimx.graphisoft.com/model/...">
                </div>
            </div>

            <!-- External Manufacturer Links (User prompt: porduct ar website link add kora jabe) -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                <span class="text-xs font-bold text-slate-900 block">Manufacturer Direct Contact & Website Links</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Official Website Link (URL)</label>
                        <input type="url" name="website_url" value="{{ old('website_url') }}" class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl font-mono text-xs focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none" placeholder="https://manufacturer.com">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Contact Phone</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl text-xs focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none" placeholder="+1 (800) 234-5678">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Contact Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email') }}" class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl text-xs focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none" placeholder="inquiry@brand.com">
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Short Excerpt (Summary) *</label>
                <textarea name="short_description" rows="2" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="Key highlights of this architectural product...">{{ old('short_description', old('excerpt')) }}</textarea>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Full Product Description / Technical Context (Text & Multiple Inline Photos)</label>
                <x-rich-editor name="use_description" :value="old('use_description', old('description'))" placeholder="Detailed architectural description, installation guide, application context. Write text, upload 4-5 photos, write more text..." minHeight="280px" />
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Technical Specifications (Format: Key: Value, one per line)</label>
                <textarea name="specifications_text" rows="4" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-mono text-xs focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="Material: Natural Oak Timber&#10;Thermal Insulation: U-value 0.12 W/m²K&#10;Fire Rating: Class A1 non-combustible">{{ old('specifications_text') }}</textarea>
            </div>
        </div>

        <x-direct-image-upload 
            featured-label="Product Primary Image"
            featured-help="Directly upload high-resolution product photography. Stored in public/uploads/."
            gallery-label="Product Gallery & Applications (Select 4-5 Photos)"
            gallery-help="Select multiple detail photos, materials, and installed application shots."
        />

        <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
            <a href="{{ route('admin.products.index') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2">
                <span>Save Product</span>
                <span>&rarr;</span>
            </button>
        </div>
    </form>

</div>
@endsection
