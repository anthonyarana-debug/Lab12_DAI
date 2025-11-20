<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class NotaController extends Controller
{
    public function index()
    {
        $users = User::with(['notas', 'notas.recordatorio'])
            ->addSelect([
                'total_notas' => Nota::selectRaw('count(*)')
                    ->whereColumn('user_id', 'users.id')
                    ->whereHas('recordatorio', fn($query) => $query->where('fecha_vencimiento', '>=', now()))
            ])
            ->get();

        return view('notas.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'fecha_vencimiento' => 'required|date|after:now',
        ]);

        $note = Nota::create([
            'user_id' => $validated['user_id'],
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'],
        ]);

        $note->recordatorio()->create([
            'fecha_vencimiento' => $validated['fecha_vencimiento'],
        ]);

        return redirect()->route('notas.index')->with('success', 'Nota creada!');
    }

    public function destroy(Nota $nota)
    {
        if ($nota->user_id !== Auth::id()) {
            abort(403, 'No autorizado para eliminar esta nota.');
        }

        $nota->delete();

        return redirect()->route('notas.index')->with('success', 'Nota eliminada exitosamente.');
    }
}