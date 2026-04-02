@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6">Модерация историй</h1>

    @foreach ($stories as $story)
        <div class="bg-white shadow-md rounded-lg p-6 mb-6 border border-gray-200">
            <h3 class="text-xl font-semibold mb-2">{{ $story->title }}</h3>
            <p class="text-gray-600 mb-1"><strong>Автор:</strong> {{ $story->user->name }}</p>
            <p class="text-gray-800 mb-4">{{ $story->content }}</p>

            <div class="flex space-x-4">
                <form action="/admin/stories/{{ $story->id }}/approve" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded transition-colors">
                        Approve
                    </button>
                </form>

                <form action="/admin/stories/{{ $story->id }}/reject" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded transition-colors">
                        Reject
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection