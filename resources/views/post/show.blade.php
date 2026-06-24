@extends('layouts.public')

@section('content')
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
                    <img src="{{ Storage::url($image) }}" alt="{{ $post->title }}" class="w-full aspect-[4/3] rounded-2xl object-cover shadow-sm">
                @endforeach
            </div>
        @endif

        <div class="prose prose-lg prose-indigo mx-auto text-gray-700 leading-relaxed">
            {!! nl2br(e($post->content)) !!}
        </div>

        <div class="mt-16 pt-8 border-t border-gray-100 text-center">
            <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">&larr; Back to all posts</a>
        </div>
    </article>
@endsection
