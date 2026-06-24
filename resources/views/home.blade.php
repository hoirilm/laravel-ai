@extends('layouts.public')

@section('content')
    <div class="space-y-12">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">Latest Posts</h1>
            <p class="mt-4 text-xl text-gray-500">Thoughts, stories and ideas.</p>
        </div>

        @forelse($posts as $post)
            <article class="flex flex-col items-start justify-between bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-200">
                @if($post->cover_image)
                    <div class="relative w-full mb-6">
                        <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}" class="aspect-[16/9] w-full rounded-xl bg-gray-100 object-cover sm:aspect-[2/1] lg:aspect-[3/2]">
                    </div>
                @endif
                <div class="flex items-center gap-x-4 text-xs">
                    <time datetime="{{ $post->created_at->toDateString() }}" class="text-gray-500">{{ $post->created_at->format('M d, Y') }}</time>
                </div>
                <div class="group relative">
                    <h3 class="mt-3 text-2xl font-bold leading-6 text-gray-900 group-hover:text-gray-600">
                        <a href="{{ route('post.show', $post->slug) }}">
                            <span class="absolute inset-0"></span>
                            {{ $post->title }}
                        </a>
                    </h3>
                    <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                </div>
                <div class="relative mt-8 flex items-center gap-x-4">
                    <div class="text-sm leading-6">
                        <p class="font-semibold text-gray-900">
                            <span class="absolute inset-0"></span>
                            {{ $post->user->name }}
                        </p>
                    </div>
                </div>
            </article>
        @empty
            <div class="text-center text-gray-500">
                <p>No posts available yet.</p>
            </div>
        @endforelse

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
