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

    /**
     * Relación con las incidencias reportadas para esta orden.
     */
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'po', 'po');
    }

    /**
     * Relación con las evidencias de entrega registradas.
     */
    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'po', 'po');
    }

    /**
     * Relación con la última evidencia de entrega.
     */
    public function evidencia()
    {
        return $this->hasOne(Evidencia::class, 'po', 'po')->latestOfMany();
    }
}
