@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Лента</h1>

    @foreach ($stories as $story)
        <div class="bg-white p-5 rounded-lg shadow mb-6">

            <h3 class="text-lg font-semibold mb-2">
                <a href="/stories/{{ $story->id }}" class="text-blue-500 hover:underline">
                    {{ $story->title }}
                </a>
            </h3>

            <p class="text-sm text-gray-500 mb-2">
                Автор: {{ $story->user->name }}
            </p>

            <p class="text-sm text-gray-800 mb-3">
                {{ $story->content }}
            </p>

            @if(isset($story->image))
                <img src="{{ $story->image }}" alt="story image" class="max-w-full h-auto rounded mb-3">
            @endif

            @if(isset($story->video))
                <video controls class="max-w-full h-auto rounded mb-3">
                    <source src="{{ $story->video }}" type="video/mp4">
                </video>
            @endif

            @auth
                @php
                    $liked = $story->likes->contains('user_id', Auth::id());
                @endphp

                <button type="button" class="like-btn mt-2 px-3 py-1 border rounded"
                data-id="{{ $story->id }}">
                <span class="heart">
                {{ $story->likes->contains('user_id', Auth::id()) ? '❤️' : '🤍' }}
                </span>
                <span class="like-count">{{ $story->likes_count }}</span>
                </button>
            @endauth

        </div>
    @endforeach

    {{-- <div class="mt-4">
        {{ $stories->links() }}
    </div> --}}
</div>
@endsection