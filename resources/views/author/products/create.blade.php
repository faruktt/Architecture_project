@extends('layouts.author')

@section('title', 'Submit Product Specification | nook')

@section('content')
<div class="max-w-4xl mx-auto py-4">

    <div class="border-b border-zinc-200 pb-5 mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900">Submit Architecture Product</h1>
            <p class="text-xs text-zinc-500 mt-1">Submit building systems, interior finishes, luminaires, and software for catalog inclusion.</p>
        </div>
        <a href="{{ route('author.products.index') }}" class="text-xs text-zinc-600 hover:text-black font-semibold inline-flex items-center gap-1">
            &larr; Back to My Products
        </a>
    </div>

    <!-- Notice -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 sm:mb-8 text-xs text-blue-900 leading-relaxed flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
        <div>
            <strong class="font-bold block">Product Approval Workflow:</strong>
            Submitted products will enter status <span class="bg-blue-200 text-blue-900 px-1.5 py-0.5 rounded font-bold uppercase text-[10px]">Pending</span>. The Editorial Admin can review, edit all fields (including website links and technical parameters), and approve the product to appear in the public Architecture Products catalog!
        </div>
    </div>

    <form action="{{ route('author.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-white p-4 sm:p-10 rounded-xl border border-zinc-200 shadow-sm">
        @csrf

        <div class="space-y-4">
            <h3 class="text-sm font-bold text-zinc-900 uppercase tracking-wide border-b border-zinc-100 pb-2">1. Product & Manufacturer Identity</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Product Title *</label>
                    <input type="text" name="title" required value="{{ old('title') }}"
                           class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                           placeholder="e.g. BIM Presentation and Communication - BIMx">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Manufacturer / Brand Name *</label>
                    <input type="text" name="manufacturer" required value="{{ old('manufacturer') }}"
                           class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                           placeholder="e.g. GRAPHISOFT or ZURN ELKAY">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Category *</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Country / Region</label>
                    <input type="text" name="country_region" value="{{ old('country_region', 'Global') }}"
                           class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                           placeholder="e.g. Global, Germany, USA, Bangladesh">
                </div>
            </div>

            <!-- Website Link Field (Specified in User Prompt: "porduct ar website link add kora jabe") -->
            <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-lg space-y-3">
                <div>
                    <label class="block text-xs font-bold text-zinc-800 mb-1">
                        🌐 Official Product Website Link (Clickable external link)
                    </label>
                    <input type="url" name="website_url" value="{{ old('website_url') }}"
                           class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                           placeholder="https://graphisoft.com/solutions/products/bimx">
                    <span class="text-[11px] text-zinc-500 mt-1 block">
                        This URL will be linked to the "Website" button on the product details page.
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Direct Contact Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                           placeholder="+36 1 437 3000">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Direct Sales / Inquiries Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                           placeholder="sales@manufacturer.com">
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 text-xs font-semibold text-zinc-800 cursor-pointer">
                    <input type="checkbox" name="has_bim" value="1" {{ old('has_bim', true) ? 'checked' : '' }} class="rounded border-zinc-300 text-black">
                    <span>Includes BIM Files & 3D Objects (Archicad, Revit, IFC)</span>
                </label>
            </div>
        </div>

        <!-- Section 2: Technical Specifications & Breakdown (Matching Image 3) -->
        <div class="space-y-4 pt-4 border-t border-zinc-200">
            <h3 class="text-sm font-bold text-zinc-900 uppercase tracking-wide border-b border-zinc-100 pb-2">2. Product Specifications (Image 3 Breakdown)</h3>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Short Overview *</label>
                <textarea name="short_description" rows="2" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black" placeholder="A brief summary of what this architectural product does...">{{ old('short_description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Use / Full Description (Write Text & Upload Inline Photos)</label>
                <x-rich-editor name="use_description" :value="old('use_description')" placeholder="Detailed description, installation guide, application context. Write text, insert 4-5 photos, write more text..." minHeight="240px" />
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Applications (Image 3 field)</label>
                <textarea name="applications" rows="2" class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black" placeholder="e.g. Interactive exploration of BIM projects; review of linked 2D documentation and 3D models...">{{ old('applications') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Characteristics (Image 3 field)</label>
                <textarea name="characteristics" rows="2" class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black" placeholder="e.g. Multi-platform BIM viewer for desktop, mobile devices, web...">{{ old('characteristics') }}</textarea>
            </div>
        </div>

        <x-direct-image-upload 
            featured-label="Product Primary Image"
            featured-help="Directly upload high-resolution product photography. Stored in public/uploads/."
            gallery-label="Product Gallery & Applications (Select 4-5 Photos)"
            gallery-help="Select multiple detail photos, materials, and installed application shots."
        />

        <div class="pt-6 border-t border-zinc-200 flex items-center justify-between">
            <span class="text-xs text-zinc-500">Submissions will be reviewed by admin prior to catalog publishing.</span>
            <button type="submit" class="px-8 py-3 bg-[#003882] text-white hover:bg-[#002860] text-xs font-bold rounded-lg uppercase tracking-wider transition-colors shadow">
                Submit Product For Review &rarr;
            </button>
        </div>

    </form>
</div>
@endsection
