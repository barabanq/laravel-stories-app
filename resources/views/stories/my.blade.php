@extends('layouts.app')

@section('content')

<form method="GET" action="/stories" class="mb-4">
    <input type="text" name="q" placeholder="Поиск..." value="{{ request('q') }}">
    <button type="submit">Найти</button>
</form>

@if(session('success'))
<div class="mb-4 text-green-600">
    {{ session('success') }}
</div>
@endif

<h1 class="text-2xl font-bold mb-4">Мои истории</h1>

@foreach ($stories as $story)
    <div class="mb-4 p-4 border rounded bg-white">

        <h3 class="text-lg font-semibold">
            {{ $story->title }}
        </h3>

        <p class="mb-2">{{ $story->content }}</p>

        <p class="text-sm text-gray-500 mb-2">
            Статус: {{ $story->status }}
        </p>

        <div class="mb-2">
            @foreach ($story->tags as $tag)
                <span class="text-sm bg-gray-200 px-2 py-1 rounded mr-1">
                    #{{ $tag->name }}
                </span>
            @endforeach
        </div>

        <div class="mt-3 space-x-2">
            @if ($story->status === 'approved')
                <a href="/stories/{{ $story->id }}" class="text-blue-500 hover:underline">
                    Открыть
                </a>
            @endif

            <a href="/stories/{{ $story->id }}/edit" class="text-yellow-600 hover:underline">
                Редактировать
            </a>

            <form action="/stories/{{ $story->id }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button class="text-red-600 hover:underline">
                    Удалить
                </button>
            </form>
        </div>

    </div>
@endforeach

@endsection