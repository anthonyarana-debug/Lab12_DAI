@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $actividad->titulo }}</h1>

    <p><strong>Nota:</strong> <a href="{{ route('notas.show', $actividad->nota) }}">{{ $actividad->nota->titulo }}</a></p>
    <p><strong>Descripción:</strong> {{ $actividad->descripcion }}</p>
    <p><strong>Estado:</strong> {{ $actividad->completada ? 'Completada' : 'Pendiente' }}</p>
    <p><strong>Creada:</strong> {{ $actividad->created_at->diffForHumans() }}</p>
    <p><strong>Última actualización:</strong> {{ $actividad->updated_at->diffForHumans() }}</p>

    <div class="mt-3">
        <a href="{{ route('actividades.edit', $actividad) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('notas.show', $actividad->nota) }}" class="btn btn-secondary">Volver a la Nota</a>

        <form method="POST" action="{{ route('actividades.destroy', $actividad) }}" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta actividad?')">Eliminar</button>
        </form>
    </div>
</div>
@endsection