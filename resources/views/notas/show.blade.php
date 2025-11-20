@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $nota->titulo_formateado }}</h1>
    <p>{{ $nota->contenido }}</p>
    <p><small>Creada: {{ $nota->created_at->diffForHumans() }}</small></p>

    <div class="mb-3">
        <a href="{{ route('notas.index') }}" class="btn btn-secondary">Volver a Notas</a>
        <a href="{{ route('notas.edit', $nota) }}" class="btn btn-warning">Editar</a>
        <form method="POST" action="{{ route('notas.destroy', $nota) }}" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar esta nota y todo su contenido (recordatorio y actividades)?')">
                Eliminar Nota
            </button>
        </form>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Recordatorio</h5>
        </div>
        <div class="card-body">
            @if($nota->recordatorio)
                <p><strong>Fecha de Vencimiento:</strong> {{ $nota->recordatorio->fecha_vencimiento->format('d/m/Y H:i') }}</p>
                <p><strong>Estado:</strong>
                    @if($nota->recordatorio->completado)
                        <span class="badge bg-success">Completado</span>
                    @else
                        <span class="badge bg-warning">Pendiente</span>
                    @endif
                </p>
            @else
                <p>No tiene un recordatorio asociado.</p>
            @endif
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Actividades</h5>
        </div>
        <div class="card-body">
            @if($nota->actividades->isNotEmpty())
                <ul class="list-group">
                    @foreach($nota->actividades as $actividad)
                        <li class="list-group-item">
                            <strong>{{ $actividad->titulo }}</strong>
                            <p class="mb-1">{{ $actividad->descripcion ?? 'Sin descripción' }}</p>
                            <small>
                                Estado:
                                @if($actividad->completada)
                                    <span class="badge bg-success">Completada</span>
                                @else
                                    <span class="badge bg-warning">Pendiente</span>
                                @endif
                            </small>
                            <div class="mt-1">
                                <a href="{{ route('actividades.edit', $actividad) }}" class="btn btn-sm btn-outline-warning">Editar</a>
                                <form method="POST" action="{{ route('actividades.destroy', $actividad) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta actividad?')">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No hay actividades asociadas a esta nota.</p>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('actividades.create', ['nota_id' => $nota->id]) }}" class="btn btn-primary">Agregar Nueva Actividad</a>
    </div>

</div>
@endsection