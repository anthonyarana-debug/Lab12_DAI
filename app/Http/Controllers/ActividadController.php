<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActividadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $actividades = Actividad::whereHas('nota', function ($query) {
            $query->where('user_id', Auth::id())->withoutGlobalScopes();
        })->with(['nota' => function ($query) {
            $query->withTrashed()->withoutGlobalScopes();
        }])->latest()->get();

        return view('actividades.index', compact('actividades'));
    }

    public function create()
    {
        $notas = Auth::user()->notas()->withTrashed()->get();

        return view('actividades.create', compact('notas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nota_id' => 'required|exists:notas,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $nota = Nota::findOrFail($request->nota_id);
        if ($nota->user_id !== Auth::id()) {
            abort(403, 'No autorizado para agregar actividad a esta nota.');
        }

        Actividad::create([
            'nota_id' => $request->nota_id,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'completada' => false,
        ]);

        return redirect()->route('notas.index')->with('success', 'Actividad creada exitosamente.');
    }

    public function show(Actividad $actividad)
    {
        // Carga la relación 'nota' IGNORANDO el scope global 'activa' y incluyendo eliminadas suavemente
        $actividad->load(['nota' => function ($query) {
            $query->withTrashed()->withoutGlobalScope('activa');
        }]);

        // Verifica si la relación 'nota' existe
        if (!$actividad->nota) {
            abort(404, 'La nota asociada a esta actividad no existe.');
        }

        // Ahora verifica si el usuario autenticado es el dueño de la nota
        if ($actividad->nota->user_id !== Auth::id()) {
            abort(403, 'No autorizado para ver esta actividad.');
        }

        return view('actividades.show', compact('actividad'));
    }

    public function edit(Actividad $actividad)
    {
        // Carga la relación 'nota' IGNORANDO el scope global 'activa' y incluyendo eliminadas suavemente
        $actividad->load(['nota' => function ($query) {
            $query->withTrashed()->withoutGlobalScope('activa');
        }]);

        // Verifica si la relación 'nota' existe
        if (!$actividad->nota) {
            abort(404, 'La nota asociada a esta actividad no existe.');
        }

        // Ahora verifica si el usuario autenticado es el dueño de la nota
        if ($actividad->nota->user_id !== Auth::id()) {
            abort(403, 'No autorizado para editar esta actividad.');
        }

        $notas = Auth::user()->notas()->withTrashed()->get();

        return view('actividades.edit', compact('actividad', 'notas'));
    }

    public function update(Request $request, Actividad $actividad)
    {
        // Carga la relación 'nota' IGNORANDO el scope global 'activa' y incluyendo eliminadas suavemente
        $actividad->load(['nota' => function ($query) {
            $query->withTrashed()->withoutGlobalScope('activa');
        }]);

        // Verifica si la relación 'nota' existe
        if (!$actividad->nota) {
            abort(404, 'La nota asociada a esta actividad no existe.');
        }

        // Ahora verifica si el usuario autenticado es el dueño de la nota
        if ($actividad->nota->user_id !== Auth::id()) {
            abort(403, 'No autorizado para actualizar esta actividad.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'completada' => 'boolean',
        ]);

        $actividad->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'completada' => $request->completada ?? false,
        ]);

        return redirect()->route('notas.show', $actividad->nota->id)->with('success', 'Actividad actualizada exitosamente.');
    }

    public function destroy(Actividad $actividad)
    {
        // Carga la relación 'nota' IGNORANDO el scope global 'activa'
        $actividad->load(['nota' => function ($query) {
            $query->withoutGlobalScope('activa');
        }]);

        // Verifica si la relación 'nota' existe
        if (!$actividad->nota) {
            abort(404, 'La nota asociada a esta actividad no existe.');
        }

        // Ahora verifica si el usuario autenticado es el dueño de la nota
        if ($actividad->nota->user_id !== Auth::id()) {
            abort(403, 'No autorizado para eliminar esta actividad.');
        }

        $notaId = $actividad->nota_id;

        $actividad->delete();

        return redirect()->route('notas.show', $notaId)->with('success', 'Actividad eliminada exitosamente.');
    }
}