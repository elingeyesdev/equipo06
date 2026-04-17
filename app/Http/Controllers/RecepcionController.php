<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use Illuminate\Http\Request;

class RecepcionController extends Controller
{
    /**
     * Registro mínimo de conformidad de recibido (ENT 2.4 - Tarea 3).
     */
    public function guardarConformidad(Request $request, Envio $envio)
    {
        $validated = $request->validate([
            'conforme' => ['required', 'boolean'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ], [], [
            'conforme' => 'conformidad',
            'observaciones' => 'observaciones',
        ]);

        $conforme = (bool) $validated['conforme'];

        $envio->recepcion()->updateOrCreate(
            ['envio_id' => $envio->id],
            [
                'fecha_llegada' => $envio->recepcion?->fecha_llegada ?? now(),
                'estado_entrega' => $conforme ? 'recibido' : 'observado',
                'conforme' => $conforme,
                'observaciones' => $request->filled('observaciones') ? trim((string) $request->input('observaciones')) : null,
            ]
        );

        return redirect()
            ->route('envios.show', $envio)
            ->with('status', 'Conformidad de recepción registrada correctamente.');
    }
}
