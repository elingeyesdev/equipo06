<?php

namespace App\Http\Controllers;

use App\Models\EventoProduccion;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventoProduccionController extends Controller
{
    public function index()
    {
        $eventos = EventoProduccion::query()
            ->with('producto')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(12);

        $total = EventoProduccion::query()->count();
        $completados = EventoProduccion::contarCompletados();
        $enProceso = EventoProduccion::contarEnProcesoEfectivo();
        $pendientes = EventoProduccion::contarPendientesEfectivo();

        $timelineEventos = EventoProduccion::query()
            ->with('producto')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->limit(35)
            ->get()
            ->sortBy(function (EventoProduccion $e) {
                return [$e->fecha->timestamp, $e->id];
            })
            ->values();

        return view('eventos-produccion.index', compact(
            'eventos',
            'total',
            'completados',
            'enProceso',
            'pendientes',
            'timelineEventos'
        ));
    }

    public function create()
    {
        $evento = new EventoProduccion([
            'fecha' => now()->toDateString(),
            'estado' => 'pendiente',
        ]);

        $productos = Producto::query()
            ->where('activo', true)
            ->with('productor')
            ->orderBy('nombre')
            ->get();

        $etapas = EventoProduccion::etapasDisponibles();
        $estados = EventoProduccion::estadosDisponibles();

        return view('eventos-produccion.create', compact('evento', 'productos', 'etapas', 'estados'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedEvento($request);

        EventoProduccion::create($validated);

        return redirect()
            ->route('eventos-produccion.index')
            ->with('status', 'Evento de producción registrado correctamente.');
    }

    public function edit(EventoProduccion $evento_produccion)
    {
        $productos = Producto::query()
            ->where(function ($q) use ($evento_produccion) {
                $q->where('activo', true)
                    ->orWhere('id', $evento_produccion->producto_id);
            })
            ->with('productor')
            ->orderBy('nombre')
            ->get();

        $etapas = EventoProduccion::etapasDisponibles();
        $estados = EventoProduccion::estadosDisponibles();

        return view('eventos-produccion.edit', [
            'evento' => $evento_produccion,
            'productos' => $productos,
            'etapas' => $etapas,
            'estados' => $estados,
        ]);
    }

    public function update(Request $request, EventoProduccion $evento_produccion)
    {
        $validated = $this->validatedEvento($request);

        $evento_produccion->update($validated);

        return redirect()
            ->route('eventos-produccion.index')
            ->with('status', 'Evento actualizado correctamente.');
    }

    public function completar(EventoProduccion $evento_produccion)
    {
        if ($evento_produccion->estado !== 'completado') {
            $evento_produccion->update(['estado' => 'completado']);
        }

        return redirect()
            ->route('eventos-produccion.index')
            ->with('status', 'Evento marcado como completado.');
    }

    public function destroy(EventoProduccion $evento_produccion)
    {
        $evento_produccion->delete();

        return redirect()
            ->route('eventos-produccion.index')
            ->with('status', 'Evento eliminado correctamente.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedEvento(Request $request): array
    {
        $etapasKeys = array_keys(EventoProduccion::etapasDisponibles());
        $estadosKeys = array_keys(EventoProduccion::estadosDisponibles());

        $validated = $request->validate([
            'producto_id' => ['required', 'integer', 'exists:productos,id'],
            'etapa' => ['required', 'string', Rule::in($etapasKeys)],
            'fecha' => ['required', 'date'],
            'inicia_en' => ['nullable', 'date'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'estado' => ['required', 'string', Rule::in($estadosKeys)],
        ], [], [
            'producto_id' => 'producto',
            'etapa' => 'etapa',
            'fecha' => 'fecha',
            'inicia_en' => 'inicio programado',
            'descripcion' => 'descripción',
            'estado' => 'estado',
        ]);

        $validated['descripcion'] = $request->filled('descripcion') ? trim($request->input('descripcion')) : null;
        $validated['inicia_en'] = $request->filled('inicia_en') ? $request->input('inicia_en') : null;

        return $validated;
    }
}
