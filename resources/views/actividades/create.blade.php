@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Nueva Actividad</h1>

    <form method="POST" action="{{ route('actividades.store') }}">
        @csrf

        <div class="mb-3">
            <label for="nota_id" class="form-label">Nota Asociada</label>
            <select name="nota_id" id="nota_id" class="form-select" required>
                <option value="">-- Selecciona una nota --</option>
                @foreach($notas as $nota)
                    <option value="{{ $nota->id }}">{{ $nota->titulo }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="titulo" class="form-label">Título de la Actividad</label>
            <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo') }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Crear Actividad</button>
        <a href="{{ route('notas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection