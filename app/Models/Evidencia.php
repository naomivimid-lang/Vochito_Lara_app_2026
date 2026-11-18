<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    protected $fillable = [
        'orden_compra',
        'recibe',
        'fecha_entrega',
        'foto',
        'comentarios'
    ];

    protected $casts = [
        'fecha_entrega' => 'datetime',
    ];
}
