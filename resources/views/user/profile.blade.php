@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-6">

    {{-- Заголовок профиля --}}
    <h1 class="text-3xl font-bold mb-2">{{ $user->name }}</h1>

    {{-- Подписка --}}
    @auth
        @php
            $isSubscribed = Auth::user()
                ->following()
                ->where('following_id', $user->id)->exists();
        @endphp

        @if(Auth::id() !== $user->id)
            <form action="/user/{{ $user->id }}/subscribe" method="POST" class="mb-6">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    {{ $isSubscribed ? 'Отписаться' : 'Подписаться' }}
                </button>
            </form>
        @endif
    @endauth

    {{-- Истории пользователя --}}
    <h2 class="text-2xl font-semibold mb-2">Истории:</h2>
    <div class="space-y-4 mb-6">
        @foreach ($user->stories as $story)
            <div class="p-4 border rounded hover:shadow">
                <a href="/stories/{{ $story->id }}" class="text-xl font-medium text-blue-500 hover:underline">
                    {{ $story->title }}
                </a>
                <p class="text-sm text-gray-500 mt-1">
                    Автор:
                    <a href="/user/{{ $story->user->id }}" class="text-blue-500 hover:underline">
                        {{ $story->user->name }}
                    </a>
                </p>
            </div>
        @endforeach
    </div>

    {{-- Комментарии пользователя --}}
    <h2 class="text-2xl font-semibold mb-2">Комментарии:</h2>
    <div class="space-y-3">
        @foreach ($user->comments as $comment)
            <div class="p-3 bg-gray-50 border rounded">
                <p>{{ $comment->content }}</p>
                <p class="text-xs text-gray-400 mt-1">
                    к истории:
                    <a href="/stories/{{ $comment->story->id }}" class="text-blue-500 hover:underline">
                        {{ $comment->story->title }}
                    </a>
                </p>
            </div>
        @endforeach
    </div>

</div>

@endsection