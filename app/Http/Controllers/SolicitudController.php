<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSolicitudRequest;
use App\Http\Requests\UpdateSolicitudRequest;
use App\Models\Pedido;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SolicitudController extends Controller
{
    /**
     * Muestra la vista de cancelación de pedidos con las solicitudes registradas.
     */
    public function cancelacionView()
    {
        $solicitudes = collect();
        if (Schema::hasTable('solicitudes')) {
            $solicitudes = Solicitud::with('pedido.producto')
                ->where('tipo', 'cancelacion')
                ->latest()
                ->get();
        }

        return view('vochito.cancelacion', compact('solicitudes'));
    }

    /**
     * Muestra la vista de modificación de pedidos con las solicitudes registradas.
     */
    public function modificacionView()
    {
        $solicitudes = collect();
        if (Schema::hasTable('solicitudes')) {
            $solicitudes = Solicitud::with('pedido.producto')
                ->where('tipo', 'modificacion')
                ->latest()
                ->get();
        }

        return view('vochito.modificacion', compact('solicitudes'));
    }

    /**
     * Consulta y lista las solicitudes realizadas (filtro opcional por tipo, estado o PO).
     */
    public function index(Request $request)
    {
        $dbReady = Schema::hasTable('solicitudes');
        $solicitudes = collect();

        if ($dbReady) {
            $query = Solicitud::with('pedido.producto');

            if ($request->filled('tipo')) {
                $query->where('tipo', $request->query('tipo'));
            }

            if ($request->filled('estado')) {
                $query->where('estado', $request->query('estado'));
            }

            if ($request->filled('po')) {
                $query->where('po', 'LIKE', '%' . strtoupper(trim($request->query('po'))) . '%');
            }

            $solicitudes = $query->latest()->get();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success'     => true,
                'db_ready'    => $dbReady,
                'solicitudes' => $solicitudes,
            ]);
        }

        if ($request->query('tipo') === 'modificacion') {
            return view('vochito.modificacion', compact('solicitudes'));
        }

        return view('vochito.cancelacion', compact('solicitudes'));
    }

    /**
     * Muestra el formulario correspondiente para crear una solicitud.
     */
    public function create(Request $request)
    {
        if ($request->query('tipo') === 'modificacion') {
            return $this->modificacionView();
        }

        return $this->cancelacionView();
    }

    /**
     * Registra una nueva solicitud de cancelación o modificación.
     */
    public function store(StoreSolicitudRequest $request)
    {
        $validated = $request->validated();
        $po = strtoupper(trim($validated['po']));

        $pedido = Schema::hasTable('pedidos') ? Pedido::buscarPorPo($po) : null;

        // Regla de negocio: no se aceptan solicitudes si el pedido ya está En tránsito
        if ($pedido && $pedido->estatus === 'En tránsito') {
            $errorMsg = "El pedido {$po} ya se encuentra 'En tránsito', por lo que no es posible procesar la solicitud.";
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }

            return back()->with('error', $errorMsg)->withInput();
        }

        if (Schema::hasTable('solicitudes')) {
            $solicitud = Solicitud::create([
                'pedido_id'   => $pedido?->id,
                'tipo'        => $validated['tipo'],
                'po'          => $po,
                'email'       => $validated['email'],
                'motivo'      => $validated['motivo'] ?? null,
                'comentarios' => $validated['comentarios'],
                'estado'      => 'Pendiente',
            ]);

            $solicitud->load('pedido.producto');
            $mensaje = "Solicitud de {$validated['tipo']} para la orden {$po} registrada exitosamente.";

            if ($request->wantsJson()) {
                return response()->json([
                    'success'   => true,
                    'message'   => $mensaje,
                    'solicitud' => $solicitud,
                ], 201);
            }

            return back()->with('ok', $mensaje);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'db_ready'  => false,
                'message'   => "Solicitud registrada correctamente para la orden {$po} (modo prototipo).",
                'solicitud' => array_merge($validated, [
                    'id'         => time(),
                    'po'         => $po,
                    'estado'     => 'Pendiente',
                    'created_at' => now()->toIso8601String(),
                ]),
            ]);
        }

        return back()->with('ok', "Solicitud registrada correctamente para la orden {$po}.");
    }

    /**
     * Muestra el detalle de una solicitud en formato JSON.
     */
    public function show(string $id)
    {
        $solicitud = Solicitud::with('pedido.producto')->findOrFail($id);

        return response()->json(['success' => true, 'solicitud' => $solicitud]);
    }

    /**
     * Muestra la vista correspondiente para editar/gestionar la solicitud.
     */
    public function edit(string $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        return $solicitud->tipo === 'modificacion'
            ? $this->modificacionView()
            : $this->cancelacionView();
    }

    /**
     * Actualiza el estado de una solicitud (Pendiente, Aprobada, Rechazada).
     */
    public function updateEstado(UpdateSolicitudRequest $request, string $id)
    {
        return $this->update($request, $id);
    }

    /**
     * Actualiza la solicitud en base de datos.
     */
    public function update(UpdateSolicitudRequest $request, string $id)
    {
        $validated = $request->validated();

        if (Schema::hasTable('solicitudes')) {
            $solicitud = Solicitud::findOrFail($id);
            $solicitud->estado = $validated['estado'];
            $solicitud->fecha_resolucion = now();

            if (array_key_exists('respuesta_admin', $validated) && $validated['respuesta_admin'] !== null) {
                $solicitud->respuesta_admin = $validated['respuesta_admin'];
            }
            if (array_key_exists('motivo', $validated) && $validated['motivo'] !== null) {
                $solicitud->motivo = $validated['motivo'];
            }
            if (array_key_exists('comentarios', $validated) && $validated['comentarios'] !== null) {
                $solicitud->comentarios = $validated['comentarios'];
            }
            $solicitud->save();

            // Si se aprueba una cancelación, marcar el pedido asociado como Cancelado
            if ($solicitud->tipo === 'cancelacion' && $solicitud->estado === 'Aprobada' && Schema::hasTable('pedidos')) {
                $pedido = $solicitud->pedido ?? Pedido::buscarPorPo($solicitud->po);
                if ($pedido) {
                    $pedido->estatus = 'Cancelado';
                    $pedido->save();
                }
            }

            $mensaje = "La solicitud #{$solicitud->id} ha sido actualizada a: {$solicitud->estado}.";

            if ($request->wantsJson()) {
                return response()->json([
                    'success'   => true,
                    'message'   => $mensaje,
                    'solicitud' => $solicitud,
                ]);
            }

            return back()->with('ok', $mensaje);
        }

        return response()->json([
            'success' => true,
            'message' => 'Estado de solicitud actualizado.',
        ]);
    }

    /**
     * Elimina una solicitud cuando corresponda.
     */
    public function destroy(Request $request, string $id)
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

            return back()->with('ok', $mensaje);
        }

        return response()->json([
            'success' => true,
            'message' => 'Solicitud eliminada exitosamente.',
        ]);
    }
}
