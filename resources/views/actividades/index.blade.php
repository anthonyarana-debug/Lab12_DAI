@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mis Actividades</h1>

    <a href="{{ route('actividades.create') }}" class="btn btn-primary mb-3">Crear Nueva Actividad</a>

    @if($actividades->isEmpty())
        <p>No tienes actividades.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Nota</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($actividades as $actividad)
                    <tr>
                        <td>{{ $actividad->titulo }}</td>
                        <td><a href="{{ route('notas.show', $actividad->nota) }}">{{ $actividad->nota->titulo }}</a></td>
                        <td>{{ $actividad->completada ? 'Completada' : 'Pendiente' }}</td>
                        <td>
                            <a href="{{ route('actividades.show', $actividad) }}" class="btn btn-sm btn-info">Ver</a>
                            <a href="{{ route('actividades.edit', $actividad) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form method="POST" action="{{ route('actividades.destroy', $actividad) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection