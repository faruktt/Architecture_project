@props([
    'name' => 'content',
    'value' => '',
    'placeholder' => 'Write your architectural story here. You can write paragraphs, insert 4-5 photos, write more paragraphs, and add more photos...',
    'minHeight' => '320px',
    'required' => false,
])

@php
    $editorId = 'quill_editor_' . str_replace(['[', ']', '-'], '_', $name) . '_' . uniqid();
    $inputId = 'quill_input_' . str_replace(['[', ']', '-'], '_', $name) . '_' . uniqid();
    $fileInputId = 'quill_files_' . str_replace(['[', ']', '-'], '_', $name) . '_' . uniqid();
    $statusId = 'quill_status_' . str_replace(['[', ']', '-'], '_', $name) . '_' . uniqid();
@endphp

<div class="rich-editor-wrapper space-y-2">
    <!-- Action helper bar above editor -->
    <div class="flex flex-wrap items-center justify-between gap-2 p-2.5 bg-slate-100/90 rounded-xl border border-slate-200">
        <div class="flex items-center gap-2">
            <button type="button" onclick="document.getElementById('{{ $fileInputId }}').click()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-lg shadow-xs hover:shadow transition-all cursor-pointer">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/>
                    <path d="M21 15l-5-5L5 21" stroke-width="2"/>
                </svg>
                <span>+ Upload 4-5 Photos into Narrative</span>
            </button>

            <span class="text-[11px] text-slate-500 hidden sm:inline">
                Directly select 1 or multiple photos from your computer
            </span>
        </div>

        <div class="text-[11px] text-slate-400 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                <line x1="12" y1="16" x2="12" y2="12" stroke-width="2"/>
                <line x1="12" y1="8" x2="12.01" y2="8" stroke-width="2"/>
            </svg>
            <span>You can also drag & drop or paste images</span>
        </div>
    </div>

    <!-- Hidden multiple file input for editor uploads -->
    <input type="file" id="{{ $fileInputId }}" multiple accept="image/*" class="hidden">

    <!-- Upload Progress / Status Indicator -->
    <div id="{{ $statusId }}" class="hidden p-2.5 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-900 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="animate-spin w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span class="font-medium status-text">Uploading photos directly to public/uploads/...</span>
        </div>
    </div>

    <!-- Quill Editor Container -->
    <div class="bg-white rounded-xl border border-slate-300 shadow-2xs overflow-hidden focus-within:ring-2 focus-within:ring-slate-900/10 focus-within:border-slate-900 transition-all">
        <div id="{{ $editorId }}" style="min-height: {{ $minHeight }};" class="text-slate-800 text-sm">
            {!! $value !!}
        </div>
    </div>

    <!-- Hidden input holding synchronized HTML value -->
    <textarea name="{{ $name }}" id="{{ $inputId }}" {{ $required ? 'required' : '' }} class="sr-only">{{ $value }}</textarea>
</div>

<!-- Load Quill Assets (Once) -->
@once
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<style>
    .ql-toolbar.ql-snow {
        border: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
        background-color: #f8fafc;
        padding: 10px 14px !important;
        font-family: inherit;
    }
    .ql-container.ql-snow {
        border: none !important;
        font-family: inherit;
        font-size: 14px;
    }
    .ql-editor {
        padding: 16px 20px !important;
        line-height: 1.7;
    }
    .ql-editor p {
        margin-bottom: 1rem;
    }
    .ql-editor img {
        display: block;
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 1.5rem auto;
        box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }
    .ql-editor h2, .ql-editor h3 {
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: #0f172a;
    }
    .ql-editor blockquote {
        border-left: 3px solid #0f172a;
        padding-left: 1rem;
        font-style: italic;
        color: #475569;
    }
