@extends('layouts.admin')

@section('title', 'Edit Article: ' . $article->title)

@section('content')
<div class="max-w-4xl mx-auto py-2 space-y-6">

    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md">Article Revision</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Article: {{ $article->title }}</h1>
            <p class="text-xs text-slate-500 mt-1">Live URL: <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline font-semibold">/articles/{{ $article->slug }} &nearr;</a></p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-semibold flex items-center gap-1 transition-colors">
            <span>&larr; Back to Articles</span>
        </a>
    </div>

    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/90 shadow-sm text-xs">
        @csrf
        @method('PUT')

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
                <input type="text" name="title" required value="{{ old('title', $article->title) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Slug *</label>
                <input type="text" name="slug" required value="{{ old('slug', $article->slug) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-mono focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Category *</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $article->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Author Name *</label>
                    <input type="text" name="author_name" required value="{{ old('author_name', $article->author_name) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Status *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-bold focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Published & Live</option>
                        <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                <div class="font-bold text-slate-800 text-xs">Article Badges & Audio Availability</div>
                <div class="flex flex-wrap items-center gap-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                        <input type="checkbox" name="has_audio" value="1" {{ old('has_audio', $article->has_audio) ? 'checked' : '' }} class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500">
                        <span>Audio available (🎧 Audio available)</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <label class="font-semibold text-slate-700">Badge Text (Optional):</label>
                        <input type="text" name="badge_text" value="{{ old('badge_text', $article->badge_text) }}" placeholder="e.g. In-Depth, Featured" class="px-3 py-1.5 bg-white border border-slate-300 text-slate-900 rounded-lg text-xs outline-none">
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Summary / Excerpt *</label>
                <textarea name="summary" rows="3" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">{{ old('summary', $article->summary) }}</textarea>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Article Body (Rich Narrative: Text, Insert 4-5 Photos, More Text) *</label>
                <x-rich-editor name="content" :value="old('content', $article->content)" placeholder="Write your full architectural story here..." minHeight="350px" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                    <label class="block font-bold text-slate-800">Cover Image</label>
                    <div class="mb-2">
                        <img src="{{ $article->image }}" class="w-full h-32 object-cover rounded-xl border border-slate-200 shadow-2xs">
                    </div>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800">
                    <span class="text-[11px] text-slate-400 block">Or external Image URL:</span>
                    <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs">
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                    <label class="block font-bold text-slate-800">Thumbnail Gallery Strip</label>
                    @if(is_array($article->gallery) && count($article->gallery) > 0)
                        <div class="grid grid-cols-5 gap-1.5 mb-2">
                            @foreach($article->gallery as $gImg)
                                <img src="{{ $gImg }}" class="aspect-square object-cover rounded-lg border border-slate-200">
                            @endforeach
                        </div>
                    @endif
                    <input type="file" name="gallery_files[]" multiple accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800">
                    <p class="text-[11px] text-slate-400">Upload more photos to add into the thumbnail gallery strip below the post.</p>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
            <a href="{{ route('admin.articles.index') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2">
                <span>Save Article Changes</span>
                <span>&rarr;</span>
            </button>
        </div>

    </form>
</div>
@endsection
