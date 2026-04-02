@extends('layouts.app')

@section('content')

<div class="mb-6">
    <form method="GET" action="/stories" class="flex gap-2">
        <input
            type="text"
            name="q"
            placeholder="Поиск..."
            value="{{ request('q') }}"
            class="flex-1 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
        <button
            type="submit"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
        >
            Найти
        </button>
    </form>

    <div class="mt-2 flex gap-4">
        <a href="/stories" class="text-gray-700 hover:text-blue-500 transition">Новые</a>
        <a href="/stories?sort=popular" class="text-gray-700 hover:text-blue-500 transition">Популярные</a>
    </div>
</div>

<h1 class="text-3xl font-bold mb-6">Истории</h1>

<div class="grid gap-6">
@foreach ($stories as $story)
    <div class="border rounded-lg p-5 shadow-sm hover:shadow-md transition">
        <h2 class="text-2xl font-semibold mb-2">{{ $story->title }}</h2>

        <p class="text-sm text-gray-500 mb-3">
            Автор:
            <a href="/user/{{ $story->user->id }}" class="font-medium text-blue-500 hover:underline">
                {{ $story->user->name }}
            </a>
        </p>

        <p class="mb-4 text-gray-800">{{ Str::limit($story->content, 200, '...') }}</p>

        <div class="mb-4 flex flex-wrap gap-2">
            @foreach ($story->tags as $tag)
                <a
                    href="/tags/{{ $tag->id }}"
                    class="text-sm text-red-500 bg-red-100 px-2 py-1 rounded hover:bg-red-200 transition"
                >
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>

        <a
            href="/stories/{{ $story->id }}"
            class="inline-block text-blue-500 font-medium hover:underline transition"
        >
            Читать полностью →
        </a>
    </div>
@endforeach
</div>

@endsection