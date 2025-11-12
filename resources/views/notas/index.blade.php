@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Notes and Reminders</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Formulario para crear nota -->
    <div class="card mb-4">
        <div class="card-header">Formulario para Crear Nota</div>
        <div class="card-body">
            <form method="POST" action="{{ route('notas.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="user_id" class="form-label">Seleccionar Usuario</label>
                    <select name="user_id" id="user_id" class="form-select" required>
                        <option value="">-- Selecciona un usuario --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título Nota</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="contenido" class="form-label">Contenido</label>
                    <textarea name="contenido" id="contenido" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="fecha_vencimiento" class="form-label">Fecha Vencimiento</label>
                    <input type="datetime-local" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Añadir Nota</button>
            </form>
        </div>
    </div>

    <!-- Listado de usuarios y sus notas -->
    @foreach($users as $user)
        <div class="card mb-3">
            <div class="card-header">
                Usuario: {{ $user->name }} ({{ $user->total_notas }} Active Notes)
            </div>
            <div class="card-body">
                @if($user->notas->isEmpty())
                    <p>No tiene notas activas.</p>
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
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection