<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Penulis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Welcome Card -->
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-bold mb-2">Halo, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-gray-600">Selamat datang kembali di dashboard penulis. Anda bisa mulai menulis cerita baru atau mengelola artikel yang sudah ada.</p>
                        
                        <div class="mt-6">
                            <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Buat Postingan Baru
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col justify-center items-center p-6 text-center">
                    <div class="text-4xl font-extrabold text-indigo-600 mb-2">
                        {{ Auth::user()->posts()->count() }}
                    </div>
                    <div class="text-sm text-gray-500 uppercase tracking-wide font-semibold">
                        Total Postingan Anda
                    </div>
                </div>
            </div>

            <!-- Quick Action / Recent -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-between items-center">
                    <div>
                        <h4 class="font-semibold text-lg">Kelola Artikel Anda</h4>
                        <p class="text-sm text-gray-500">Lihat, edit, atau hapus artikel yang telah Anda publikasikan.</p>
                    </div>
                    <a href="{{ route('admin.posts.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
                        Ke Halaman Kelola &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
