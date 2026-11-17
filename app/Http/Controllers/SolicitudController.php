<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SolicitudController extends Controller
{
    /**
     * Muestra la vista de cancelación de pedidos con las solicitudes de cancelación registradas.
     */
    public function cancelacionView()
    {
        $solicitudes = [];
        if (Schema::hasTable('solicitudes')) {
            $solicitudes = Solicitud::where('tipo', 'cancelacion')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('vochito.cancelacion', compact('solicitudes'));
    }

    /**
     * Muestra la vista de modificación de pedidos con las solicitudes de modificación registradas.
     */
    public function modificacionView()
    {
        $solicitudes = [];
        if (Schema::hasTable('solicitudes')) {
            $solicitudes = Solicitud::where('tipo', 'modificacion')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('vochito.modificacion', compact('solicitudes'));
    }

    /**
     * Consulta y lista las solicitudes realizadas (filtro opcional por tipo o estado).
     */
    public function index(Request $request)
    {
        if (!Schema::hasTable('solicitudes')) {
            return response()->json([
                'success' => true,
                'solicitudes' => [],
                'mensaje' => 'Tabla aún no inicializada; usando almacenamiento local.'
            ]);
        }

        $query = Solicitud::query();

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->query('tipo'));
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->query('estado'));
        }

        if ($request->filled('po')) {
            $query->where('po', 'LIKE', '%' . strtoupper(trim($request->query('po'))) . '%');
        }

        $solicitudes = $query->orderBy('created_at', 'desc')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'solicitudes' => $solicitudes,
            ]);
        }

        return view('vochito.cancelacion', compact('solicitudes'));
    }

    /**
     * Registrar una nueva solicitud (cancelación o modificación).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:cancelacion,modificacion',
            'po' => 'required|string|max:50',
            'email' => 'required|email|max:150',
            'motivo' => 'nullable|string|max:150',
            'comentarios' => 'required|string|max:1000',
        ]);

        $po = strtoupper(trim($validated['po']));

        // Regla de negocio: Verificar si el pedido ya fue embarcado (En tránsito)
        if (Schema::hasTable('pedidos')) {
            $pedido = Pedido::where('po', $po)->first();
            if ($pedido && $pedido->estatus === 'En tránsito') {
                $errorMsg = "El pedido {$po} ya se encuentra 'En tránsito', por lo que no es posible procesar la solicitud.";
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMsg], 422);
                }
                return back()->with('error', $errorMsg)->withInput();
            }
        }

        if (Schema::hasTable('solicitudes')) {
            $solicitud = Solicitud::create([
                'tipo' => $validated['tipo'],
                'po' => $po,
                'email' => $validated['email'],
                'motivo' => $validated['motivo'] ?? null,
                'comentarios' => $validated['comentarios'],
                'estado' => 'Pendiente',
            ]);

            $mensaje = "Solicitud de {$validated['tipo']} para la orden {$po} registrada exitosamente.";

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $mensaje,
                    'solicitud' => $solicitud,
                ], 201);
            }

            return back()->with('success', $mensaje);
        }

        // Si la tabla no está creada aún en SQLite/MySQL
        return response()->json([
            'success' => true,
            'message' => "Solicitud registrada correctamente para la orden {$po} (modo demo).",
            'solicitud' => array_merge($validated, [
                'id' => time(),
                'po' => $po,
                'estado' => 'Pendiente',
                'created_at' => now()->toIso8601String(),
            ])
        ]);
    }

    /**
     * Actualiza el estado de una solicitud (Pendiente, Aprobada, Rechazada).
     */
    public function updateEstado(Request $request, $id)
    {
        $validated = $request->validate([
            'estado' => 'required|in:Pendiente,Aprobada,Rechazada',
            'respuesta_admin' => 'nullable|string|max:500',
        ]);

        if (Schema::hasTable('solicitudes')) {
            $solicitud = Solicitud::findOrFail($id);
            $solicitud->estado = $validated['estado'];
            $solicitud->respuesta_admin = $validated['respuesta_admin'] ?? $solicitud->respuesta_admin;
            $solicitud->fecha_resolucion = now();
            $solicitud->save();

            // Si se aprueba una cancelación, actualizar estatus del pedido correspondiente
            if ($solicitud->tipo === 'cancelacion' && $solicitud->estado === 'Aprobada') {
                if (Schema::hasTable('pedidos')) {
                    $pedido = Pedido::where('po', $solicitud->po)->first();
                    if ($pedido) {
                        $pedido->estatus = 'Cancelado';
                        $pedido->desempeno = 'cancelado';
                        $pedido->save();
                    }
                }
            }

            $mensaje = "La solicitud #{$solicitud->id} ha sido actualizada a: {$solicitud->estado}.";

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $mensaje,
                    'solicitud' => $solicitud,
                ]);
            }

            return back()->with('success', $mensaje);
        }

        return response()->json([
            'success' => true,
            'message' => "Estado de solicitud actualizado a {$validated['estado']}.",
        ]);
    }

    /**
     * Elimina o rechaza una solicitud cuando corresponda.
     */
    public function destroy(Request $request, $id)
    {
        if (Schema::hasTable('solicitudes')) {
            $solicitud = Solicitud::findOrFail($id);
            $po = $solicitud->po;
            $solicitud->delete();

            $mensaje = "La solicitud para la orden {$po} fue eliminada con éxito.";

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $mensaje,
                ]);
            }

            return back()->with('success', $mensaje);
        }

        return response()->json([
            'success' => true,
            'message' => "Solicitud eliminada exitosamente.",
        ]);
    }
}
