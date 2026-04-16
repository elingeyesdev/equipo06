<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EnvioController extends Controller
{
    public function index()
    {
        $envios = Envio::query()
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

        return view('envios.create', compact('envio', 'estados', 'productos', 'cantidadesPrevias'));
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
        ], [], [
            'origen' => 'origen',
            'destino' => 'destino',
            'estado' => 'estado',
            'fecha_creacion' => 'fecha de creación',
            'fecha_programada' => 'fecha programada',
            'observaciones' => 'observaciones',
            'cantidades' => 'cantidades',
        ]);

        $validated['observaciones'] = $request->filled('observaciones') ? trim($request->input('observaciones')) : null;
        $validated['codigo'] = (string) Str::ulid();

        $detalles = $this->detallesValidosDesdeRequest($request, null);

        DB::transaction(function () use ($validated, $detalles) {
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
            'asignaciones.transportista',
            'asignaciones.vehiculo',
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

        return view('envios.edit', compact('envio', 'estados', 'productos', 'cantidadesPrevias'));
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
        ], [], [
            'origen' => 'origen',
            'destino' => 'destino',
            'estado' => 'estado',
            'fecha_creacion' => 'fecha de creación',
            'fecha_programada' => 'fecha programada',
            'observaciones' => 'observaciones',
            'cantidades' => 'cantidades',
        ]);

        $validated['observaciones'] = $request->filled('observaciones') ? trim($request->input('observaciones')) : null;

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
