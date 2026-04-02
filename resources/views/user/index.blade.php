@extends('layouts.app')

@section('content')

<h1>Пользователи</h1>

<form action="/users" method="GET">
    <input type="text" name="q" placeholder="Поиск пользователей..." value="{{ request('q') }}">
    <button type="submit">Найти</button>
</form>

@foreach ($users as $user)
    <div style="margin:top 10px;">
        <a href="/user/{{ $user->id }}">
            {{ $user->name }}
        </a>
    </div>
@endforeach
@endsection