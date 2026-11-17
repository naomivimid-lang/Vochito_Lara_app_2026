<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public const ESTATUS = [
    'Orden recibida', 'En fabricación', 'Control de calidad', 'Empaquetado', 'En tránsito',
    ];

    // Color del badge de Bootstrap por estatus (mismo mapa que js/datos.js)
    public const COLOR_ESTATUS = [
        'Orden recibida' => 'secondary',
        'En fabricación' => 'warning',
        'Control de calidad' => 'info',
        'Empaquetado' => 'primary',
        'En tránsito' => 'success',
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
