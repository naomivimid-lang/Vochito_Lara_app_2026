<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $fillable = [
        'orden_compra',
        'tipo_incidencia',
        'causa',
        'nueva_fecha_estimada'
    ];

    protected $casts = [
        'nueva_fecha_estimada' => 'datetime',
    ];
}
