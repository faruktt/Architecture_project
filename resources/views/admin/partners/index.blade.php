@extends('layouts.admin')

@section('title', 'Our Partners | nook Admin')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Our Partners</h1>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs">
                Total Partners: {{ $partners->total() }}
            </span>
            <a href="{{ route('home') }}#partners" target="_blank" class="px-3 py-1.5 bg-[#404e67] hover:bg-[#353c48] text-white rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-colors">
                <span>View on Site</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs">
            <div class="font-bold mb-1">Please fix the following errors:</div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        <!-- Left Column: Add Partner Form -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-plus text-xs"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Add New Partner</h2>
                    <p class="text-[11px] text-slate-500">Upload partner logo and destination link</p>
                </div>
            </div>

            <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf

                <!-- Partner Name -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Partner Name *</label>
                    <input type="text"
                           name="name"
                           required
                           value="{{ old('name') }}"
                           placeholder="e.g. Holcim Foundation, UIA"
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all">
                </div>

                <!-- Destination URL / Link -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Destination Link (URL)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-link text-[11px]"></i>
                        </span>
                        <input type="url"
                               name="url"
                               value="{{ old('url') }}"
                               placeholder="https://example.com"
                               class="w-full pl-8 pr-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none transition-all font-mono text-[11px]">
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">When users click this logo on the home page, they will be taken to this link.</span>
                </div>

                <!-- Logo Image Upload & Live Preview -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Partner Logo Image *</label>
                    <div class="border-2 border-dashed border-slate-200 hover:border-slate-400 rounded-xl p-4 text-center bg-slate-50/50 transition-colors">
                        <input type="file"
                               name="logo"
                               id="create-logo-input"
                               accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp"
                               required
                               class="hidden"
                               onchange="previewPartnerLogo(this, 'create-logo-preview', 'create-preview-wrapper')">
                        <label for="create-logo-input" class="cursor-pointer block">
                            <div id="create-preview-wrapper" class="hidden mb-3">
                                <img id="create-logo-preview" src="#" alt="Logo Preview" class="h-14 max-w-[200px] object-contain mx-auto bg-white p-2 rounded-lg border border-slate-200 shadow-2xs">
                            </div>
                            <div class="text-slate-400 mb-1">
                                <i class="fa-solid fa-cloud-arrow-up text-xl text-slate-400"></i>
                            </div>
                            <span class="font-semibold text-slate-700 block">Click to upload logo</span>
                            <span class="text-[10px] text-slate-400">SVG, PNG, JPG, or WEBP (Max 4MB)</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Sort Order</label>
                        <input type="number"
                               name="order"
                               value="{{ old('order', 1) }}"
                               min="0"
                               class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Status</label>
                        <select name="is_active" class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl font-medium focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none">
                            <option value="1" selected>Active & Live</option>
                            <option value="0">Draft / Inactive</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-xs hover:shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-plus text-xs text-emerald-400"></i>
                    <span>Save Partner</span>
                </button>
            </form>
        </div>

        <!-- Right Column: Partners List Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <!-- Search & Filter Bar -->
            <div class="p-4 border-b border-slate-200/90 flex flex-col sm:flex-row items-center justify-between gap-4">
                <form action="{{ route('admin.partners.index') }}" method="GET" class="relative w-full sm:max-w-xs">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Search partners..."
                           class="w-full pl-8 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10 outline-none transition-all">
                </form>

                <div class="text-xs text-slate-500 font-medium">
                    Showing {{ $partners->count() }} of {{ $partners->total() }} partners
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700 min-w-[620px]">
                    <thead class="bg-slate-50/80 text-[10px] uppercase font-bold text-slate-500 tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 w-12 text-center">#</th>
                            <th class="py-3 px-4 w-36">Logo</th>
                            <th class="py-3 px-4">Partner Details</th>
                            <th class="py-3 px-4 w-24">Order</th>
                            <th class="py-3 px-4 w-28">Status</th>
                            <th class="py-3 px-4 text-right w-36">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($partners as $partner)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 text-center font-mono text-[11px] text-slate-400">
                                    {{ $partner->id }}
                                </td>

                                <!-- Logo Preview Box -->
                                <td class="py-3.5 px-4">
                                    <div class="w-28 h-12 bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-center shadow-2xs overflow-hidden">
                                        @if($partner->logo_url)
                                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="max-h-full max-w-full object-contain">
                                        @else
                                            <span class="text-[10px] text-slate-400 italic">No image</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Details: Name & Destination Link -->
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900 text-sm leading-tight">{{ $partner->name }}</div>
                                    @if($partner->url)
                                        <div class="mt-1 flex items-center gap-1.5">
                                            <a href="{{ $partner->url }}" target="_blank" rel="noopener noreferrer" class="text-[11px] text-[#01a9ac] hover:underline font-mono inline-flex items-center gap-1">
                                                <i class="fa-solid fa-link text-[9px]"></i>
                                                <span class="truncate max-w-[240px]">{{ $partner->url }}</span>
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic mt-0.5 block">No destination link</span>
                                    @endif
                                </td>

                                <!-- Sort Order -->
                                <td class="py-3.5 px-4 font-mono text-[11px] text-slate-600">
                                    <span class="px-2 py-0.5 bg-slate-100 rounded-md font-semibold text-slate-700">
                                        {{ $partner->order }}
                                    </span>
                                </td>

                                <!-- Active Status -->
                                <td class="py-3.5 px-4">
                                    @if($partner->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-500 border border-slate-200">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <!-- Action Buttons (Strictly Icon-Only with FontAwesome per instruction) -->
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-1 justify-end">
                                        <!-- Open External Website if URL exists -->
                                        @if($partner->url)
                                            <a href="{{ $partner->url }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="p-2 text-slate-400 hover:text-[#01a9ac] hover:bg-cyan-50 rounded-lg transition-colors"
                                               title="Visit Partner Website">
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                            </a>
                                        @endif

                                        <!-- Toggle Status Icon -->
                                        <form action="{{ route('admin.partners.toggle-status', $partner->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2 rounded-lg transition-colors cursor-pointer {{ $partner->is_active ? 'text-emerald-600 hover:bg-emerald-50' : 'text-slate-400 hover:bg-slate-100' }}"
                                                    title="{{ $partner->is_active ? 'Click to Deactivate' : 'Click to Activate' }}">
                                                <i class="fa-solid {{ $partner->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-base"></i>
                                            </button>
                                        </form>

                                        <!-- Edit Modal Trigger -->
                                        <button type="button"
                                                onclick="document.getElementById('edit-partner-modal-{{ $partner->id }}').classList.remove('hidden')"
                                                class="p-2 text-slate-400 hover:text-[#404e67] hover:bg-slate-100 rounded-lg transition-colors cursor-pointer"
                                                title="Edit Partner">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>

                                        <!-- Delete Partner -->
                                        <form action="{{ route('admin.partners.destroy', $partner->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete partner &quot;{{ $partner->name }}&quot;? This cannot be undone.')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Delete Partner">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div id="edit-partner-modal-{{ $partner->id }}"
                                         class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
                                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-lg w-full p-6 text-left space-y-4">
                                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </div>
                                                    <h3 class="font-bold text-slate-900 text-sm">Edit Partner: {{ $partner->name }}</h3>
                                                </div>
                                                <button type="button"
                                                        onclick="document.getElementById('edit-partner-modal-{{ $partner->id }}').classList.add('hidden')"
                                                        class="text-slate-400 hover:text-slate-900 text-base font-bold cursor-pointer">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>

                                            <form action="{{ route('admin.partners.update', $partner->id) }}"
                                                  method="POST"
                                                  enctype="multipart/form-data"
                                                  class="space-y-4 text-xs">
                                                @csrf
                                                @method('PUT')

                                                <!-- Name -->
                                                <div>
                                                    <label class="block font-semibold text-slate-700 mb-1">Partner Name *</label>
                                                    <input type="text"
                                                           name="name"
                                                           required
                                                           value="{{ old('name', $partner->name) }}"
                                                           class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none">
                                                </div>

                                                <!-- URL -->
                                                <div>
                                                    <label class="block font-semibold text-slate-700 mb-1">Destination URL (Link)</label>
                                                    <input type="url"
                                                           name="url"
                                                           value="{{ old('url', $partner->url) }}"
                                                           placeholder="https://example.com"
                                                           class="w-full px-3.5 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 outline-none font-mono text-[11px]">
                                                </div>

                                                <!-- Current Logo & Replace -->
                                                <div>
                                                    <label class="block font-semibold text-slate-700 mb-1">Partner Logo</label>
                                                    <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl mb-2">
                                                        <div class="w-24 h-10 bg-white border border-slate-200 rounded p-1 flex items-center justify-center">
                                                            <img id="edit-preview-{{ $partner->id }}" src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="max-h-full max-w-full object-contain">
                                                        </div>
                                                        <div class="text-[11px] text-slate-500">
                                                            <span class="font-semibold block text-slate-700">Current Logo</span>
                                                            <span class="truncate block max-w-[220px]">{{ $partner->logo }}</span>
                                                        </div>
                                                    </div>

                                                    <input type="file"
                                                           name="logo"
                                                           accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp"
                                                           onchange="previewPartnerLogo(this, 'edit-preview-{{ $partner->id }}')"
                                                           class="w-full px-3 py-1.5 bg-white border border-slate-300 text-slate-700 rounded-xl text-xs file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                                                    <span class="text-[10px] text-slate-400 mt-1 block">Leave empty to keep current logo.</span>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block font-semibold text-slate-700 mb-1">Sort Order</label>
                                                        <input type="number"
                                                               name="order"
                                                               value="{{ old('order', $partner->order) }}"
                                                               class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl">
                                                    </div>
                                                    <div>
                                                        <label class="block font-semibold text-slate-700 mb-1">Status</label>
                                                        <select name="is_active" class="w-full px-3.5 py-2 bg-white border border-slate-300 text-slate-900 rounded-xl">
                                                            <option value="1" {{ $partner->is_active ? 'selected' : '' }}>Active & Live</option>
                                                            <option value="0" {{ !$partner->is_active ? 'selected' : '' }}>Inactive / Draft</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                                                    <button type="button"
                                                            onclick="document.getElementById('edit-partner-modal-{{ $partner->id }}').classList.add('hidden')"
                                                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold cursor-pointer">
                                                        Cancel
                                                    </button>
                                                    <button type="submit"
                                                            class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold cursor-pointer flex items-center gap-1.5">
                                                        <i class="fa-solid fa-check text-xs text-emerald-400"></i>
                                                        <span>Save Changes</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-handshake text-3xl text-slate-300"></i>
                                        <p class="font-semibold text-slate-600">No partners found</p>
                                        <p class="text-[11px] text-slate-400">Use the form on the left to add your first partner logo.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($partners->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $partners->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewPartnerLogo(input, previewImgId, wrapperId = null) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(previewImgId);
                if (img) {
                    img.src = e.target.result;
                }
                if (wrapperId) {
                    const wrap = document.getElementById(wrapperId);
                    if (wrap) wrap.classList.remove('hidden');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
