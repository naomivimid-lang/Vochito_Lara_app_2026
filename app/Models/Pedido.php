<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'po',
        'cliente',
        'producto',
        'cantidad',
        'entrega',
        'estatus',
        'incidencias',
    ];

    protected $casts = [
        'entrega' => 'datetime',
        'incidencias' => 'array',
    ];
}