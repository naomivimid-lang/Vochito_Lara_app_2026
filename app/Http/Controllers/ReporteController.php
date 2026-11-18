<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerarReporteRequest;
use App\Models\Evidencia;
use App\Models\Incidencia;
use App\Models\Pedido;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class ReporteController extends Controller
{
    /**
     * Muestra la vista principal del reporte de desempeño con las métricas calculadas.
     */
    public function index(Request $request)
    {
        $desde = $request->query('desde', now()->subMonth()->format('Y-m-d'));
        $hasta = $request->query('hasta', now()->addMonth()->format('Y-m-d'));

        $reporte = $this->calcularMetricas($desde, $hasta);

        return view('vochito.reporte_desempeno', compact('reporte', 'desde', 'hasta'));
    }

    /**
     * Alias RESTful store para Route::resource('reportes').
     */
    public function store(GenerarReporteRequest $request)
    {
        return $this->generar($request);
    }

    /**
     * Genera y retorna el reporte filtrado por rango de fechas (JSON para AJAX o vista Blade).
     */
    public function generar(GenerarReporteRequest $request)
    {
        $validated = $request->validated();
        $desde = $validated['desde'];
        $hasta = $validated['hasta'];

        $reporte = $this->calcularMetricas($desde, $hasta);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'db_ready' => Schema::hasTable('pedidos'),
                'rango'    => ['desde' => $desde, 'hasta' => $hasta],
                'metricas' => $reporte['metricas'],
                'pedidos'  => $reporte['pedidos'],
            ]);
        }

        return view('vochito.reporte_desempeno', compact('reporte', 'desde', 'hasta'));
    }

    /**
     * Calcula métricas reales de desempeño: entregados a tiempo, retrasados y cancelados.
     */
    private function calcularMetricas(string $desde, string $hasta): array
    {
        $fechaInicio = Carbon::parse($desde)->startOfDay();
        $fechaFin = Carbon::parse($hasta)->endOfDay();

        if (Schema::hasTable('pedidos')) {
            $pedidosDb = Pedido::with('producto')
                ->where(function ($q) use ($fechaInicio, $fechaFin) {
                    $q->whereBetween('entrega', [$fechaInicio, $fechaFin])
                      ->orWhereBetween('created_at', [$fechaInicio, $fechaFin]);
                })
                ->orderBy('entrega')
                ->get();

            $evidenciasPorPo = Schema::hasTable('evidencias')
                ? Evidencia::all()->keyBy(fn ($e) => strtoupper($e->orden_compra))
                : collect();

            $retrasosPorPo = Schema::hasTable('incidencias')
                ? Incidencia::where('tipo_incidencia', 'Retraso')->get()->keyBy(fn ($i) => strtoupper($i->orden_compra))
                : collect();

            $cancelacionesPorPedido = Schema::hasTable('solicitudes')
                ? Solicitud::where('tipo', 'cancelacion')
                    ->where('estado', 'Aprobada')
                    ->get()
                : collect();

            $aTiempo = 0;
            $retrasados = 0;
            $cancelados = 0;
            $listado = [];

            foreach ($pedidosDb as $pedido) {
                $anio = $pedido->created_at?->format('Y') ?? date('Y');
                $poCorto = 'PO-' . str_pad((string) $pedido->id, 4, '0', STR_PAD_LEFT);
                $poCompleto = "PO-{$anio}-" . str_pad((string) $pedido->id, 4, '0', STR_PAD_LEFT);

                $evidencia = $evidenciasPorPo->get($poCompleto) ?? $evidenciasPorPo->get($poCorto);
                $incidenciaRetraso = $retrasosPorPo->get($poCompleto) ?? $retrasosPorPo->get($poCorto);
                $cancelSolicitud = $cancelacionesPorPedido->first(function ($s) use ($pedido, $poCorto, $poCompleto) {
                    return $s->pedido_id === $pedido->id || in_array(strtoupper($s->po), [$poCorto, $poCompleto], true);
                });

                $entregaReal = $evidencia?->fecha_entrega;

                if ($pedido->estatus === 'Cancelado' || $cancelSolicitud) {
                    $desempeno = 'cancelado';
                    $cancelados++;
                } elseif (
                    ($entregaReal && $pedido->entrega && $entregaReal->gt($pedido->entrega))
                    || $incidenciaRetraso
                    || ($pedido->estatus !== 'En tránsito' && $pedido->entrega && $pedido->entrega->isPast() && !$entregaReal)
                ) {
                    $desempeno = 'retrasado';
                    $retrasados++;
                } else {
                    $desempeno = 'a_tiempo';
                    $aTiempo++;
                }

                $listado[] = [
                    'po'               => $poCompleto,
                    'cliente'          => $pedido->cliente,
                    'producto'         => $pedido->producto?->nombre ?? 'Producto #' . $pedido->producto_id,
                    'cantidad'         => $pedido->cantidad,
                    'entrega_estimada' => $pedido->entrega?->format('Y-m-d H:i'),
                    'entrega_real'     => $entregaReal?->format('Y-m-d H:i'),
                    'estatus'          => $cancelSolicitud && $pedido->estatus !== 'Cancelado' ? 'Cancelado' : $pedido->estatus,
                    'desempeno'        => $desempeno,
                ];
            }

            $total = $aTiempo + $retrasados + $cancelados;
            $efectividad = $total > 0 ? round(($aTiempo / $total) * 100, 1) : 0;

            return [
                'metricas' => [
                    'entregados_a_tiempo'    => $aTiempo,
                    'retrasados'             => $retrasados,
                    'cancelados'             => $cancelados,
                    'total'                  => $total,
                    'porcentaje_efectividad' => $efectividad,
                ],
                'pedidos' => $listado,
            ];
        }

        // Respaldo demostrativo cuando la base aún no está migrada
        $pedidosDemo = [
            [
                'po'               => 'PO-2026-0001',
                'cliente'          => 'Frutería Don Chuy',
                'producto'         => 'Huacales de madera',
                'cantidad'         => 120,
                'entrega_estimada' => Carbon::parse($desde)->addDays(2)->format('Y-m-d H:i'),
                'entrega_real'     => Carbon::parse($desde)->addDays(2)->subHours(1)->format('Y-m-d H:i'),
                'estatus'          => 'En tránsito',
                'desempeno'        => 'a_tiempo',
            ],
            [
                'po'               => 'PO-2026-0002',
                'cliente'          => 'Artesanías Raíces',
                'producto'         => 'Cajas de embalaje',
                'cantidad'         => 60,
                'entrega_estimada' => Carbon::parse($desde)->addDays(4)->format('Y-m-d H:i'),
                'entrega_real'     => Carbon::parse($desde)->addDays(5)->format('Y-m-d H:i'),
                'estatus'          => 'En fabricación',
                'desempeno'        => 'retrasado',
            ],
            [
                'po'               => 'PO-2026-0004',
                'cliente'          => 'Verduras Hernández',
                'producto'         => 'Rejas plásticas',
                'cantidad'         => 80,
                'entrega_estimada' => Carbon::parse($desde)->addDays(8)->format('Y-m-d H:i'),
                'entrega_real'     => null,
                'estatus'          => 'Cancelado',
                'desempeno'        => 'cancelado',
            ],
        ];

        return [
            'metricas' => [
                'entregados_a_tiempo'    => 1,
                'retrasados'             => 1,
                'cancelados'             => 1,
                'total'                  => 3,
                'porcentaje_efectividad' => 33.3,
            ],
            'pedidos' => $pedidosDemo,
        ];
    }
}
