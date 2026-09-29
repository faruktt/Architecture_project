@extends('layouts.author')

@section('title', 'Edit Project | ' . $project->title)

@section('content')
<div class="max-w-4xl mx-auto py-4">

    <div class="border-b border-zinc-200 pb-5 mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-zinc-900">Edit Project: {{ $project->title }}</h1>
            <p class="text-xs text-zinc-500 mt-1">Current status: <span class="uppercase font-bold text-zinc-800">{{ $project->status }}</span></p>
        </div>
        <a href="{{ route('author.projects.index') }}" class="text-xs text-zinc-600 hover:text-black font-semibold inline-flex items-center gap-1">
            &larr; Back to My Projects
        </a>
    </div>

    <form action="{{ route('author.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-4 sm:p-10 rounded-xl border border-zinc-200 shadow-sm">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Project Title *</label>
                <input type="text" name="title" required value="{{ old('title', $project->title) }}"
                       class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Subtitle</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $project->subtitle) }}"
                       class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Typology *</label>
                    <select name="category" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $project->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Country *</label>
                    <select name="country" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black bg-white">
                        @foreach($countries as $cnt)
                            <option value="{{ $cnt }}" {{ old('country', $project->country) === $cnt ? 'selected' : '' }}>{{ $cnt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $project->city) }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Year</label>
                    <input type="text" name="year" value="{{ old('year', $project->year) }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Gross Area</label>
                    <input type="text" name="area" value="{{ old('area', $project->area) }}"
                           class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Collaboration</label>
                <input type="text" name="collaboration" value="{{ old('collaboration', $project->collaboration) }}"
                       class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">
            </div>

            <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-lg">
                <label class="flex items-center gap-2 text-xs font-semibold text-zinc-800">
                    <input type="checkbox" name="is_property_sell" value="1" {{ old('is_property_sell', $project->is_property_sell) ? 'checked' : '' }} class="rounded border-zinc-300 text-black">
                    <span>Feature in Property Sell Post section</span>
                </label>
                <div class="mt-2">
                    <input type="text" name="price" value="{{ old('price', $project->price) }}" class="w-full sm:w-1/2 px-3 py-1.5 text-xs border border-zinc-300 rounded" placeholder="e.g. $1,500,000 USD">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Lead Excerpt *</label>
                <textarea name="excerpt" rows="3" required class="w-full px-3 py-2 text-xs border border-zinc-300 rounded focus:outline-none focus:border-black">{{ old('excerpt', $project->excerpt) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Narrative Content *</label>
                <p class="text-[11px] text-zinc-500 mb-2">Write your narrative, insert 4-5 photos, write more text, and add more photos directly into the story.</p>
                <x-rich-editor name="content" :value="old('content', $project->content)" />
            </div>
        </div>

        <x-direct-image-upload 
            :current-featured="$project->featured_image"
            :current-gallery="$project->gallery"
        />

        <div class="pt-6 border-t border-zinc-200 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-black text-white hover:bg-zinc-800 text-xs font-bold rounded-lg uppercase tracking-wider transition-colors shadow">
                Save Changes & Resubmit &rarr;
            </button>
        </div>

    </form>
</div>
@endsection
