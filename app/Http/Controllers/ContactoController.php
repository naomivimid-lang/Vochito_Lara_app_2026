<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactoRequest;
use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ContactoController extends Controller
{
    /**
     * Show the contact form.
     */
    public function create()
    {
        return view('vochito.contacto');
    }

    /**
     * Store a newly created contact message.
     */
    public function store(StoreContactoRequest $request)
    {
        $validated = $request->validated();

        if (Schema::hasTable('contactos')) {
            $contacto = Contacto::create($validated);

            $mensaje = "Mensaje enviado exitosamente. Nos pondremos en contacto contigo a la brevedad.";

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $mensaje,
                    'contacto' => $contacto,
                ], 201);
            }

            return redirect()->route('vochito.contacto')->with('success', $mensaje);
        }

        return redirect()->route('vochito.contacto')->with('success', "Mensaje enviado. Te responderemos pronto.");
    }
}
