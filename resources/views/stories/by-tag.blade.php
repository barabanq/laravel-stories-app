<form method="GET" action="/stories" style="margin:bottom 20px;">
    <input type="text" name="q" placeholder="Поиск..." value="{{ request('q') }}">
    <button type="submit">Найти</button>
</form>

<h1> Истории с тегом # {{ $tag->name }} </h1>

@foreach ($stories as $story)
    <div style="margin-bottom: 20px;">
        <h3>{{ $story->title }} </h3>
        <p>{{ $story->content }}</p>
    </div>
@endforeach