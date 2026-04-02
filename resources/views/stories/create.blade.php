@extends('layouts.app')

@section('content')

<form method="GET" action="/stories" class="mb-4">
    <input type="text" name="q" placeholder="Поиск..." value="{{ request('q') }}">
    <button type="submit">Найти</button>
</form>

@if($errors->any())
    <div class="mb-4 text-red-600">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }} </li>
            @endforeach
        </ul>
    </div>
@endif

<div class="max-w-xl">

<form action="{{ url('/stories')}}" method="POST">
    @csrf

    <div>
        <label>Заголовок</label>
        <input
            type="text"
            name="title"
            value="{{ old('title')}}"
            class="w-full border rounded px-3 py-2 mb-3"
            required
        >
    </div>

    <div>
        <label>Содержание</label>
        <textarea
            name="content"
            class="w-full border rounded px-3 py-2 mb-3"
            required
        >{{ old('content') }}</textarea>
    </div>

    <div>
        <label>Теги</label>
        <input
            type="text"
            name="tags"
            value="{{ old('tags')}}"
            class="w-full border rounded px-3 py-2 mb-3"
            placeholder="#life #fun #travel"
        >
    </div>

    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Добавить историю
    </button>

</form>

</div>

@endsection