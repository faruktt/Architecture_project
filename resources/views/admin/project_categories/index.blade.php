@extends('layouts.admin')

@section('title', 'Project Categories | nook Admin')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">Taxonomy CMS</span>
                <span class="text-xs text-slate-400 font-medium">Architecture</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Project Categories</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage architectural typology categories. These populate dynamically in project creation forms and frontend filters.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 shadow-2xs">
                Total Categories: {{ $categories->total() }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        <!-- Left Column: Add New Category Form -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    +
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Create New Category</h2>
                    <p class="text-[11px] text-slate-500">Add a project typology category</p>
                </div>
            </div>

            <form action="{{ route('admin.project-categories.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Category Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Residential, Hospitality, Pavilion" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Description (Optional)</label>
                    <textarea name="description" rows="2" placeholder="Brief summary of this architectural typology..." class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Sort Order</label>
                        <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Status</label>
                        <select name="is_active" class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl font-medium focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none">
                            <option value="1" selected>Active & Live</option>
                            <option value="0">Draft / Inactive</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-xs hover:shadow transition-all flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Save Project Category</span>
                </button>
            </form>
        </div>

        <!-- Right Column: Categories Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <!-- Search & Filter bar -->
            <div class="p-4 border-b border-slate-200/90 flex items-center justify-between gap-4">
                <form action="{{ route('admin.project-categories.index') }}" method="GET" class="relative flex-grow max-w-sm">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search project categories..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10 outline-none transition-all">
                </form>

                <span class="text-xs text-slate-400">
                    Showing {{ $categories->count() }} of {{ $categories->total() }}
                </span>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700 min-w-[550px]">
                    <thead class="bg-slate-50/80 text-[10px] uppercase font-bold text-slate-500 tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Order</th>
                            <th class="py-3 px-4">Category Name</th>
                            <th class="py-3 px-4">Projects Count</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-4 font-mono text-[11px] text-slate-400">
                                    {{ $cat->order }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $cat->name }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">slug: {{ $cat->slug }}</div>
                                    @if($cat->description)
                                        <div class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ $cat->description }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $cat->projects_count }} projects
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @if($cat->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-500 border border-slate-200">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <!-- Edit button triggers inline toggle or modal -->
                                        <button type="button" onclick="document.getElementById('edit-modal-{{ $cat->id }}').classList.remove('hidden')" class="p-1.5 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors" title="Edit Category">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <!-- Delete button -->
                                        <form action="{{ route('admin.project-categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove category {{ $cat->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete Category">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div id="edit-modal-{{ $cat->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
                                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full p-6 text-left space-y-4">
                                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                                <h3 class="font-bold text-slate-900 text-sm">Edit Category: {{ $cat->name }}</h3>
                                                <button type="button" onclick="document.getElementById('edit-modal-{{ $cat->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-900 text-lg font-bold">&times;</button>
                                            </div>

                                            <form action="{{ route('admin.project-categories.update', $cat->id) }}" method="POST" class="space-y-4 text-xs">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="block font-semibold text-slate-700 mb-1">Category Name *</label>
                                                    <input type="text" name="name" required value="{{ $cat->name }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none">
                                                </div>

                                                <div>
                                                    <label class="block font-semibold text-slate-700 mb-1">Description</label>
                                                    <textarea name="description" rows="2" class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none">{{ $cat->description }}</textarea>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block font-semibold text-slate-700 mb-1">Order</label>
                                                        <input type="number" name="order" value="{{ $cat->order }}" class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl">
                                                    </div>
                                                    <div>
                                                        <label class="block font-semibold text-slate-700 mb-1">Status</label>
                                                        <select name="is_active" class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl">
                                                            <option value="1" {{ $cat->is_active ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ !$cat->is_active ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                                                    <button type="button" onclick="document.getElementById('edit-modal-{{ $cat->id }}').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold">Cancel</button>
                                                    <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold shadow-xs">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    No project categories found. Create one using the form on the left!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-4 border-t border-slate-200/90">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
