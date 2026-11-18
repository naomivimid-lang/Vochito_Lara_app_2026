<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'orden_compra',
        'titulo',
        'mensaje',
        'tipo',
        'leida'
    ];

    protected $casts = [
        'leida' => 'boolean',
    ];
}