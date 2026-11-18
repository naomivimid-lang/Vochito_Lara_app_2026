<?php

namespace Database\Seeders;

use App\Models\Pedido;
use App\Models\Solicitud;
use Illuminate\Database\Seeder;

class SolicitudSeeder extends Seeder
{
    /**
     * Carga solicitudes de ejemplo vinculadas a los pedidos creados por ProductoSeeder.
     */
    public function run(): void
    {
        $anio = date('Y');
        $pedido1 = Pedido::find(1);
        $pedido2 = Pedido::find(2);
        $pedido4 = Pedido::find(4);

        $solicitudes = [
            [
                'pedido_id'       => $pedido1?->id,
                'tipo'            => 'cancelacion',
                'po'              => "PO-{$anio}-0001",
                'email'           => 'maria.lopez@ejemplo.com',
                'motivo'          => 'Demora en tiempo de entrega',
                'comentarios'     => 'El cliente solicita cancelar antes del embarque por cambio de fecha.',
                'estado'          => 'Pendiente',
                'respuesta_admin' => null,
            ],
            [
                'pedido_id'       => $pedido2?->id,
                'tipo'            => 'modificacion',
                'po'              => "PO-{$anio}-0002",
                'email'           => 'jose.hernandez@ejemplo.com',
                'motivo'          => 'Ajuste de volumen o cantidad',
                'comentarios'     => 'Incrementar el pedido de tortillas de maíz en 2 kg adicionales.',
                'estado'          => 'Aprobada',
                'respuesta_admin' => 'Ajuste autorizado y confirmado con almacén.',
            ],
            [
                'pedido_id'       => $pedido4?->id,
                'tipo'            => 'cancelacion',
                'po'              => "PO-{$anio}-0004",
                'email'           => 'juan.perez@ejemplo.com',
                'motivo'          => 'Error en productos seleccionados',
                'comentarios'     => 'Orden duplicada al registrar en el portal.',
                'estado'          => 'Aprobada',
                'respuesta_admin' => 'Cancelación procesada sin penalización.',
            ],
        ];

        foreach ($solicitudes as $s) {
            Solicitud::firstOrCreate(
                ['po' => $s['po'], 'tipo' => $s['tipo']],
                $s
            );
        }

        if ($pedido4 && $pedido4->estatus !== 'En tránsito') {
            $pedido4->update(['estatus' => 'Cancelado']);
        }
    }
}
