@extends('layouts.app')

@section('content')

<form method="GET" action="/stories" class="mb-4 flex gap-2">
    <input type="text" name="q" placeholder="Поиск..." value="{{ request('q') }}" class="border px-3 py-2 rounded flex-1">
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Найти</button>
</form>

<h1 class="text-2xl font-bold mb-2">{{ $story->title }}</h1>

<p class="text-sm text-gray-500 mb-2">
    Автор:
    <a href="/user/{{ $story->user->id }}" class="text-blue-500 hover:underline">
        {{ $story->user->name }}
    </a>
    | {{ $story->created_at->format('d.m.Y H:i') }}
</p>

<p class="mb-3">{{ $story->content }}</p>

<div class="mb-4 flex flex-wrap gap-2">
    @foreach ($story->tags as $tag)
        <span class="text-sm bg-gray-200 px-2 py-1 rounded">#{{ $tag->name }}</span>
    @endforeach
</div>

<hr class="my-4">

<h3 class="text-lg font-semibold mb-2">Комментарии</h3>

@auth
<form id="comment-form" action="/stories/{{ $story->id }}/comment" class="mb-4">
    @csrf
    <textarea
        name="content"
        id="comment-input"
        class="w-full border rounded px-3 py-2 mb-2"
        placeholder="Комментарий..."
    ></textarea>

    <button class="bg-blue-500 text-white px-4 py-2 rounded">Отправить</button>
</form>
@endauth

<div id="comments" class="space-y-4">
@foreach ($story->comments as $comment)
    <div class="border-b pb-3">

        <p class="font-semibold">{{ $comment->user->name }}</p>
        <p class="mb-2">{{ $comment->content }}</p>

        {{-- Лайк для комментария --}}
        <button class="like-btn flex items-center gap-1 mt-1" data-id="{{ $comment->id }}">
            <span class="heart">🤍</span>
            <span class="like-count">{{ $comment->likes->count() }}</span>
        </button>

        {{-- Редактирование собственного комментария --}}
        @if(Auth::id() === $comment->user_id)
            <div x-data="{ edit: false }" class="mt-2">
                <button @click="edit = !edit" class="text-sm text-blue-500 mb-1">
                    Изменить
                </button>
                <form x-show="edit" action="/comments/{{ $comment->id }}" method="POST" class="flex gap-2 mt-1">
                    @csrf
                    @method('PUT')
                    <input
                        type="text"
                        name="content"
                        value="{{ $comment->content }}"
                        class="border px-2 py-1 rounded flex-1"
                    >
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Сохранить</button>
                </form>
                <form action="/comments/{{ $comment->id }}" method="POST" class="mt-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 text-sm">
                    Удалить
                    </button>
                </form>
            </div>
        @endif

        {{-- Ответ на комментарий --}}
        @auth
            <form action="/stories/{{ $story->id }}/comment" method="POST" class="mt-2 flex gap-2">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <input
                    type="text"
                    name="content"
                    placeholder="Ответить..."
                    class="border px-2 py-1 rounded flex-1"
                >
                <button type="submit" class="bg-gray-200 px-3 py-1 rounded text-sm">Ответить</button>
            </form>
        @endauth

        {{-- Ответы на комментарий --}}
        @foreach ($comment->replies as $reply)
    <div class="ml-6 mt-2 border-l pl-3">

        <strong>{{ $reply->user->name }}</strong>
        <p>{{ $reply->content }}</p>

        @if(Auth::id() === $reply->user_id)
            <div x-data="{ edit: false }" class="mt-1">

                <button @click="edit = !edit" class="text-xs text-blue-500">
                    Изменить
                </button>

                <form x-show="edit" action="/comments/{{ $reply->id }}" method="POST" class="flex gap-2 mt-1">
                    @csrf
                    @method('PUT')
                    <input
                        type="text"
                        name="content"
                        value="{{ $reply->content }}"
                        class="border px-2 py-1 rounded flex-1 text-sm"
                    >
                    <button class="bg-blue-500 text-white px-2 py-1 rounded text-xs">
                        Сохранить
                    </button>
                </form>

                <form action="/comments/{{ $reply->id }}" method="POST" class="mt-1">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-500 text-xs">
                        Удалить
                    </button>
                </form>

            </div>
        @endif

    </div>
@endforeach

    </div>
@endforeach

@endsection