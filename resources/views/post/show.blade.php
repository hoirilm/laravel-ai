@extends('layouts.public')

@section('content')
    <div x-data="{ modalOpen: false, modalImage: '' }">
        <article class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mt-8">
            <header class="mb-10 text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl mb-4">{{ $post->title }}</h1>
                <div class="flex items-center justify-center text-sm text-gray-500 gap-4">
                    <span>By {{ $post->user->name }}</span>
                    <span>•</span>
                    <time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->format('F d, Y') }}</time>
                </div>
            </header>

            @if(!empty($post->images))
                <div class="mb-10 grid gap-6 grid-cols-1 sm:grid-cols-2">
                    @foreach($post->images as $image)
                        <img 
                            src="{{ Storage::url($image) }}" 
                            alt="{{ $post->title }}" 
                            class="w-full aspect-[4/3] rounded-2xl object-cover shadow-sm cursor-zoom-in hover:opacity-90 hover:shadow-md transition"
                            @click="modalOpen = true; modalImage = '{{ Storage::url($image) }}'"
                        >
                    @endforeach
                </div>
            @endif

            <div class="prose prose-lg prose-indigo prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-700 prose-strong:text-gray-900 prose-a:text-indigo-600 prose-blockquote:border-indigo-300 prose-code:text-indigo-700 max-w-none mx-auto leading-relaxed">
                {!! $post->content !!}
            </div>

            <div class="mt-16 pt-8 border-t border-gray-100 text-center">
                <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">&larr; Back to all posts</a>
            </div>
        </article>

        <!-- Lightbox Modal -->
        <div 
            x-show="modalOpen" 
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 backdrop-blur-sm transition-opacity"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;"
            @keydown.escape.window="modalOpen = false"
        >
            <button 
                @click="modalOpen = false" 
                class="absolute top-6 right-6 text-white hover:text-gray-300 focus:outline-none"
            >
                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img 
                :src="modalImage" 
                class="max-h-[90vh] max-w-[90vw] object-contain rounded-lg shadow-2xl cursor-zoom-out"
                @click="modalOpen = false"
                @click.outside="modalOpen = false"
            >
        </div>
    </div>
@endsection
