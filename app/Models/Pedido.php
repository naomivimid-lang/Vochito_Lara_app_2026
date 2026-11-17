<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'po',
        'cliente',
        'producto',
        'cantidad',
        'entrega_estimada',
        'entrega_real',
        'estatus',
        'desempeno',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'entrega_estimada' => 'datetime',
            'entrega_real' => 'datetime',
            'cantidad' => 'integer',
        ];
    }

    /**
     * Relación con las solicitudes asociadas a esta orden.
     */
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'po', 'po');
    }
}
