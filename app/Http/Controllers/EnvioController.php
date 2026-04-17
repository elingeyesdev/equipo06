<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Producto;
use App\Models\Transportista;
use App\Models\Ubicacion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EnvioController extends Controller
{
    public function index()
    {
        $envios = Envio::query()
            ->with(['ubicacionActual', 'transportista'])
            ->orderByDesc('fecha_programada')
            ->orderByDesc('fecha_creacion')
            ->orderByDesc('id')
            ->paginate(12);

        $total = Envio::query()->count();
        $pendientes = Envio::query()->where('estado', 'pendiente')->count();
        $enRuta = Envio::query()->whereIn('estado', ['asignado', 'en_transito'])->count();

        return view('envios.index', compact('envios', 'total', 'pendientes', 'enRuta'));
    }

    public function create()
    {
        $envio = new Envio([
            'fecha_creacion' => now()->toDateString(),
            'estado' => 'pendiente',
        ]);
        $estados = Envio::estadosDisponibles();
        $productos = $this->productosParaFormulario($envio);
        $cantidadesPrevias = [];
        $codigoPreview = Envio::previewSiguienteCodigoGuia();
        $ubicaciones = $this->ubicacionesParaFormulario();
        $transportistas = $this->transportistasParaFormulario($envio);

        return view('envios.create', compact('envio', 'estados', 'productos', 'cantidadesPrevias', 'codigoPreview', 'ubicaciones', 'transportistas'));
    }

    public function store(Request $request)
    {
        $estadosKeys = array_keys(Envio::estadosDisponibles());

        $validated = $request->validate([
            'origen' => ['required', 'string', 'max:255'],
            'destino' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'string', Rule::in($estadosKeys)],
            'fecha_creacion' => ['required', 'date'],
            'fecha_programada' => ['nullable', 'date', 'after_or_equal:fecha_creacion'],
            'observaciones' => ['nullable', 'string', 'max:5000'],
            'cantidades' => ['nullable', 'array'],
            'cantidades.*' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'ubicacion_actual_id' => ['nullable', 'integer', 'exists:ubicaciones,id'],
            'transportista_id' => ['nullable', 'integer', 'exists:transportistas,id'],
        ], [], [
            'origen' => 'origen',
            'destino' => 'destino',
            'estado' => 'estado',
            'fecha_creacion' => 'fecha de creación',
            'fecha_programada' => 'fecha programada',
            'observaciones' => 'observaciones',
            'cantidades' => 'cantidades',
            'ubicacion_actual_id' => 'ubicación actual',
            'transportista_id' => 'responsable de transporte',
        ]);

        $validated['observaciones'] = $request->filled('observaciones') ? trim($request->input('observaciones')) : null;
        $validated['ubicacion_actual_id'] = $request->filled('ubicacion_actual_id') ? (int) $request->input('ubicacion_actual_id') : null;
        $validated['transportista_id'] = $request->filled('transportista_id') ? (int) $request->input('transportista_id') : null;

        $this->aplicarEstadoSegunUbicacionActual($validated);

        $detalles = $this->detallesValidosDesdeRequest($request, null);

        DB::transaction(function () use ($validated, $detalles) {
            $validated['codigo'] = Envio::siguienteCodigoGuia(lockForUpdate: true);
            $envio = Envio::create($validated);
            $this->persistDetalles($envio, $detalles);
        });

        return redirect()
            ->route('envios.index')
            ->with('status', 'Envío registrado correctamente.');
    }

    public function show(Envio $envio)
    {
        $envio->load([
            'detalles.producto.productor',
            'transportista',
            'asignaciones.transportista',
            'asignaciones.vehiculo',
            'ubicacionActual',
        ]);

        return view('envios.show', compact('envio'));
    }

    public function edit(Envio $envio)
    {
        $estados = Envio::estadosDisponibles();
        $productos = $this->productosParaFormulario($envio);
        $cantidadesPrevias = $envio->detalles()
            ->get()
            ->mapWithKeys(fn ($d) => [$d->producto_id => $d->cantidad])
            ->all();
        $ubicaciones = $this->ubicacionesParaFormulario();
        $transportistas = $this->transportistasParaFormulario($envio);

        return view('envios.edit', compact('envio', 'estados', 'productos', 'cantidadesPrevias', 'ubicaciones', 'transportistas'));
    }

    public function update(Request $request, Envio $envio)
    {
        $estadosKeys = array_keys(Envio::estadosDisponibles());

        $validated = $request->validate([
            'origen' => ['required', 'string', 'max:255'],
            'destino' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'string', Rule::in($estadosKeys)],
            'fecha_creacion' => ['required', 'date'],
            'fecha_programada' => ['nullable', 'date', 'after_or_equal:fecha_creacion'],
            'observaciones' => ['nullable', 'string', 'max:5000'],
            'cantidades' => ['nullable', 'array'],
            'cantidades.*' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'ubicacion_actual_id' => ['nullable', 'integer', 'exists:ubicaciones,id'],
            'transportista_id' => ['nullable', 'integer', 'exists:transportistas,id'],
        ], [], [
            'origen' => 'origen',
            'destino' => 'destino',
            'estado' => 'estado',
            'fecha_creacion' => 'fecha de creación',
            'fecha_programada' => 'fecha programada',
            'observaciones' => 'observaciones',
            'cantidades' => 'cantidades',
            'ubicacion_actual_id' => 'ubicación actual',
            'transportista_id' => 'responsable de transporte',
        ]);

        $validated['observaciones'] = $request->filled('observaciones') ? trim($request->input('observaciones')) : null;
        $validated['ubicacion_actual_id'] = $request->filled('ubicacion_actual_id') ? (int) $request->input('ubicacion_actual_id') : null;
        $validated['transportista_id'] = $request->filled('transportista_id') ? (int) $request->input('transportista_id') : null;

        $this->aplicarEstadoSegunUbicacionActual($validated);

        $detalles = $this->detallesValidosDesdeRequest($request, $envio);

        DB::transaction(function () use ($envio, $validated, $detalles) {
            $envio->update($validated);
            $this->persistDetalles($envio, $detalles);
        });

        return redirect()
            ->route('envios.index')
            ->with('status', 'Envío actualizado correctamente.');
    }

    public function destroy(Envio $envio)
    {
        $codigo = $envio->codigo;
        $envio->delete();

        return redirect()
            ->route('envios.index')
            ->with('status', 'Envío '.$codigo.' eliminado correctamente.');
    }

    /**
     * @return Collection<int, Ubicacion>
     */
    private function ubicacionesParaFormulario()
    {
        return Ubicacion::query()
            ->orderBy('nombre_ubicacion')
            ->get();
    }

    /**
     * @return Collection<int, Transportista>
     */
    private function transportistasParaFormulario(Envio $envio)
    {
        return Transportista::query()
            ->where(function ($q) use ($envio) {
                $q->where('estado', 'activo');
                if ($envio->exists && $envio->transportista_id) {
                    $q->orWhere('id', $envio->transportista_id);
                }
            })
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();
    }

    /**
     * Ajuste MVP del estado según el tipo de la ubicación actual (IDs / tipo en BD, sin GPS).
     *
     * @param  array<string, mixed>  $validated
     */
    private function aplicarEstadoSegunUbicacionActual(array &$validated): void
    {
        if (($validated['estado'] ?? '') === 'cancelado') {
            return;
        }
        $uid = $validated['ubicacion_actual_id'] ?? null;
        if (! $uid) {
            return;
        }
        $ubicacion = Ubicacion::query()->find($uid);
        if (! $ubicacion) {
            return;
        }
        if ($ubicacion->tipo === 'destino') {
            $validated['estado'] = 'entregado';

            return;
        }
        if ($ubicacion->tipo === 'punto_control' && in_array($validated['estado'] ?? '', ['pendiente', 'asignado'], true)) {
            $validated['estado'] = 'en_transito';
        }
    }

    /**
     * @return Collection<int, Producto>
     */
    private function productosParaFormulario(Envio $envio)
    {
        return Producto::query()
            ->where(function ($q) use ($envio) {
                $q->where('activo', true);
                if ($envio->exists) {
                    $ids = $envio->detalles()->pluck('producto_id')->all();
                    if ($ids !== []) {
                        $q->orWhereIn('id', $ids);
                    }
                }
            })
            ->with('productor')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * @return list<array{producto_id: int, cantidad: float}>
     */
    private function detallesValidosDesdeRequest(Request $request, ?Envio $envio): array
    {
        $raw = $request->input('cantidades', []);
        if (! is_array($raw)) {
            return [];
        }

        $allowed = Producto::query()
            ->where(function ($q) use ($envio) {
                $q->where('activo', true);
                if ($envio?->exists) {
                    $ids = $envio->detalles()->pluck('producto_id')->all();
                    if ($ids !== []) {
                        $q->orWhereIn('id', $ids);
                    }
                }
            })
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $detalles = [];
        foreach ($raw as $key => $value) {
            $pid = (int) $key;
            if (! in_array($pid, $allowed, true)) {
                continue;
            }
            $qty = is_numeric($value) ? round((float) $value, 3) : 0.0;
            if ($qty > 0) {
                $detalles[] = ['producto_id' => $pid, 'cantidad' => $qty];
            }
        }

        return $detalles;
    }

    /**
     * @param  list<array{producto_id: int, cantidad: float}>  $detalles
     */
    private function persistDetalles(Envio $envio, array $detalles): void
    {
        $envio->detalles()->delete();
        foreach ($detalles as $row) {
            $envio->detalles()->create($row);
        }
    }
}
