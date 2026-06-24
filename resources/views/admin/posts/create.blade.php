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
                            <textarea id="content" name="content" rows="10" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('content') }}</textarea>
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
</x-app-layout>
