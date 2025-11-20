<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividades';

    protected $fillable = ['nota_id', 'titulo', 'descripcion', 'completada'];

    public function nota()
    {
        return $this->belongsTo(Nota::class)->withoutGlobalScopes();
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->withoutGlobalScopes()
                    ->with(['nota' => function ($query) {
                        $query->withTrashed()->withoutGlobalScopes();
                    }])
                    ->where($field ?? $this->getRouteKeyName(), $value)
                    ->firstOrFail();
    }
}