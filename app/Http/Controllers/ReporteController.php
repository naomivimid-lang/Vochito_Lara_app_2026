<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
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
        $hasta = $request->query('hasta', now()->format('Y-m-d'));

        $reporte = $this->calcularMetricas($desde, $hasta);

        return view('vochito.reporte_desempeno', compact('reporte', 'desde', 'hasta'));
    }

    /**
     * Genera y retorna el reporte filtrado por rango de fechas (vía JSON para AJAX o POST).
     */
    public function generar(Request $request)
    {
        $validated = $request->validate([
            'desde' => 'required|date',
            'hasta' => 'required|date|after_or_equal:desde',
        ]);

        $desde = $validated['desde'];
        $hasta = $validated['hasta'];

        $reporte = $this->calcularMetricas($desde, $hasta);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'rango' => ['desde' => $desde, 'hasta' => $hasta],
                'metricas' => $reporte['metricas'],
                'pedidos' => $reporte['pedidos'],
            ]);
        }

        return view('vochito.reporte_desempeno', compact('reporte', 'desde', 'hasta'));
    }

    /**
     * Calcula métricas de desempeño: entregados a tiempo, retrasados y cancelados.
     */
    private function calcularMetricas($desde, $hasta)
    {
        $fechaInicio = Carbon::parse($desde)->startOfDay();
        $fechaFin = Carbon::parse($hasta)->endOfDay();

        if (Schema::hasTable('pedidos')) {
            $pedidos = Pedido::whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->orWhereBetween('entrega_estimada', [$fechaInicio, $fechaFin])
                ->get();

            if ($pedidos->isNotEmpty()) {
                $aTiempo = $pedidos->where('desempeno', 'a_tiempo')->count();
                $retrasados = $pedidos->where('desempeno', 'retrasado')->count();
                $cancelados = $pedidos->where('desempeno', 'cancelado')->count();
                $total = $pedidos->count();

                $efectividad = $total > 0 ? round(($aTiempo / $total) * 100, 1) : 0;

                return [
                    'metricas' => [
                        'entregados_a_tiempo' => $aTiempo,
                        'retrasados' => $retrasados,
                        'cancelados' => $cancelados,
                        'total' => $total,
                        'porcentaje_efectividad' => $efectividad,
                    ],
                    'pedidos' => $pedidos,
                ];
            }
        }

        // Datos demostrativos dinámicos coherentes con el prototipo de Bochito
        $pedidosDemo = [
            [
                'po' => 'PO-2026-0001',
                'cliente' => 'Frutería Don Chuy',
                'producto' => 'Huacales de madera',
                'cantidad' => 120,
                'entrega_estimada' => Carbon::parse($desde)->addDays(2)->format('Y-m-d H:i'),
                'entrega_real' => Carbon::parse($desde)->addDays(2)->subHours(1)->format('Y-m-d H:i'),
                'estatus' => 'Entregado',
                'desempeno' => 'a_tiempo',
            ],
            [
                'po' => 'PO-2026-0002',
                'cliente' => 'Artesanías Raíces',
                'producto' => 'Cajas de embalaje',
                'cantidad' => 60,
                'entrega_estimada' => Carbon::parse($desde)->addDays(4)->format('Y-m-d H:i'),
                'entrega_real' => Carbon::parse($desde)->addDays(5)->format('Y-m-d H:i'),
                'estatus' => 'Entregado',
                'desempeno' => 'retrasado',
            ],
            [
                'po' => 'PO-2026-0003',
                'cliente' => 'Abarrotes La Esperanza',
                'producto' => 'Bolsas de papel kraft',
                'cantidad' => 2000,
                'entrega_estimada' => Carbon::parse($desde)->addDays(6)->format('Y-m-d H:i'),
                'entrega_real' => Carbon::parse($desde)->addDays(6)->format('Y-m-d H:i'),
                'estatus' => 'Entregado',
                'desempeno' => 'a_tiempo',
            ],
            [
                'po' => 'PO-2026-0004',
                'cliente' => 'Verduras Hernández',
                'producto' => 'Rejas plásticas',
                'cantidad' => 80,
                'entrega_estimada' => Carbon::parse($desde)->addDays(8)->format('Y-m-d H:i'),
                'entrega_real' => null,
                'estatus' => 'Cancelado',
                'desempeno' => 'cancelado',
            ],
            [
                'po' => 'PO-2026-0005',
                'cliente' => 'Cooperativa Sierra Verde',
                'producto' => 'Etiquetas adhesivas',
                'cantidad' => 5000,
                'entrega_estimada' => Carbon::parse($desde)->addDays(10)->format('Y-m-d H:i'),
                'entrega_real' => Carbon::parse($desde)->addDays(10)->format('Y-m-d H:i'),
                'estatus' => 'Entregado',
                'desempeno' => 'a_tiempo',
            ],
        ];

        $aTiempo = 1180;
        $retrasados = 52;
        $cancelados = 16;
        $total = $aTiempo + $retrasados + $cancelados;
        $efectividad = round(($aTiempo / $total) * 100, 1);

        return [
            'metricas' => [
                'entregados_a_tiempo' => $aTiempo,
                'retrasados' => $retrasados,
                'cancelados' => $cancelados,
                'total' => $total,
                'porcentaje_efectividad' => $efectividad,
            ],
            'pedidos' => $pedidosDemo,
        ];
    }
}
