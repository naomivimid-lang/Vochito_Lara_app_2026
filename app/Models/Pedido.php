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
        'Cancelado' => 'danger',
    ];

    protected $fillable = ['cliente', 'producto_id', 'cantidad', 'entrega', 'estatus'];

    protected $casts = [
        'entrega' => 'datetime',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Solicitudes de cancelación o modificación asociadas al pedido.
     */
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'pedido_id');
    }

    /**
     * Resuelve el número de PO en formato corto o completo hacia un Pedido existente.
     */
    public static function buscarPorPo(string $po): ?self
    {
        if (preg_match('/(\d{1,6})$/', trim($po), $matches)) {
            $id = (int) $matches[1];
            if ($id > 0) {
                return self::find($id);
            }
        }

        return null;
    }
}
