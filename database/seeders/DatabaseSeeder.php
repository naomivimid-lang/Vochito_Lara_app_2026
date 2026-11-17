<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pedido;
use App\Models\Solicitud;
use App\Models\Incidencia;
use App\Models\Evidencia;
use App\Models\Contacto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Usuario de prueba
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Usuario Bochito',
                'password' => bcrypt('password123'),
            ]
        );

        // 2. Pedidos Iniciales
        $pedidos = [
            [
                'po' => 'PO-2026-0001',
                'cliente' => 'Frutería Don Chuy',
                'producto' => 'Huacales de madera',
                'cantidad' => 120,
                'entrega_estimada' => Carbon::now()->subDays(5)->setHour(10)->setMinute(0),
                'entrega_real' => Carbon::now()->subDays(5)->setHour(9)->setMinute(30),
                'estatus' => 'Entregado a tiempo',
                'desempeno' => 'a_tiempo',
            ],
            [
                'po' => 'PO-2026-0002',
                'cliente' => 'Artesanías Raíces',
                'producto' => 'Cajas de embalaje',
                'cantidad' => 60,
                'entrega_estimada' => Carbon::now()->subDays(3)->setHour(12)->setMinute(0),
                'entrega_real' => Carbon::now()->subDays(2)->setHour(15)->setMinute(0),
                'estatus' => 'Retrasado',
                'desempeno' => 'retrasado',
            ],
            [
                'po' => 'PO-2026-0003',
                'cliente' => 'Abarrotes La Esperanza',
                'producto' => 'Bolsas de papel kraft',
                'cantidad' => 2000,
                'entrega_estimada' => Carbon::now()->addDays(2)->setHour(9)->setMinute(0),
                'entrega_real' => null,
                'estatus' => 'Control de calidad',
                'desempeno' => 'pendiente',
            ],
            [
                'po' => 'PO-2026-0004',
                'cliente' => 'Verduras Hernández',
                'producto' => 'Rejas plásticas',
                'cantidad' => 80,
                'entrega_estimada' => Carbon::now()->subDays(1)->setHour(16)->setMinute(0),
                'entrega_real' => null,
                'estatus' => 'Cancelado',
                'desempeno' => 'cancelado',
            ],
            [
                'po' => 'PO-2026-0005',
                'cliente' => 'Cooperativa Sierra Verde',
                'producto' => 'Etiquetas adhesivas',
                'cantidad' => 5000,
                'entrega_estimada' => Carbon::now()->addDays(4)->setHour(11)->setMinute(0),
                'entrega_real' => null,
                'estatus' => 'En tránsito',
                'desempeno' => 'pendiente',
            ],
        ];

        foreach ($pedidos as $p) {
            Pedido::firstOrCreate(['po' => $p['po']], $p);
        }

        // 3. Solicitudes (Cancelaciones y Modificaciones)
        $solicitudes = [
            [
                'tipo' => 'cancelacion',
                'po' => 'PO-2026-0001',
                'email' => 'donchuy@fruteria.com',
                'motivo' => 'Demora en tiempo de entrega',
                'comentarios' => 'Se solicitó cancelar porque el cliente requería entrega urgente el mismo día.',
                'estado' => 'Pendiente',
                'respuesta_admin' => null,
            ],
            [
                'tipo' => 'modificacion',
                'po' => 'PO-2026-0002',
                'email' => 'contacto@artesaniasraices.com',
                'motivo' => 'Ajuste de volumen o cantidad',
                'comentarios' => 'Incrementar pedido de 60 a 90 unidades de cajas.',
                'estado' => 'Aprobada',
                'respuesta_admin' => 'Se verificó existencias y se actualizó la orden a 90 unidades.',
            ],
            [
                'tipo' => 'cancelacion',
                'po' => 'PO-2026-0004',
                'email' => 'pedidos@verdurashernandez.com',
                'motivo' => 'Error en productos seleccionados',
                'comentarios' => 'Se duplicó la orden por error en el portal.',
                'estado' => 'Aprobada',
                'respuesta_admin' => 'Cancelación aprobada y pedido cancelado sin penalización.',
            ]
        ];

        foreach ($solicitudes as $s) {
            Solicitud::firstOrCreate(
                ['po' => $s['po'], 'tipo' => $s['tipo']],
                $s
            );
        }

        // 4. Incidencias
        Incidencia::firstOrCreate(
            ['po' => 'PO-2026-0002'],
            [
                'tipo_incidencia' => 'Falla de maquinaria',
                'causa' => 'Mantenimiento no programado en la línea de corte.',
                'nueva_fecha_estimada' => Carbon::now()->subDays(2)->setHour(15)->setMinute(0),
                'estatus' => 'Atendida',
            ]
        );

        // 5. Evidencias
        Evidencia::firstOrCreate(
            ['po' => 'PO-2026-0001'],
            [
                'recibe' => 'Jesús Don Chuy Morales',
                'fecha_entrega' => Carbon::now()->subDays(5)->setHour(9)->setMinute(30),
                'foto_path' => null,
                'comentarios' => 'Entrega recibida en almacén central con firma de conformidad.',
            ]
        );

        // 6. Mensajes de Contacto
        Contacto::firstOrCreate(
            ['correo' => 'artesano@oaxaca.mx'],
            [
                'nombre' => 'María Elena Cruz',
                'telefono' => '9511234567',
                'asunto' => 'Alianza de artesanías locales',
                'mensaje' => 'Nos gustaría incorporar nuestros huacales tallados a mano en su catálogo de distribución.',
                'leido' => true,
            ]
        );
    }
}
