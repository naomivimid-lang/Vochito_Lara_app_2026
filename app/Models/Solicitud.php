<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    public const TIPOS = ['cancelacion', 'modificacion'];

    public const ESTADOS = ['Pendiente', 'Aprobada', 'Rechazada'];

    public const COLOR_ESTADOS = [
        'Pendiente' => 'warning',
        'Aprobada' => 'success',
        'Rechazada' => 'danger',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'pedido_id',
        'tipo',
        'po',
        'email',
        'motivo',
        'comentarios',
        'estado',
        'respuesta_admin',
        'fecha_resolucion',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_resolucion' => 'datetime',
        ];
    }

    /**
     * Relación con el pedido correspondiente si existe en base de datos.
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}
