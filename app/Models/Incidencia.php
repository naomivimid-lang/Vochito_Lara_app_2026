<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencias';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'po',
        'tipo_incidencia',
        'causa',
        'nueva_fecha_estimada',
        'estatus',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nueva_fecha_estimada' => 'datetime',
        ];
    }

    /**
     * Relación con el pedido correspondiente.
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'po', 'po');
    }
}
