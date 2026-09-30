@extends('layouts.admin')

@section('title', 'Edit News: ' . $newsItem->title)

@section('content')
<div class="max-w-4xl mx-auto py-2 space-y-6">

    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Edit News: {{ $newsItem->title }}</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('news.show', $newsItem->slug) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                <span>View Live &nearr;</span>
            </a>
            <a href="{{ route('admin.news.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-semibold flex items-center gap-1 transition-colors">
                <span>&larr; Back to News</span>
            </a>
        </div>
    </div>

    <form action="{{ route('admin.news.update', $newsItem->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/90 shadow-sm text-xs">
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
                <label class="block font-semibold text-slate-700 mb-1.5">News Headline / Title *</label>
                <input type="text" name="title" required value="{{ old('title', $newsItem->title) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Slug *</label>
                <input type="text" name="slug" required value="{{ old('slug', $newsItem->slug) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-mono focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Category *</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $newsItem->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Reporter / Author *</label>
                    <input type="text" name="author_name" required value="{{ old('author_name', $newsItem->author_name) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Status *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-bold focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        <option value="published" {{ old('status', $newsItem->status) === 'published' ? 'selected' : '' }}>Published & Live</option>
                        <option value="draft" {{ old('status', $newsItem->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

            <!-- Media Badges: Video & Audio -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                <div class="font-bold text-slate-800 text-xs">Media Features & Badges (Image 2 style)</div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="inline-flex items-center gap-2 cursor-pointer font-semibold text-slate-700 mt-2">
                            <input type="checkbox" name="has_audio" value="1" {{ old('has_audio', $newsItem->has_audio) ? 'checked' : '' }} class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500">
                            <span>Audio available (🎧)</span>
                        </label>
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-2 cursor-pointer font-semibold text-slate-700 mt-2">
                            <input type="checkbox" name="has_video" value="1" {{ old('has_video', $newsItem->has_video) ? 'checked' : '' }} class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500">
                            <span>Video feature included</span>
                        </label>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Image Pill Badge (e.g. ▷ Videos)</label>
                        <input type="text" name="badge_text" value="{{ old('badge_text', $newsItem->badge_text) }}" class="w-full px-3 py-1.5 bg-white border border-slate-300 text-slate-900 rounded-lg text-xs outline-none" placeholder="▷ Videos">
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">News Summary / Lead Paragraph *</label>
                <textarea name="summary" rows="3" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">{{ old('summary', $newsItem->summary) }}</textarea>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Full News Story (Rich Narrative: Text, Insert Photos, More Text) *</label>
                <x-rich-editor name="content" :value="old('content', $newsItem->content)" placeholder="Write your full news story here..." minHeight="350px" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                    <label class="block font-bold text-slate-800">Featured Hero Image</label>
                    <div class="mb-2 relative">
                        <img src="{{ $newsItem->image }}" class="w-full h-32 object-cover rounded-xl border border-slate-200 shadow-2xs">
                        @if($newsItem->badge_text)
                            <span class="absolute top-2 left-2 bg-white/90 backdrop-blur-xs text-slate-900 font-bold px-2 py-0.5 rounded text-[11px] border border-slate-300">
                                {{ $newsItem->badge_text }}
                            </span>
                        @endif
                    </div>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800">
                    <span class="text-[11px] text-slate-400 block">Or external Image URL:</span>
                    <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs">
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                    <label class="block font-bold text-slate-800">Thumbnail Gallery Strip</label>
                    @if(is_array($newsItem->gallery) && count($newsItem->gallery) > 0)
                        <div class="grid grid-cols-5 gap-1.5 mb-2">
                            @foreach($newsItem->gallery as $gImg)
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
            <a href="{{ route('admin.news.index') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2">
                <span>Save News Changes</span>
                <span>&rarr;</span>
            </button>
        </div>

    </form>
</div>
@endsection
