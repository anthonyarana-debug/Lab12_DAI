<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $fillable = ['nota_id', 'titulo', 'descripcion', 'completada'];

    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }
}