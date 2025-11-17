@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Actividad</h1>

    <form method="POST" action="{{ route('actividades.update', $actividad) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nota_id" class="form-label">Nota Asociada</label>
            <select name="nota_id" id="nota_id" class="form-select" required>
                @foreach($notas as $nota)
                    <option value="{{ $nota->id }}" {{ $actividad->nota_id == $nota->id ? 'selected' : '' }}>{{ $nota->titulo }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="titulo" class="form-label">Título de la Actividad</label>
            <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo', $actividad->titulo) }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion', $actividad->descripcion) }}</textarea>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="completada" id="completada" class="form-check-input" value="1" {{ $actividad->completada ? 'checked' : '' }}>
            <label class="form-check-label" for="completada">Completada</label>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Actividad</button>
        <a href="{{ route('notas.show', $actividad->nota) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection