@extends('layouts.app')

@section('content')

<h1>Модерация историй</h1>

@foreach ($stories as $story)
    <div style="margin-bottom: 20px;">
        <h3>{{ $story->title }}</h3>
        <p><strong>Автор:</strong> {{ $story->user->name }}</p>
        <p>{{ $story->content }}</p>

        <form action="/admin/stories/{{ $story->id }}/approve" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Approve</button>
        </form>

        <form action="/admin/stories/{{ $story->id }}/reject" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Reject</button>
        </form>
    </div>
@endforeach
@endsection