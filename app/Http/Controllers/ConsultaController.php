<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    // Búsqueda y filtrado de pedidos desde PostgreSQL (RF04)
    public function busquedas(Request $request)
    {
        $filtros = $request->validate([
            'po' => ['nullable', 'string', 'max:120'],
            'fecha' => ['nullable', 'date'],
            'estatus' => ['nullable', 'string', 'max:30'],
        ]);

        $consulta = Pedido::with('producto');

        // Filtro por número de pedido (PO-0001 o 1), cliente o producto
        if (filled($filtros['po'] ?? null)) {
            $busqueda = trim($filtros['po']);
            $porPo = Pedido::buscarPorPo($busqueda);

            $consulta->where(function ($query) use ($busqueda, $porPo) {
                $query->where('cliente', 'ILIKE', "%{$busqueda}%")
                    ->orWhereHas('producto', fn ($q) => $q->where('nombre', 'ILIKE', "%{$busqueda}%"));

                if ($porPo) {
                    $query->orWhere('id', $porPo->id);
                }
            });
        }

        // Filtro por fecha de entrega
        if (filled($filtros['fecha'] ?? null)) {
            $consulta->whereDate('entrega', $filtros['fecha']);
        }

        // Filtro por estado
        if (filled($filtros['estatus'] ?? null)) {
            $consulta->where('estatus', $filtros['estatus']);
        }

        // Paginación: 10 resultados por página
        $resultados = $consulta->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('vochito.busquedas', [
            'resultados' => $resultados,
            'filtros' => $filtros,
        ]);
    }

    // Historial de pedidos desde PostgreSQL
    public function historial(Request $request)
    {
        $estatus = $request->validate(['estatus' => ['nullable', 'string', 'max:30']])['estatus'] ?? null;

        $pedidos = Pedido::with('producto')
            ->when($estatus, fn ($q, $e) => $q->where('estatus', $e))
            ->orderByDesc('entrega')
            ->paginate(10)
            ->withQueryString();

        return view('vochito.historial', [
            'pedidos' => $pedidos,
            'estatusSeleccionado' => $estatus,
        ]);
    }

    // Mediciones reales de consultas a PostgreSQL (RNF01)
    public function tiempoRespuesta()
    {
        $medir = function (callable $consulta): float {
            $inicio = microtime(true);
            $consulta();

            return microtime(true) - $inicio;
        };

        $tiempoEstatus = $medir(fn () => Pedido::query()->select('id', 'estatus')->latest()->first());

        $tiempoTrazabilidad = $medir(fn () => Pedido::with('producto', 'solicitudes')->latest()->limit(50)->get());

        $tiempoBusqueda = $medir(fn () => Pedido::query()
            ->whereIn('estatus', Pedido::ESTATUS)
            ->whereDate('entrega', '>=', now()->subMonth())
            ->count());

        $metricas = [
            ['consulta' => 'Estatus de un pedido', 'icono' => 'bi-arrow-repeat', 'color' => 'icon-circle-blue', 'segundos' => $tiempoEstatus],
            ['consulta' => 'Trazabilidad (etapas del pedido)', 'icono' => 'bi-truck', 'color' => 'icon-circle-green', 'segundos' => $tiempoTrazabilidad],
            ['consulta' => 'Búsqueda por PO, fechas o estatus', 'icono' => 'bi-geo-alt', 'color' => 'icon-circle-yellow', 'segundos' => $tiempoBusqueda],
        ];

        foreach ($metricas as &$m) {
            $m['objetivo'] = '2 segundos o menos';
            $m['medicion'] = number_format($m['segundos'], 4) . ' s';
            $m['cumple'] = $m['segundos'] <= 2;
        }

        return view('vochito.tiempo_respuesta', ['metricas' => $metricas]);
    }
}
