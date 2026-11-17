<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Datos compartidos de pedidos en Laravel para el módulo de Consultas
        $pedidosCompartidos = [
            [
                'po' => 'PO-2026-0118',
                'cliente' => 'Abarrotes Don Chuy',
                'producto' => 'Huacales de madera',
                'cantidad' => 100,
                'fecha_pedido' => '2026-08-25',
                'fecha_entrega' => '2026-08-28',
                'estatus' => 'Entregado',
                'detalle' => 'Entrega completa en bodega central sin incidencias.',
                'tiempo_respuesta' => '0.42 s',
            ],
            [
                'po' => 'PO-2026-0112',
                'cliente' => 'Panadería La Espiga',
                'producto' => 'Bolsas de papel kraft',
                'cantidad' => 1500,
                'fecha_pedido' => '2026-08-18',
                'fecha_entrega' => '2026-08-20',
                'estatus' => 'Entregado',
                'detalle' => 'Paquete de 1500 unidades calibre reforzado.',
                'tiempo_respuesta' => '0.38 s',
            ],
            [
                'po' => 'PO-2026-0109',
                'cliente' => 'Distribuidora del Valle',
                'producto' => 'Cajas de embalaje',
                'cantidad' => 40,
                'fecha_pedido' => '2026-08-12',
                'fecha_entrega' => '2026-08-15',
                'estatus' => 'Cancelado',
                'detalle' => 'Cancelado por el cliente antes de recolección.',
                'tiempo_respuesta' => '0.31 s',
            ],
            [
                'po' => 'PO-2026-0124',
                'cliente' => 'Orgánicos Pachuca',
                'producto' => 'Miel orgánica multiflora (1L)',
                'cantidad' => 60,
                'fecha_pedido' => '2026-11-10',
                'fecha_entrega' => '2026-11-18',
                'estatus' => 'En camino',
                'detalle' => 'En ruta de reparto local hacia Pachuca Centro.',
                'tiempo_respuesta' => '0.55 s',
            ],
            [
                'po' => 'PO-2026-0129',
                'cliente' => 'Comedor El Comal',
                'producto' => 'Tortillas de maíz artesanal (kg)',
                'cantidad' => 85,
                'fecha_pedido' => '2026-11-15',
                'fecha_entrega' => '2026-11-17',
                'estatus' => 'Pendiente',
                'detalle' => 'En preparación para recolección matutina.',
                'tiempo_respuesta' => '0.29 s',
            ],
        ];

        View::share('pedidosCompartidos', $pedidosCompartidos);
    }
}