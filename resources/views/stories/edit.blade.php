@extends('layouts.app')

@section('content')

<form method="GET" action="/stories" class="mb-4">
    <input type="text" name="q" placeholder="Поиск..." value="{{ request('q') }}">
    <button type="submit">Найти</button>
</form>

@if($errors->any())
    <div class="mb-4 text-red-600">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="max-w-xl">

<h1 class="text-2xl font-bold mb-4">Редактировать историю</h1>

<form action="/stories/{{ $story->id }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Заголовок</label>
        <input
            type="text"
            name="title"
            value="{{ $story->title }}"
            class="w-full border rounded px-3 py-2 mb-3"
        >
    </div>

    <div>
        <label>Текст</label>
        <textarea
            name="content"
            class="w-full border rounded px-3 py-2 mb-3"
        >{{ $story->content }}</textarea>
    </div>

    <div>
        <label>Теги через запятую</label>
        <input
            type="text"
            name="tags"
            value="{{ $story->tags->pluck('name')->implode(', ') }}"
            class="w-full border rounded px-3 py-2 mb-3"
        >
    </div>

    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Сохранить
    </button>

</form>

</div>

@endsection