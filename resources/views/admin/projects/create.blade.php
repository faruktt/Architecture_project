@extends('layouts.admin')

@section('title', 'Add New Architectural Project | nook Admin')

@section('content')
<div class="max-w-4xl mx-auto py-2 space-y-6">

    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">Editorial Publication</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Create New Architecture Project</h1>
            <p class="text-xs text-slate-500 mt-1">Publish an architectural project directly to the magazine editorial feed.</p>
        </div>
        <a href="{{ route('admin.projects.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-semibold flex items-center gap-1 transition-colors">
            <span>&larr; Back to Projects</span>
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs">
            <strong class="font-bold block mb-1">Please check the required fields:</strong>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/90 shadow-sm text-xs">
        @csrf

        <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Assign Author (Optional)</label>
                    <select name="author_id" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        <option value="">Editorial In-House (No author assigned)</option>
                        @foreach($authors as $a)
                            <option value="{{ $a->id }}" {{ old('author_id') == $a->id ? 'selected' : '' }}>{{ $a->name }} ({{ $a->company ?? $a->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Publication Status *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-bold focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved & Live</option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Draft / Rejected</option>
                    </select>
                </div>
            </div>

            <!-- Homepage Special Features (Exclusive selections) -->
            <div class="pt-3 border-t border-slate-200 space-y-2">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Homepage Exclusive Features (Selecting will automatically unmark previous selection):</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="flex items-center gap-2 font-semibold text-slate-700 cursor-pointer p-2.5 rounded-xl border border-amber-200 bg-amber-50/50 hover:bg-amber-50">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-slate-300 text-amber-600 focus:ring-0">
                        <div>
                            <span class="block text-slate-900 font-bold">Project of the week</span>
                            <span class="text-[10px] text-slate-500 font-normal">Top left feature card (1 only)</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-2 font-semibold text-slate-700 cursor-pointer p-2.5 rounded-xl border border-purple-200 bg-purple-50/50 hover:bg-purple-50">
                        <input type="checkbox" name="is_spotlight" value="1" {{ old('is_spotlight') ? 'checked' : '' }} class="rounded border-slate-300 text-purple-600 focus:ring-0">
                        <div>
                            <span class="block text-slate-900 font-bold">Nook Spotlight</span>
                            <span class="text-[10px] text-slate-500 font-normal">Top right feature card (1 only)</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-2 font-semibold text-slate-700 cursor-pointer p-2.5 rounded-xl border border-blue-200 bg-blue-50/50 hover:bg-blue-50">
                        <input type="checkbox" name="is_hero_story" value="1" {{ old('is_hero_story') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-0">
                        <div>
                            <span class="block text-slate-900 font-bold">Main Hero Story</span>
                            <span class="text-[10px] text-slate-500 font-normal">Big photo & title under cards (1 only)</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Project Title *</label>
                <input type="text" name="title" required value="{{ old('title') }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="e.g. Modernist Cliffside Villa">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Subtitle</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="e.g. In Collaboration with Studio Drift">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Category *</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Country *</label>
                    <select name="country" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        @foreach($countries as $cnt)
                            <option value="{{ $cnt }}" {{ old('country') === $cnt ? 'selected' : '' }}>{{ $cnt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">City / Location</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="e.g. Bali, Indonesia">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Year</label>
                    <input type="text" name="year" value="{{ old('year', date('Y')) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Gross Area</label>
                    <input type="text" name="area" value="{{ old('area') }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="e.g. 520 m²">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Architects / Collaboration</label>
                    <input type="text" name="collaboration" value="{{ old('collaboration', old('architects')) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="e.g. Studio Morph">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Listing Price (Optional)</label>
                    <input type="text" name="price" value="{{ old('price') }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="e.g. $1,200,000">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Short Excerpt (Summary) *</label>
                <textarea name="excerpt" rows="2" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="A compelling summary shown on project cards...">{{ old('excerpt') }}</textarea>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Full Editorial Description / Story (Content) *</label>
                <p class="text-[11px] text-slate-500 mb-2">Write your narrative, insert 4-5 photos, write more text, and add more photos directly into the story.</p>
                <x-rich-editor name="content" :value="old('content', old('description'))" />
            </div>
        </div>

        <x-direct-image-upload />

        <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
            <a href="{{ route('admin.projects.index') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2">
                <span>Publish Project</span>
                <span>&rarr;</span>
            </button>
        </div>
    </form>

</div>
@endsection
