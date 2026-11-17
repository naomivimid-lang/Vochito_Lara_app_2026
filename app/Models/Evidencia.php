<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    use HasFactory;

    protected $table = 'evidencias';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'po',
        'recibe',
        'fecha_entrega',
        'foto_path',
        'comentarios',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_entrega' => 'datetime',
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
