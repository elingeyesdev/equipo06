<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use Illuminate\Http\Request;
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

        return view('envios.create', compact('envio', 'estados'));
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
        ], [], [
            'origen' => 'origen',
            'destino' => 'destino',
            'estado' => 'estado',
            'fecha_creacion' => 'fecha de creación',
            'fecha_programada' => 'fecha programada',
            'observaciones' => 'observaciones',
        ]);

        $validated['observaciones'] = $request->filled('observaciones') ? trim($request->input('observaciones')) : null;
        $validated['codigo'] = (string) Str::ulid();

        Envio::create($validated);

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

        return view('envios.edit', compact('envio', 'estados'));
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
        ], [], [
            'origen' => 'origen',
            'destino' => 'destino',
            'estado' => 'estado',
            'fecha_creacion' => 'fecha de creación',
            'fecha_programada' => 'fecha programada',
            'observaciones' => 'observaciones',
        ]);

        $validated['observaciones'] = $request->filled('observaciones') ? trim($request->input('observaciones')) : null;

        $envio->update($validated);

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
}
