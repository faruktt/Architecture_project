@extends('layouts.author')

@section('title', 'Edit Product | ' . $product->title)

@section('content')
<div class="max-w-4xl mx-auto py-4">

    <div class="border-b border-zinc-200 pb-5 mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900">Edit Product: {{ $product->title }}</h1>
            <p class="text-xs text-zinc-500 mt-1">Status: <span class="uppercase font-bold text-zinc-800">{{ $product->status }}</span></p>
        </div>
        <a href="{{ route('author.products.index') }}" class="text-xs text-zinc-600 hover:text-black font-semibold inline-flex items-center gap-1">
            &larr; Back to My Products
        </a>
    </div>

    <form action="{{ route('author.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-4 sm:p-10 rounded-xl border border-zinc-200 shadow-sm">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Product Title *</label>
                    <input type="text" name="title" required value="{{ old('title', $product->title) }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Manufacturer *</label>
                    <input type="text" name="manufacturer" required value="{{ old('manufacturer', $product->manufacturer) }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Category *</label>
                    <select name="category" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $product->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Country / Region</label>
                    <input type="text" name="country_region" value="{{ old('country_region', $product->country_region) }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-zinc-800 mb-1">
                    🌐 Product Website Link ("porduct ar website link add kora jabe")
                </label>
                <input type="url" name="website_url" value="{{ old('website_url', $product->website_url) }}"
                       class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                       placeholder="https://example.com">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', $product->phone) }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $product->email) }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 text-xs font-semibold text-zinc-800">
                    <input type="checkbox" name="has_bim" value="1" {{ old('has_bim', $product->has_bim) ? 'checked' : '' }} class="rounded border-zinc-300 text-black">
                    <span>Includes BIM Files</span>
                </label>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Short Description *</label>
                <textarea name="short_description" rows="2" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">{{ old('short_description', $product->short_description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Use / Full Description (Write Text & Upload Inline Photos)</label>
                <x-rich-editor name="use_description" :value="old('use_description', $product->use_description)" placeholder="Detailed description, installation guide, application context..." minHeight="240px" />
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Applications</label>
                <textarea name="applications" rows="2" class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">{{ old('applications', $product->applications) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Characteristics</label>
                <textarea name="characteristics" rows="2" class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">{{ old('characteristics', $product->characteristics) }}</textarea>
            </div>

        <x-direct-image-upload 
            featured-label="Product Primary Image"
            featured-help="Directly upload high-resolution product photography. Stored in public/uploads/."
            gallery-label="Product Gallery & Applications (Select 4-5 Photos)"
            gallery-help="Select multiple detail photos, materials, and installed application shots."
            :current-featured="$product->featured_image"
            :current-gallery="$product->gallery"
        />

        <div class="pt-6 border-t border-zinc-200 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-[#003882] text-white hover:bg-[#002860] text-xs font-bold rounded-lg uppercase tracking-wider transition-colors shadow">
                Save & Resubmit Product &rarr;
            </button>
        </div>

    </form>
</div>
@endsection
