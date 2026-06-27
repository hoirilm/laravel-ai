@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<style>
    /* Sesuaikan Quill dengan tema Tailwind indigo */
    #quill-editor {
        border: 1px solid #d1d5db; /* border-gray-300 */
        border-radius: 0 0 0.375rem 0.375rem;
        min-height: 280px;
        font-size: 0.875rem;
        font-family: inherit;
    }
    .ql-toolbar.ql-snow {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem 0.375rem 0 0;
        background-color: #f9fafb;
        font-family: inherit;
    }
    .ql-toolbar.ql-snow .ql-picker-label:hover,
    .ql-toolbar.ql-snow .ql-picker-item:hover,
    .ql-toolbar.ql-snow button:hover .ql-stroke,
    .ql-toolbar.ql-snow button.ql-active .ql-stroke {
        stroke: #6366f1; /* indigo-500 */
    }
    .ql-toolbar.ql-snow button:hover .ql-fill,
    .ql-toolbar.ql-snow button.ql-active .ql-fill {
        fill: #6366f1;
    }
    .ql-toolbar.ql-snow .ql-picker-label:hover,
    .ql-toolbar.ql-snow .ql-picker-item.ql-selected,
    .ql-toolbar.ql-snow button:hover,
    .ql-toolbar.ql-snow button.ql-active {
        color: #6366f1;
    }
    .ql-container.ql-snow:focus-within {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }
    .ql-editor {
        min-height: 240px;
        font-size: 0.9rem;
        line-height: 1.6;
        color: #1f2937;
    }
    .ql-editor.ql-blank::before {
        color: #9ca3af;
        font-style: normal;
    }
    .ql-editor h1, .ql-editor h2, .ql-editor h3 { font-weight: 600; }
    .ql-editor blockquote {
        border-left: 4px solid #e0e7ff;
        background: #eef2ff;
        padding: 0.5rem 1rem;
        border-radius: 0 0.25rem 0.25rem 0;
        color: #4338ca;
    }
    .ql-editor pre.ql-syntax {
        background-color: #1e1b4b;
        color: #c7d2fe;
        border-radius: 0.375rem;
        font-size: 0.8rem;
    }
</style>
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div x-data="uploadForm()">
                        <form id="postForm" @submit.prevent="submitForm" action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                        
                        @if ($errors->any())
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                <strong class="font-bold">Oops!</strong>
                                <span class="block sm:inline">Terdapat kesalahan:</span>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus maxlength="255" />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="images" :value="__('Images (Optional)')" />
                            <input id="images" name="images[]" type="file" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" />
                            <x-input-error class="mt-2" :messages="$errors->get('images')" />
                            <p class="text-xs text-gray-500 mt-1">You can select multiple images.</p>
                        </div>

                        <div>
                            <x-input-label for="content" :value="__('Content')" />
                            <!-- Hidden textarea untuk submit -->
                            <textarea id="content" name="content" class="hidden">{{ old('content') }}</textarea>
                            <!-- Quill editor container -->
                            <div id="quill-editor" class="mt-1 bg-white"></div>
                            <x-input-error class="mt-2" :messages="$errors->get('content')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button type="submit" x-bind:disabled="isSubmitting" x-bind:class="{ 'opacity-50 cursor-not-allowed': isSubmitting }">
                                <span x-show="!isSubmitting">{{ __('Save') }}</span>
                                <span x-show="isSubmitting" style="display: none;">Menyimpan...</span>
                            </x-primary-button>
                            <a href="{{ route('admin.posts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                        </form>

                        <!-- Popup Modal Overlay -->
                        <div x-show="showModal" 
                             class="fixed inset-0 z-50 flex items-center justify-center"
                             style="display: none;">
                             
                            <!-- Background backdrop -->
                            <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"
                                 x-show="showModal"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 @click="showModal = false"></div>

                            <!-- Modal Content -->
                            <div x-show="showModal"
                                 x-transition:enter="transition ease-out duration-300 delay-75"
                                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                                 class="relative bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform transition-all">
                                <div class="flex items-center justify-center w-14 h-14 mx-auto bg-red-100 rounded-full mb-4">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-center text-gray-900 mb-2">Upload Tertunda</h3>
                                <p class="text-sm text-gray-600 text-center mb-6" x-text="errorMessage"></p>
                                <div class="text-center">
                                    <button type="button" @click="showModal = false" class="w-full px-4 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 focus:outline-none transition">
                                        Saya Mengerti
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function uploadForm() {
            return {
                showModal: false,
                errorMessage: '',
                isSubmitting: false,
                submitForm(e) {
                    // Sync konten Quill ke hidden textarea
                    const quillContent = quill.root.innerHTML;
                    const isEmpty = quill.getText().trim().length === 0;

                    if (isEmpty) {
                        quill.root.style.borderColor = '#ef4444';
                        quill.root.focus();
                        return;
                    }

                    quill.root.style.borderColor = '';
                    document.getElementById('content').value = quillContent;

                    const form = e.target;
                    const fileInput = document.getElementById('images');
                    if (fileInput && fileInput.files.length > 0) {
                        let totalSize = 0;
                        const maxSizePerFile = 2 * 1024 * 1024; // 2MB
                        const maxTotalSize = 8 * 1024 * 1024; // 8MB
                        
                        for (let i = 0; i < fileInput.files.length; i++) {
                            const file = fileInput.files[i];
                            totalSize += file.size;
                            if (file.size > maxSizePerFile) {
                                this.errorMessage = 'Gambar "' + file.name + '" ukurannya terlalu besar. Maksimal 2MB per gambar.';
                                this.showModal = true;
                                return;
                            }
                        }
                        
                        if (totalSize > maxTotalSize) {
                            this.errorMessage = 'Total ukuran semua gambar melebihi batas aman (Maksimal 8MB). Harap kurangi jumlah gambar yang dipilih.';
                            this.showModal = true;
                            return;
                        }
                    }
                    
                    this.isSubmitting = true;
                    form.submit();
                }
            }
        }
    </script>

    @push('scripts')
    <script>
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Tulis konten post di sini...',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ color: [] }, { background: [] }],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    ['blockquote', 'code-block'],
                    ['link'],
                    ['clean'],
                ],
            },
        });

        // Isi konten awal dari old() jika ada (setelah validation error)
        const existingContent = document.getElementById('content').value;
        if (existingContent) {
            quill.root.innerHTML = existingContent;
        }
    </script>
    @endpush
</x-app-layout>
