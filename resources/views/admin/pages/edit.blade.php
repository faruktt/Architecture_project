@extends('layouts.admin')

@section('title', 'Edit Page: ' . $page->title)

@section('content')
<div class="max-w-4xl mx-auto py-2 space-y-6">

    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">CMS Editor</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit CMS Page: {{ $page->title }}</h1>
            <p class="text-xs text-slate-500 mt-1">Live URL: <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline font-semibold">/page/{{ $page->slug }} &nearr;</a></p>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-semibold flex items-center gap-1 transition-colors">
            <span>&larr; Back to Pages</span>
        </a>
    </div>

    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" class="space-y-6 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/90 shadow-sm text-xs">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Page Title *</label>
                    <input type="text" name="title" required value="{{ old('title', $page->title) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">URL Slug *</label>
                    <input type="text" name="slug" required value="{{ old('slug', $page->slug) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-mono focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">SEO Meta Description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description', $page->meta_description) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Publication Status *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-bold focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        <option value="published" {{ old('status', $page->status) === 'published' ? 'selected' : '' }}>Published & Live (Shows in Footer)</option>
                        <option value="draft" {{ old('status', $page->status) === 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Display Order in Footer</label>
                    <input type="number" name="order" value="{{ old('order', $page->order ?? 0) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all" placeholder="e.g. 1, 2, 3">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Page Body Content *</label>
                <textarea name="content" rows="12" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-mono focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none leading-relaxed transition-all">{{ old('content', $page->content) }}</textarea>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
            <a href="{{ route('admin.pages.index') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2">
                <span>Save Page Changes</span>
                <span>&rarr;</span>
            </button>
        </div>

    </form>
</div>
@endsection
