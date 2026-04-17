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
            'cantidad_recibida' => ['required', 'numeric', 'min:0', 'max:999999'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ], [], [
            'conforme' => 'conformidad',
            'cantidad_recibida' => 'cantidad recibida',
            'observaciones' => 'observaciones',
        ]);

        $conforme = (bool) $validated['conforme'];
        $cantidadRecibida = round((float) $validated['cantidad_recibida'], 3);
        $cantidadEnviada = round((float) $envio->detalles()->sum('cantidad'), 3);

        $resultado = match (true) {
            $cantidadRecibida === $cantidadEnviada => 'completa',
            $cantidadRecibida < $cantidadEnviada => 'parcial',
            default => 'exceso',
        };

        $envio->recepcion()->updateOrCreate(
            ['envio_id' => $envio->id],
            [
                'fecha_llegada' => $envio->recepcion?->fecha_llegada ?? now(),
                'estado_entrega' => $conforme ? 'recibido' : 'observado',
                'conforme' => $conforme,
                'cantidad_recibida' => $cantidadRecibida,
                'resultado_validacion' => $resultado,
                'observaciones' => $request->filled('observaciones') ? trim((string) $request->input('observaciones')) : null,
            ]
        );

        // Cierre lógico MVP: solo marcar entregado cuando la recepción es conforme y completa.
        if ($conforme && $resultado === 'completa' && $envio->estado !== 'entregado') {
            $envio->update(['estado' => 'entregado']);
        }

        return redirect()
            ->route('envios.show', $envio)
            ->with('status', 'Conformidad y validación de cantidad registradas correctamente.');
    }
}
