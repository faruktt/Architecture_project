@extends('layouts.author')

@section('title', 'Submit Your Project | nook MAGAZINE')

@section('content')
<div class="max-w-4xl mx-auto py-4">

    <!-- Header -->
    <div class="border-b border-zinc-200 pb-5 mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900">Submit Your Project</h1>
            <p class="text-xs text-zinc-500 mt-1">Provide project information, drawings, and photography for editorial evaluation.</p>
        </div>
        <a href="{{ route('author.projects.index') }}" class="text-xs text-zinc-600 hover:text-black font-semibold inline-flex items-center gap-1">
            &larr; Back to My Projects
        </a>
    </div>

    <!-- Editorial Policy Notice -->
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6 sm:mb-8 text-xs text-amber-900 leading-relaxed flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
        <div>
            <strong class="font-bold block">Editorial Approval Workflow:</strong>
            When you complete this form and submit, your project will be marked as <span class="bg-amber-200 text-amber-900 px-1.5 py-0.5 rounded font-bold uppercase text-[10px]">Pending</span>. The Editorial Admin will review and can fine-tune or edit any fields if needed. Once approved by Admin, the project will be published live across nook MAGAZINE and public search results.
        </div>
    </div>

    <!-- Submission Form -->
    <form action="{{ route('author.projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-white p-4 sm:p-10 rounded-xl border border-zinc-200 shadow-sm">
        @csrf

        <!-- Section 1: Core Information -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-zinc-900 uppercase tracking-wide border-b border-zinc-100 pb-2">1. Project Identity</h3>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Project Title *</label>
                <input type="text" name="title" required value="{{ old('title') }}"
                       class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                       placeholder="e.g. Architecture on Water: Living at Sea Sanctuary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Subtitle / Headline</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}"
                       class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                       placeholder="e.g. An exploration of floating microclimates and resilient teak bulkheads">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Typology / Category *</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Country / Region *</label>
                    <select name="country" required class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black bg-white">
                        @foreach($countries as $cnt)
                            <option value="{{ $cnt }}" {{ old('country') === $cnt ? 'selected' : '' }}>{{ $cnt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">City / Specific Location</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                           placeholder="e.g. Bali, Indonesia or KL, Malaysia">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Year Completed</label>
                    <input type="text" name="year" value="{{ old('year', date('Y')) }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                           placeholder="e.g. 2024">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Gross Built Area</label>
                    <input type="text" name="area" value="{{ old('area') }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                           placeholder="e.g. 480 m²">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Collaborating Consultants / Firms</label>
                <input type="text" name="collaboration" value="{{ old('collaboration') }}"
                       class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                       placeholder="e.g. In Collaboration with Studio Drift & Oceanica">
            </div>

            <!-- Property Sell Option -->
            <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-lg space-y-3">
                <label class="flex items-center gap-2 text-xs font-semibold text-zinc-800 cursor-pointer">
                    <input type="checkbox" name="is_property_sell" value="1" {{ old('is_property_sell') ? 'checked' : '' }} class="rounded border-zinc-300 text-black focus:ring-0">
                    <span>Feature this in "PROPERTY SELL POST" section (Right sidebar of homepage)</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block text-[11px] text-zinc-500 mb-1">Listing Price (Optional for sell post)</label>
                        <input type="text" name="price" value="{{ old('price') }}"
                               class="w-full px-3 py-1.5 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black"
                               placeholder="e.g. $1,450,000 USD">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Narrative & Editorial Content -->
        <div class="space-y-4 pt-4 border-t border-zinc-200">
            <h3 class="text-sm font-bold text-zinc-900 uppercase tracking-wide border-b border-zinc-100 pb-2">2. Narrative & Editorial Content</h3>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Lead Excerpt / Summary * (Shown on cards and article lead)</label>
                <textarea name="excerpt" rows="3" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black leading-relaxed" placeholder="Write a compelling lead paragraph summarizing the architectural concept, context, and innovation...">{{ old('excerpt') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Full Project Narrative / Story *</label>
                <p class="text-[11px] text-zinc-500 mb-2">Write your narrative, insert 4-5 photos, write more text, and add more photos directly into the story.</p>
                <x-rich-editor name="content" :value="old('content')" />
            </div>
        </div>

        <x-direct-image-upload />

        <!-- Submit Button -->
        <div class="pt-6 border-t border-zinc-200 flex items-center justify-between">
            <span class="text-xs text-zinc-500">Status will be initialized as <strong>Pending Approval</strong>.</span>
            <button type="submit" class="px-8 py-3 bg-black text-white hover:bg-zinc-800 text-xs font-bold rounded-lg uppercase tracking-wider transition-colors shadow">
                Submit Project For Review &rarr;
            </button>
        </div>

    </form>

</div>
@endsection
