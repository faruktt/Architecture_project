@extends('layouts.admin')

@section('title', 'Write Article | nook Admin')

@section('content')
<div class="max-w-4xl mx-auto py-2 space-y-6">

    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md">Article Studio</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Write New Architectural Article</h1>
            <p class="text-xs text-slate-500 mt-1">Publish long-form essays, interviews, criticism, and project studies.</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-semibold flex items-center gap-1 transition-colors">
            <span>&larr; Back to Articles</span>
        </a>
    </div>

    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/90 shadow-sm text-xs">
        @csrf

        @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs">
            <div class="font-bold mb-1.5 flex items-center gap-1.5 text-rose-900">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/><line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"/></svg>
                <span>Please correct the following errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="space-y-4">
            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Article Title *</label>
                <input type="text" name="title" required value="{{ old('title') }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="e.g. From Garden to Monument: Liberation Landscapes in African Cities">
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
                    <label class="block font-semibold text-slate-700 mb-1.5">Author Name *</label>
                    <input type="text" name="author_name" required value="{{ old('author_name', 'Nook Editorial Board') }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Status *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-bold focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        <option value="published" selected>Published & Live</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                <div class="font-bold text-slate-800 text-xs">Article Badges & Audio Availability</div>
                <div class="flex flex-wrap items-center gap-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                        <input type="checkbox" name="has_audio" value="1" {{ old('has_audio', true) ? 'checked' : '' }} class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500">
                        <span>Audio available (🎧 Audio available)</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <label class="font-semibold text-slate-700">Badge Text (Optional):</label>
                        <input type="text" name="badge_text" value="{{ old('badge_text') }}" placeholder="e.g. In-Depth, Featured" class="px-3 py-1.5 bg-white border border-slate-300 text-slate-900 rounded-lg text-xs outline-none">
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Summary / Excerpt (Shows above gallery on feed) *</label>
                <textarea name="summary" rows="3" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="Liberation does not always begin with a monument. It may begin in a garden, a yard, a clearing...">{{ old('summary') }}</textarea>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Article Body (Rich Narrative: Text, Insert 4-5 Photos, More Text) *</label>
                <x-rich-editor name="content" :value="old('content')" placeholder="Write your full architectural story here. Click the button to insert 4-5 photos into narrative, write more paragraphs..." minHeight="350px" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                    <label class="block font-bold text-slate-800">Main Cover Image *</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800">
                    <span class="text-[11px] text-slate-400 block">Or external Image URL:</span>
                    <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs">
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                    <label class="block font-bold text-slate-800">Thumbnail Gallery Strip (4-8+ Photos)</label>
                    <input type="file" name="gallery_files[]" multiple accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800">
                    <p class="text-[11px] text-slate-400">These will appear as the 5-item thumbnail strip below the post with the "+4" overlay.</p>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
            <a href="{{ route('admin.articles.index') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2">
                <span>Publish Article</span>
                <span>&rarr;</span>
            </button>
        </div>

    </form>
</div>
@endsection
