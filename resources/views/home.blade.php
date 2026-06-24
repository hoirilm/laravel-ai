@extends('layouts.public')

@section('content')
    <div class="space-y-12">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">Latest Posts</h1>
            <p class="mt-4 text-xl text-gray-500">Thoughts, stories and ideas.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($posts as $post)
                <article class="flex flex-col items-start justify-between bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-200 h-full">
                    @if($post->cover_image)
                        <div class="relative w-full mb-4">
                            <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}" class="aspect-[16/9] w-full rounded-xl bg-gray-100 object-cover">
                        </div>
                    @endif
                    <div class="group relative mt-auto w-full">
                        <h3 class="mt-3 text-lg font-bold leading-tight text-gray-900 group-hover:text-gray-600">
                            <a href="{{ route('post.show', $post->slug) }}">
                                <span class="absolute inset-0"></span>
                                {{ $post->title }}
                            </a>
                        </h3>
                        <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-gray-600">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                    </div>
                    <div class="relative mt-4 flex items-center justify-between w-full text-xs text-gray-500 border-t border-gray-50 pt-4">
                        <div class="font-medium text-gray-900">
                            {{ $post->user->name }}
                        </div>
                        <time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->format('M d, Y') }}</time>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center text-gray-500 py-12">
                    <p>No posts available yet.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
