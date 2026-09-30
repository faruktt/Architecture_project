@extends('layouts.author')

@section('title', 'Submit Your Project | nook MAGAZINE')

@section('content')
<div class="max-w-4xl mx-auto py-4">

    <!-- Header -->
    <div class="border-b border-zinc-200 pb-5 mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900">Submit Your Project</h1>
            <p class="text-xs text-zinc-500 mt-1">Provide project information, narrative, drawings, and photography for editorial evaluation.</p>
        </div>
        <a href="{{ route('author.projects.index') }}" class="text-xs text-zinc-600 hover:text-black font-semibold inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Back to My Projects</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs mb-6">
            <div class="font-bold mb-1">Please correct the following errors:</div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Editorial Policy Notice -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 sm:mb-8 text-xs text-amber-900 leading-relaxed flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
        <div>
            <strong class="font-bold block text-slate-900">Editorial Approval Workflow:</strong>
            When you complete this form and submit, your project will be marked as <span class="bg-amber-200 text-amber-900 px-1.5 py-0.5 rounded font-bold uppercase text-[10px]">Pending</span>. The Chief Editor will review, verify specifications, and publish live across nook MAGAZINE.
        </div>
    </div>

    <!-- Submission Form -->
    <form action="{{ route('author.projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-white p-4 sm:p-10 rounded-2xl border border-zinc-200 shadow-sm">
        @csrf

        <!-- SECTION 1: PROJECT IDENTITY & TEXT -->
        <div class="space-y-4">
            <div class="flex items-center gap-2 border-b border-zinc-100 pb-2">
                <span class="w-6 h-6 rounded-full bg-zinc-900 text-white flex items-center justify-center text-xs font-bold">1</span>
                <h3 class="text-sm font-bold text-zinc-900 uppercase tracking-wide">Project Title & Story (লেখা)</h3>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Project Title *</label>
                <input type="text" name="title" required value="{{ old('title') }}"
                       class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded-xl focus:outline-none focus:border-black transition-colors"
                       placeholder="e.g. Modernist Cliffside Villa & Reflection Pool">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Subtitle / Headline</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}"
                       class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded-xl focus:outline-none focus:border-black transition-colors"
                       placeholder="e.g. An exploration of floating microclimates and resilient teak bulkheads">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Lead Excerpt / Summary *</label>
                <textarea name="excerpt" rows="3" required class="w-full px-3.5 py-2.5 text-xs border border-zinc-300 rounded-xl focus:outline-none focus:border-black leading-relaxed transition-colors" placeholder="Write a compelling lead paragraph summarizing the architectural concept, context, and innovation...">{{ old('excerpt') }}</textarea>
                <span class="text-[10px] text-zinc-400 mt-1 block">This excerpt will be displayed on the magazine cards and project header.</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Full Project Narrative / Story *</label>
                <p class="text-[11px] text-zinc-500 mb-2">Write your architectural story, description, design philosophy, materials used, and insert photos inline.</p>
                <x-rich-editor name="content" :value="old('content')" />
            </div>
        </div>

        <!-- SECTION 2: PROJECT SPECIFICATIONS (SPECIFIED BY USER) -->
        <div class="space-y-5 pt-6 border-t border-zinc-200">
            <div class="flex items-center gap-2 border-b border-zinc-100 pb-2">
                <span class="w-6 h-6 rounded-full bg-zinc-900 text-white flex items-center justify-center text-xs font-bold">2</span>
                <h3 class="text-sm font-bold text-zinc-900 uppercase tracking-wide">Project Specifications</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">

                <!-- 1. Lead Architects -->
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Lead Architects</label>
                    <input type="text"
                           name="lead_architects"
                           value="{{ old('lead_architects') }}"
                           class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl focus:outline-none focus:border-black transition-colors"
                           placeholder="e.g. Ar. Tariq Ahmed, Atelier Studio">
                </div>

                <!-- 2. Associate -->
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Associate</label>
                    <input type="text"
                           name="associate"
                           value="{{ old('associate') }}"
                           class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl focus:outline-none focus:border-black transition-colors"
                           placeholder="e.g. Associate Architects, Design Associates">
                </div>

                <!-- 3. Area -->
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Area</label>
                    <input type="text"
                           name="area"
                           value="{{ old('area') }}"
                           class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl focus:outline-none focus:border-black transition-colors"
                           placeholder="000 m²">
                </div>

                <!-- 4. Build Year -->
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Build Year</label>
                    <input type="text"
                           name="build_year"
                           value="{{ old('build_year', old('year', date('Y'))) }}"
                           class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl focus:outline-none focus:border-black transition-colors"
                           placeholder="e.g. 2024">
                </div>

                <!-- 5. Photographer -->
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Photographer</label>
                    <input type="text"
                           name="photographer"
                           value="{{ old('photographer') }}"
                           class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl focus:outline-none focus:border-black transition-colors"
                           placeholder="e.g. Iwan Baan, Fernando Guerra">
                </div>

                <!-- 6. Category -->
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Category *</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl focus:outline-none focus:border-black bg-white transition-colors">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 7. Illustrations -->
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Illustrations</label>
                    <input type="text"
                           name="illustrations"
                           value="{{ old('illustrations') }}"
                           class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl focus:outline-none focus:border-black transition-colors"
                           placeholder="e.g. Diagram / Illustration Credits or Studio">
                </div>

                <!-- 8. City -->
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">City</label>
                    <input type="text"
                           name="city"
                           value="{{ old('city') }}"
                           class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl focus:outline-none focus:border-black transition-colors"
                           placeholder="e.g. Bali, Dhaka, Tokyo, Kuala Lumpur">
                </div>

                <!-- 9. Country -->
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Country *</label>
                    <select name="country" required class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl focus:outline-none focus:border-black bg-white transition-colors">
                        @foreach($countries as $cnt)
                            <option value="{{ $cnt }}" {{ old('country') === $cnt ? 'selected' : '' }}>{{ $cnt }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 10. Phone Number -->
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Phone Number</label>
                    <input type="text"
                           name="phone_number"
                           value="{{ old('phone_number') }}"
                           class="w-full px-3.5 py-2.5 border border-zinc-300 rounded-xl focus:outline-none focus:border-black transition-colors"
                           placeholder="e.g. +60 12-345 6789">
                </div>

                <!-- 11. Web address -->
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-zinc-700 mb-1">Web address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                            <i class="fa-solid fa-globe text-xs"></i>
                        </span>
                        <input type="url"
                               name="web_address"
                               value="{{ old('web_address') }}"
                               class="w-full pl-8 pr-3.5 py-2.5 border border-zinc-300 rounded-xl focus:outline-none focus:border-black transition-colors font-mono text-[11px]"
                               placeholder="https://www.architectstudio.com">
                    </div>
                </div>

            </div>
        </div>

        <!-- SECTION 3: PROJECT PHOTOGRAPHY & IMAGES (ছবি) -->
        <div class="space-y-4 pt-6 border-t border-zinc-200">
            <div class="flex items-center gap-2 border-b border-zinc-100 pb-2">
                <span class="w-6 h-6 rounded-full bg-zinc-900 text-white flex items-center justify-center text-xs font-bold">3</span>
                <h3 class="text-sm font-bold text-zinc-900 uppercase tracking-wide">Project Photos & Drawings (ছবি)</h3>
            </div>

            <x-direct-image-upload />
        </div>

        <!-- SECTION 4: PROPERTY SELL POST (OPTIONAL) -->
        <div class="p-4 sm:p-5 bg-zinc-50 border border-zinc-200 rounded-2xl space-y-3">
            <label class="flex items-center gap-2.5 text-xs font-semibold text-zinc-800 cursor-pointer">
                <input type="checkbox" name="is_property_sell" value="1" {{ old('is_property_sell') ? 'checked' : '' }} class="rounded border-zinc-300 text-black focus:ring-0">
                <span>Feature this project in "PROPERTY SELL POST" section (Right sidebar of homepage)</span>
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                <div>
                    <label class="block text-[11px] text-zinc-500 mb-1">Listing Price (Optional for sell post)</label>
                    <input type="text" name="price" value="{{ old('price') }}"
                           class="w-full px-3.5 py-2 text-xs border border-zinc-300 rounded-xl focus:outline-none focus:border-black bg-white"
                           placeholder="e.g. $1,450,000 USD">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-6 border-t border-zinc-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-zinc-500">Status will be initialized as <strong>Pending Approval</strong>.</span>
            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-black hover:bg-zinc-800 text-white text-xs font-bold rounded-xl uppercase tracking-wider transition-colors shadow flex items-center justify-center gap-2 cursor-pointer">
                <span>Submit Project For Review</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </button>
        </div>

    </form>

</div>
@endsection
