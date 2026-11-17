<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public const ESTATUS = [
    'Orden recibida', 'En fabricación', 'Control de calidad', 'Empaquetado', 'En tránsito',
    ];
    
    protected $fillable = ['cliente', 'producto_id', 'cantidad', 'entrega', 'estatus'];

    protected $casts = [
        'entrega' => 'datetime',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
