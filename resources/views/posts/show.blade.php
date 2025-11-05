@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->content }}</p>
    <p><small>Por: {{ $post->user->name }}</small></p>

    <hr>

    @auth
        <h3>Agregar un comentario</h3>
        <form method="POST" action="{{ route('comments.store') }}">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <div class="mb-3">
                <textarea name="body" class="form-control" rows="3" placeholder="Escribe tu comentario..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Comentar</button>
        </form>
    @else
        <p><a href="{{ route('login') }}">Inicia sesión</a> para comentar.</p>
    @endauth

    <hr>

    <h3>Comentarios ({{ $post->comments->count() }})</h3>
    @if($post->comments->isNotEmpty())
        @foreach ($post->comments as $comment)
            <div class="card mb-3">
                <div class="card-body">
                    <strong>{{ $comment->user->name }}</strong>
                    <p class="mb-1">{{ $comment->body }}</p>
                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>

                    @auth
                        @if (Auth::id() === $comment->user_id)
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" 
                                        onclick="return confirm('¿Eliminar este comentario?')">
                                    Eliminar
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        @endforeach
    @else
        <p>No hay comentarios aún.</p>
    @endif

    <a href="{{ route('posts.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection