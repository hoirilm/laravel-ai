<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Posts') }}
            </h2>
            <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Create Post
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ showDeleteModal: false, deleteUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($posts as $post)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $post->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $post->created_at->format('Y-m-d') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('post.show', $post->slug) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <button type="button" @click="showDeleteModal = true; deleteUrl = '{{ route('admin.posts.destroy', $post) }}'" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Global Delete Form -->
        <form x-ref="deleteForm" :action="deleteUrl" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <!-- Delete Confirmation Modal Overlay -->
        <div x-show="showDeleteModal" 
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="display: none;">
             
            <!-- Background backdrop -->
            <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"
                 x-show="showDeleteModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showDeleteModal = false"></div>

            <!-- Modal Content -->
            <div x-show="showDeleteModal"
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
                <h3 class="text-xl font-bold text-center text-gray-900 mb-2">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus postingan ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex gap-4">
                    <button type="button" @click="$refs.deleteForm.submit()" class="w-1/2 px-4 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 focus:outline-none transition">
                        Ya, Hapus
                    </button>
                    <button type="button" @click="showDeleteModal = false" class="w-1/2 px-4 py-3 bg-gray-100 text-gray-800 font-semibold rounded-xl hover:bg-gray-200 focus:outline-none transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