</style>
@endonce

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editorContainer = document.getElementById('{{ $editorId }}');
    const hiddenInput = document.getElementById('{{ $inputId }}');
    const multiFileInput = document.getElementById('{{ $fileInputId }}');
    const statusBox = document.getElementById('{{ $statusId }}');

    if (!editorContainer || !hiddenInput) return;

    // Initialize Quill
    const quill = new Quill(editorContainer, {
        theme: 'snow',
        placeholder: '{{ $placeholder }}',
        modules: {
            toolbar: [
                [{ 'header': [2, 3, 4, false] }],
                ['bold', 'italic', 'underline', 'strike', 'blockquote'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image', 'clean']
            ]
        }
    });

    // Upload helper function
    async function uploadFiles(files) {
        if (!files || files.length === 0) return;

        // Show status
        statusBox.classList.remove('hidden');
        const statusText = statusBox.querySelector('.status-text');
        statusText.textContent = `Uploading ${files.length} photo(s) directly to server...`;

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        formData.append('_token', '{{ csrf_token() }}');

        try {
            const response = await fetch('{{ route('editor.upload-image') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success && data.urls && data.urls.length > 0) {
                // Insert each uploaded image at cursor position
                data.urls.forEach(url => {
                    const range = quill.getSelection(true) || { index: quill.getLength() };
                    quill.insertEmbed(range.index, 'image', url);
                    quill.insertText(range.index + 1, '\n');
                    quill.setSelection(range.index + 2);
                });

                statusText.textContent = `✓ Successfully inserted ${data.urls.length} photo(s) into narrative!`;
                statusBox.classList.remove('bg-amber-50', 'border-amber-200', 'text-amber-900');
                statusBox.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-900');

                setTimeout(() => {
                    statusBox.classList.add('hidden');
                    statusBox.classList.remove('bg-emerald-50', 'border-emerald-200', 'text-emerald-900');
                    statusBox.classList.add('bg-amber-50', 'border-amber-200', 'text-amber-900');
                }, 3000);
            } else {
                alert(data.message || 'Error uploading photos.');
                statusBox.classList.add('hidden');
            }
        } catch (err) {
            console.error('Editor upload error:', err);
            alert('Upload failed. Please check your connection and try again.');
            statusBox.classList.add('hidden');
        } finally {
            multiFileInput.value = '';
            syncContent();
        }
    }

    // Connect custom multi-file input
    multiFileInput.addEventListener('change', function () {
        if (this.files && this.files.length > 0) {
            uploadFiles(this.files);
        }
    });

    // Override Quill's default image toolbar button to open our multi-file picker
    const toolbar = quill.getModule('toolbar');
    toolbar.addHandler('image', function () {
        multiFileInput.click();
    });

    // Support Drag and Drop files onto editor
    quill.root.addEventListener('drop', function (e) {
        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            const imageFiles = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
            if (imageFiles.length > 0) {
                e.preventDefault();
                e.stopPropagation();
                uploadFiles(imageFiles);
            }
        }
    });

    // Support Paste files from clipboard
    quill.root.addEventListener('paste', function (e) {
        if (e.clipboardData && e.clipboardData.files && e.clipboardData.files.length > 0) {
            const imageFiles = Array.from(e.clipboardData.files).filter(f => f.type.startsWith('image/'));
            if (imageFiles.length > 0) {
                e.preventDefault();
                e.stopPropagation();
                uploadFiles(imageFiles);
            }
        }
    });

    // Synchronize content to hidden textarea
    function syncContent() {
        const html = quill.root.innerHTML;
        // If empty quill editor (contains just <p><br></p>), clear it
        if (html === '<p><br></p>' || quill.getText().trim().length === 0 && !html.includes('<img')) {
            hiddenInput.value = '';
        } else {
            hiddenInput.value = html;
        }
    }

    quill.on('text-change', syncContent);

    // Sync on enclosing form submit
    const form = editorContainer.closest('form');
    if (form) {
        form.addEventListener('submit', function () {
            syncContent();
        });
    }
});
</script>
