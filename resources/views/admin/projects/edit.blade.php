@extends('layouts.admin')

@section('title', 'Review & Edit Project: ' . $project->title)

@section('content')
<div class="max-w-4xl mx-auto py-2 space-y-6">

    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">Project Editorial Review</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Review & Edit Architectural Project</h1>
            <p class="text-xs text-slate-500 mt-1">Admin review portal: Edit any fields submitted by the author and decide publication status.</p>
        </div>
        <a href="{{ route('admin.projects.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-semibold flex items-center gap-1 transition-colors">
            <span>&larr; Back to Projects</span>
        </a>
    </div>

    <!-- Submitting Author Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-4">
            @if($project->author)
                <img src="{{ $project->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->author->name) }}" class="w-14 h-14 rounded-full object-cover border border-slate-200 shadow-2xs">
                <div>
                    <span class="text-[10px] uppercase font-bold text-amber-700 tracking-wider block">Submitted By Author</span>
                    <strong class="text-base text-slate-900 block font-bold">{{ $project->author->name }}</strong>
                    <span class="text-xs text-slate-500">{{ $project->author->company ?? $project->author->country }} &bull; {{ $project->author->email }}</span>
                </div>
            @else
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Submitted Directly</span>
                    <strong class="text-base text-slate-900 block font-bold">Editorial In-House</strong>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs text-slate-500 font-medium">Current Status:</span>
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $project->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($project->status === 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                {{ $project->status }}
            </span>
        </div>
    </div>

    <!-- Full Edit Form -->
    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/90 shadow-sm text-xs">
        @csrf
        @method('PUT')

        <!-- Status & Editorial Decision Block -->
        <div class="p-5 bg-amber-50/40 border border-amber-200/80 rounded-2xl space-y-4">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <h3 class="text-xs font-bold text-amber-900 uppercase tracking-wider">Editorial Publication Control</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Publication Status *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-bold focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                        <option value="pending" {{ old('status', $project->status) === 'pending' ? 'selected' : '' }}>Pending Review (Hidden from public)</option>
                        <option value="approved" {{ old('status', $project->status) === 'approved' ? 'selected' : '' }}>Approved & Live (Visible on Homepage/Catalog)</option>
                        <option value="rejected" {{ old('status', $project->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Internal Admin Notes / Feedback</label>
                    <input type="text" name="admin_notes" value="{{ old('admin_notes', $project->admin_notes) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all" placeholder="Feedback or revision requests...">
                </div>
            </div>

            <!-- Homepage Special Features (Exclusive selections) -->
            <div class="pt-3 border-t border-amber-200/60 space-y-2">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Homepage Exclusive Features (Selecting will automatically unmark previous selection):</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="flex items-center gap-2 font-semibold text-slate-700 cursor-pointer p-2.5 rounded-xl border border-amber-200 bg-amber-50/50 hover:bg-amber-50">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $project->is_featured) ? 'checked' : '' }} class="rounded border-slate-300 text-amber-600 focus:ring-0">
                        <div>
                            <span class="block text-slate-900 font-bold">Project of the week</span>
                            <span class="text-[10px] text-slate-500 font-normal">Top left feature card (1 only)</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-2 font-semibold text-slate-700 cursor-pointer p-2.5 rounded-xl border border-purple-200 bg-purple-50/50 hover:bg-purple-50">
                        <input type="checkbox" name="is_spotlight" value="1" {{ old('is_spotlight', $project->is_spotlight) ? 'checked' : '' }} class="rounded border-slate-300 text-purple-600 focus:ring-0">
                        <div>
                            <span class="block text-slate-900 font-bold">Nook Spotlight</span>
                            <span class="text-[10px] text-slate-500 font-normal">Top right feature card (1 only)</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-2 font-semibold text-slate-700 cursor-pointer p-2.5 rounded-xl border border-blue-200 bg-blue-50/50 hover:bg-blue-50">
                        <input type="checkbox" name="is_hero_story" value="1" {{ old('is_hero_story', $project->is_hero_story) ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-0">
                        <div>
                            <span class="block text-slate-900 font-bold">Main Hero Story</span>
                            <span class="text-[10px] text-slate-500 font-normal">Big photo & title under cards (1 only)</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Editable Project Fields -->
        <div class="space-y-4">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-2">Project Metadata</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Title *</label>
                    <input type="text" name="title" required value="{{ old('title', $project->title) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Slug (URL identifier) *</label>
                    <input type="text" name="slug" required value="{{ old('slug', $project->slug) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl font-mono focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Subtitle / Catchphrase</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $project->subtitle) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Category *</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $project->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Country *</label>
                    <select name="country" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                        @foreach($countries as $cnt)
                            <option value="{{ $cnt }}" {{ old('country', $project->country) === $cnt ? 'selected' : '' }}>{{ $cnt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">City / Location</label>
                    <input type="text" name="city" value="{{ old('city', $project->city) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Year</label>
                    <input type="text" name="year" value="{{ old('year', $project->year) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Gross Area</label>
                    <input type="text" name="area" value="{{ old('area', $project->area) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Architects / Firm</label>
                    <input type="text" name="architects" value="{{ old('architects', $project->architects) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Lead Architects</label>
                    <input type="text" name="lead_architects" value="{{ old('lead_architects', $project->lead_architects) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Manufacturers & Materials</label>
                    <input type="text" name="manufacturers" value="{{ old('manufacturers', $project->manufacturers) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Short Excerpt (Summary)</label>
                <textarea name="excerpt" rows="2" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">{{ old('excerpt', $project->excerpt) }}</textarea>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Full Editorial Description / Story (Content) *</label>
                <p class="text-[11px] text-slate-500 mb-2">Write your narrative, insert 4-5 photos, write more text, and add more photos directly into the story.</p>
                <x-rich-editor name="content" :value="old('content', old('description', $project->content))" />
            </div>
        </div>

        <x-direct-image-upload 
            :current-featured="$project->featured_image"
            :current-gallery="$project->gallery"
        />

        <!-- Submit Bar -->
        <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
            <a href="{{ route('admin.projects.index') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-2">
                <span>Save Changes & Update Project</span>
                <span>&rarr;</span>
            </button>
        </div>
    </form>

</div>
@endsection
