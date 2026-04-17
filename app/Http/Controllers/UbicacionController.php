<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UbicacionController extends Controller
{
    public function index()
    {
        $ubicaciones = Ubicacion::query()
            ->orderByDesc('id')
            ->paginate(12);

        $total = Ubicacion::query()->count();
        $conCoordenadas = Ubicacion::query()
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->count();

        return view('ubicaciones.index', compact('ubicaciones', 'total', 'conCoordenadas'));
    }

    public function create()
    {
        $tipos = Ubicacion::tiposPuntoDisponibles();
        $ubicacion = new Ubicacion;

        return view('ubicaciones.create', compact('tipos', 'ubicacion'));
    }

    public function store(Request $request)
    {
        $tiposKeys = array_keys(Ubicacion::tiposPuntoDisponibles());

        $validated = $request->validate([
            'nombre_ubicacion' => ['required', 'string', 'max:160'],
            'tipo' => ['required', 'string', Rule::in($tiposKeys)],
            'direccion' => ['nullable', 'string', 'max:255'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
        ], [], [
            'nombre_ubicacion' => 'nombre de ubicación',
            'tipo' => 'tipo',
            'direccion' => 'dirección',
            'latitud' => 'latitud',
            'longitud' => 'longitud',
            'descripcion' => 'descripción',
        ]);

        $validated['direccion'] = $request->filled('direccion') ? trim((string) $request->input('direccion')) : null;
        $validated['descripcion'] = $request->filled('descripcion') ? trim((string) $request->input('descripcion')) : null;
        $validated['latitud'] = $request->filled('latitud') ? $validated['latitud'] : null;
        $validated['longitud'] = $request->filled('longitud') ? $validated['longitud'] : null;

        Ubicacion::create($validated);

        return redirect()
            ->route('ubicaciones.index')
            ->with('status', 'Ubicación registrada correctamente.');
    }

    public function show(Ubicacion $ubicacion)
    {
        $ubicacion->load(['envio', 'ruta']);

        return view('ubicaciones.show', compact('ubicacion'));
    }

    public function edit(Ubicacion $ubicacion)
    {
        $tipos = Ubicacion::tiposPuntoDisponibles();

        return view('ubicaciones.edit', compact('ubicacion', 'tipos'));
    }

    public function update(Request $request, Ubicacion $ubicacion)
    {
        $tiposKeys = array_keys(Ubicacion::tiposPuntoDisponibles());

        $validated = $request->validate([
            'nombre_ubicacion' => ['required', 'string', 'max:160'],
            'tipo' => ['required', 'string', Rule::in($tiposKeys)],
            'direccion' => ['nullable', 'string', 'max:255'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
        ], [], [
            'nombre_ubicacion' => 'nombre de ubicación',
            'tipo' => 'tipo',
            'direccion' => 'dirección',
            'latitud' => 'latitud',
            'longitud' => 'longitud',
            'descripcion' => 'descripción',
        ]);

        $validated['direccion'] = $request->filled('direccion') ? trim((string) $request->input('direccion')) : null;
        $validated['descripcion'] = $request->filled('descripcion') ? trim((string) $request->input('descripcion')) : null;
        $validated['latitud'] = $request->filled('latitud') ? $validated['latitud'] : null;
        $validated['longitud'] = $request->filled('longitud') ? $validated['longitud'] : null;

        $ubicacion->update($validated);

        return redirect()
            ->route('ubicaciones.index')
            ->with('status', 'Ubicación actualizada correctamente.');
    }

    public function destroy(Ubicacion $ubicacion)
    {
        $nombre = $ubicacion->nombre_ubicacion;
        $ubicacion->delete();

        return redirect()
            ->route('ubicaciones.index')
            ->with('status', 'Ubicación «'.$nombre.'» eliminada correctamente.');
    }
}
