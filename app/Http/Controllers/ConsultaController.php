<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class ConsultaController extends Controller
{
    // Búsqueda y filtrado de pedidos desde PostgreSQL
    public function busquedas(Request $request)
    {
        $consulta = Pedido::query();

        // Filtro por número de pedido, producto o cliente
        if ($request->filled('po')) {
            $busqueda = trim($request->po);

            $consulta->where(function ($query) use ($busqueda) {
                $query->where('po', 'ILIKE', "%{$busqueda}%")
                    ->orWhere('producto', 'ILIKE', "%{$busqueda}%")
                    ->orWhere('cliente', 'ILIKE', "%{$busqueda}%");
            });
        }

        // Filtro por fecha de entrega
        if ($request->filled('fecha')) {
            $consulta->whereDate('entrega', $request->fecha);
        }

        // Filtro por estado
        if ($request->filled('estatus')) {
            $consulta->where('estatus', $request->estatus);
        }

        // Paginación: 10 resultados por página
        $pedidos = $consulta->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('vochito.busquedas', [
            'resultados' => $pedidos,
            'filtros' => $request->only(['po', 'fecha', 'estatus']),
        ]);
    }

    // Historial de pedidos desde PostgreSQL
    public function historial(Request $request)
    {
        $consulta = Pedido::query();

        // Filtro por estado
        if ($request->filled('estatus')) {
            $consulta->where('estatus', $request->estatus);
        }

        // Paginación: 10 resultados por página
        $pedidos = $consulta->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('vochito.historial', [
            'pedidos' => $pedidos,
            'estatusSeleccionado' => $request->estatus,
        ]);
    }

    // Mediciones reales de consultas a PostgreSQL
    public function tiempoRespuesta()
    {
        $inicio = microtime(true);

        Pedido::query()
            ->select('id', 'estatus')
            ->latest()
            ->first();

        $tiempoEstatus = number_format(microtime(true) - $inicio, 4) . ' s';

        $inicio = microtime(true);

        $pedidos = Pedido::query()
            ->select('id', 'po', 'estatus', 'incidencias')
            ->get();

        $tiempoTrazabilidad = number_format(microtime(true) - $inicio, 4) . ' s';

        $inicio = microtime(true);

        Pedido::query()
            ->whereNotNull('po')
            ->count();

        $tiempoBusqueda = number_format(microtime(true) - $inicio, 4) . ' s';

        $metricas = [
            [
                'consulta' => 'Estatus de un pedido',
                'icono' => 'bi-arrow-repeat',
                'color' => 'icon-circle-blue',
                'objetivo' => '2 segundos o menos',
                'medicion' => $tiempoEstatus,
                'cumple' => (float) $tiempoEstatus <= 2,
            ],
            [
                'consulta' => 'Trazabilidad (etapas del pedido)',
                'icono' => 'bi-truck',
                'color' => 'icon-circle-green',
                'objetivo' => '2 segundos o menos',
                'medicion' => $tiempoTrazabilidad,
                'cumple' => (float) $tiempoTrazabilidad <= 2,
            ],
            [
                'consulta' => 'Búsqueda por PO, fechas o estatus',
                'icono' => 'bi-geo-alt',
                'color' => 'icon-circle-yellow',
                'objetivo' => '2 segundos o menos',
                'medicion' => $tiempoBusqueda,
                'cumple' => (float) $tiempoBusqueda <= 2,
            ],
        ];

        return view('vochito.tiempo_respuesta', [
            'metricas' => $metricas,
            'pedidos' => $pedidos,
        ]);
    }

// Consultar pedidos con filtros y paginación desde PostgreSQL
public function productos(Request $request)
{
    $consulta = Pedido::query();

    // Buscar por número PO
    if ($request->filled('po')) {
        $consulta->where(
            'po',
            'ILIKE',
            '%' . trim($request->po) . '%'
        );
    }

    // Filtrar por estatus
    if ($request->filled('estatus')) {
        $consulta->where('estatus', $request->estatus);
    }

    // Filtrar por fecha inicial
    if ($request->filled('desde')) {
        $consulta->whereDate('entrega', '>=', $request->desde);
    }

    // Filtrar por fecha final
    if ($request->filled('hasta')) {
        $consulta->whereDate('entrega', '<=', $request->hasta);
    }

    // Mostrar 10 pedidos por página
    $pedidos = $consulta->orderByDesc('created_at')
        ->paginate(10)
        ->withQueryString();

    return response()->json($pedidos);
}

}
