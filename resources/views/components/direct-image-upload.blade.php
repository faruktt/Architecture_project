@props([
    'featuredName' => 'featured_image',
    'galleryName' => 'gallery',
    'currentFeatured' => null,
    'currentGallery' => [],
    'featuredLabel' => 'Cover / Featured Photo',
    'galleryLabel' => 'Project Photography & Gallery (Select 4-5 or More Photos)',
    'featuredHelp' => 'Directly upload high-resolution cover photo. Stored in public/uploads/.',
    'galleryHelp' => 'Select multiple photos at once (Hold Ctrl or Shift to pick 4, 5, 10+ photos).',
])

@php
    $uid = uniqid();
    $featuredInputId = 'featured_file_' . $uid;
    $featuredPreviewId = 'featured_preview_' . $uid;
    $galleryInputId = 'gallery_files_' . $uid;
    $galleryPreviewGridId = 'gallery_preview_grid_' . $uid;
    $galleryCountBadgeId = 'gallery_count_badge_' . $uid;
@endphp

<div class="direct-image-upload-wrapper space-y-6 pt-4 border-t border-slate-200">

    <!-- 1. COVER / FEATURED IMAGE DIRECT UPLOAD -->
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <div>
                <label class="block font-bold text-slate-900 text-xs uppercase tracking-wider">{{ $featuredLabel }} *</label>
                <p class="text-[11px] text-slate-500">{{ $featuredHelp }}</p>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                Direct File Upload
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
            <!-- Upload Box -->
            <div class="md:col-span-2">
                <label for="{{ $featuredInputId }}" class="relative border-2 border-dashed border-slate-300 hover:border-slate-900 rounded-2xl p-6 flex flex-col items-center justify-center text-center cursor-pointer bg-slate-50/60 hover:bg-slate-100/50 transition-all group">
                    <input type="file" id="{{ $featuredInputId }}" name="{{ $featuredName }}" accept="image/*" class="sr-only">

                    <div class="w-12 h-12 rounded-full bg-white shadow-xs border border-slate-200 flex items-center justify-center text-slate-600 group-hover:scale-110 group-hover:text-slate-900 transition-all mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    <span class="text-xs font-bold text-slate-800 group-hover:text-slate-900 block">Click to Browse or Drag Photo Here</span>
                    <span class="text-[11px] text-slate-400 mt-0.5 block">Supports JPG, PNG, WEBP, high-res architectural photos</span>

                    <span class="mt-3 px-3 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-semibold text-slate-600 shadow-2xs group-hover:border-slate-400">
                        Choose Cover Image
                    </span>
                </label>
            </div>

            <!-- Preview Card -->
            <div class="border border-slate-200 bg-white rounded-2xl p-3 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Cover Preview</span>
                <div class="aspect-[16/10] rounded-xl overflow-hidden bg-slate-100 border border-slate-200 relative flex items-center justify-center text-slate-400">
                    <img id="{{ $featuredPreviewId }}" src="{{ $currentFeatured ?: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=600&q=80' }}" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>


    <!-- 2. GALLERY DIRECT MULTIPLE FILE UPLOAD -->
    <div class="space-y-3 pt-4 border-t border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <label class="block font-bold text-slate-900 text-xs uppercase tracking-wider">{{ $galleryLabel }}</label>
                <p class="text-[11px] text-slate-500">{{ $galleryHelp }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span id="{{ $galleryCountBadgeId }}" class="text-[10px] font-bold uppercase tracking-wider text-purple-700 bg-purple-50 border border-purple-200 px-2 py-0.5 rounded-full">
                    {{ is_array($currentGallery) && count($currentGallery) > 0 ? count($currentGallery) . ' Saved Photos' : 'Multi-Photo Upload' }}
                </span>
            </div>
        </div>

        <!-- Big Gallery Multi-Dropzone -->
        <label for="{{ $galleryInputId }}" class="border-2 border-dashed border-slate-300 hover:border-purple-600 rounded-2xl p-6 flex flex-col items-center justify-center text-center cursor-pointer bg-slate-50/60 hover:bg-purple-50/20 transition-all group">
            <input type="file" id="{{ $galleryInputId }}" name="{{ $galleryName }}[]" multiple accept="image/*" class="sr-only">

            <div class="w-12 h-12 rounded-full bg-white shadow-xs border border-slate-200 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-all mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/>
                    <path d="M21 15l-5-5L5 21" stroke-width="2"/>
                </svg>
            </div>

            <span class="text-xs font-bold text-slate-800 group-hover:text-purple-900 block">Click to Select Multiple Photos (4, 5, 10+ Photos)</span>
            <span class="text-[11px] text-slate-500 mt-0.5 block">Select multiple files at once. All will be uploaded and saved to public/uploads/</span>

            <span class="mt-3 px-4 py-1.5 bg-slate-900 text-white rounded-xl text-xs font-bold shadow-xs group-hover:bg-purple-700 transition-colors">
                + Browse Gallery Photos
            </span>
        </label>

        <!-- Preview Grid for Selected & Existing Gallery Photos -->
        <div id="{{ $galleryPreviewGridId }}" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3 pt-2">
            @if(is_array($currentGallery) && count($currentGallery) > 0)
                @foreach($currentGallery as $idx => $img)
                    <div class="relative group aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-2xs">
                        <img src="{{ $img }}" class="w-full h-full object-cover">
                        <span class="absolute bottom-1 left-1 bg-black/70 text-white text-[9px] px-1.5 py-0.5 rounded font-mono">
                            Photo {{ $idx + 1 }}
                        </span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const featuredInput = document.getElementById('{{ $featuredInputId }}');
    const featuredPreview = document.getElementById('{{ $featuredPreviewId }}');
    const galleryInput = document.getElementById('{{ $galleryInputId }}');
    const galleryGrid = document.getElementById('{{ $galleryPreviewGridId }}');
    const galleryBadge = document.getElementById('{{ $galleryCountBadgeId }}');

    // 1. Featured Image Instant Live Preview
    if (featuredInput && featuredPreview) {
        featuredInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    featuredPreview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // 2. Multi-Photo Gallery Instant Live Preview
    if (galleryInput && galleryGrid) {
        galleryInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                const files = Array.from(this.files);

                // Update count badge
                if (galleryBadge) {
                    galleryBadge.textContent = `${files.length} Photo(s) Selected for Upload`;
                    galleryBadge.className = 'text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full';
                }

                // Add thumbnails to grid
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const thumbCard = document.createElement('div');
                        thumbCard.className = 'relative group aspect-square rounded-xl overflow-hidden bg-slate-100 border border-emerald-300 shadow-2xs animate-fade-in';
                        thumbCard.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                            <span class="absolute top-1 right-1 bg-emerald-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full shadow-xs">
                                New
                            </span>
                            <span class="absolute bottom-1 left-1 bg-black/70 text-white text-[8px] px-1 py-0.5 rounded truncate max-w-[90%]">
                                ${file.name}
                            </span>
                        `;
                        galleryGrid.appendChild(thumbCard);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    }
});
</script>
