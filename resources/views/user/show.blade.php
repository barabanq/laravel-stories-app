@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-6">

    <h1 class="text-3xl font-bold mb-4">{{ $user->name }}</h1>

    @auth
        @php
            $isSubscribed = Auth::user()
                ->following()
                ->where('following_id', $user->id)
                ->exists();
        @endphp

        @if(Auth::id() !== $user->id)
            <form action="/user/{{ $user->id }}/subscribe" method="POST" class="mb-6">
                @csrf
                <button class="px-4 py-2 rounded text-white {{ $isSubscribed ? 'bg-red-500' : 'bg-blue-500' }}">
                    {{ $isSubscribed ? 'Отписаться' : 'Подписаться' }}
                </button>
            </form>
        @endif
    @endauth

    <h2 class="text-xl font-semibold mb-2">Истории</h2>

    <div class="space-y-3 mb-6">
        @foreach ($user->stories->where('status', 'approved') as $story)
            <div class="p-3 border rounded">
                <a href="/stories/{{ $story->id }}" class="text-blue-500 hover:underline">
                    {{ $story->title }}
                </a>
            </div>
        @endforeach
    </div>

    <h2 class="text-xl font-semibold mb-2">Комментарии</h2>

    <div class="space-y-2">
        @foreach ($user->comments as $comment)
            <div class="p-2 bg-gray-50 border rounded">
                {{ $comment->content }}
            </div>
        @endforeach
    </div>

</div>

@endsection