@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Notes and Reminders</h1>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header text-center">Formulario para Crear Nota</div>
        <div class="card-body">
            <form method="POST" action="{{ route('notas.store') }}">
                @csrf
                <div class="row mb-3">
                    <label for="user_id" class="col-sm-2 col-form-label">Seleccionar Usuario:</label>
                    <div class="col-sm-10">
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">-- Selecciona un usuario --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="titulo" class="col-sm-2 col-form-label">Título Nota:</label>
                    <div class="col-sm-10">
                        <input type="text" name="titulo" id="titulo" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="contenido" class="col-sm-2 col-form-label">Contenido:</label>
                    <div class="col-sm-10">
                        <textarea name="contenido" id="contenido" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="fecha_vencimiento" class="col-sm-2 col-form-label">Fecha Vencimiento:</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control" required>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Añadir Nota</button>
                </div>
            </form>
        </div>
    </div>

    @foreach($users as $user)
        <div class="card mb-3">
            <div class="card-header">
                Usuario: {{ $user->name }} ({{ $user->total_notas }} Active Notes)
            </div>
            <div class="card-body">
                @if($user->notas->isEmpty())
                    <p class="text-center">No tiene notas activas.</p>
                @else
                    <ul class="list-group">
                        @foreach($user->notas as $nota)
                            <li class="list-group-item">
                                <strong>{{ $nota->titulo_formateado }}</strong>
                                <p>{{ $nota->contenido }}</p>
                                <small>
                                    Due: {{ $nota->recordatorio->fecha_vencimiento->format('Y-m-d H:i') }}
                                    (
                                    @if($nota->recordatorio->completado)
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                    )
                                </small>

                                <div class="mt-2">
                                    <form method="POST" action="{{ route('notas.destroy', $nota) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta nota? Esta acción también eliminará su recordatorio y todas sus actividades.')">
                                            Eliminar Nota
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection