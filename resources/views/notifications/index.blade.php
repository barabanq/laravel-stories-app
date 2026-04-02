@extends('layouts.app')

@section('content')

<h1 class="text-xl font-bold mb-4">Уведомления</h1>

@forelse($notifications as $notification)
    <div class="border-b py-2">
        <p>{{ $notification->data }}</p>
        <span class="text-xs text-gray-500">
            {{ $notification->created_at->diffForHumans() }}
        </span>
    </div>
@empty
    <p>Нет уведомлений</p>
@endforelse

@endsection