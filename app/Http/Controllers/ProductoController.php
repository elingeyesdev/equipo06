<?php

namespace App\Http\Controllers;

use App\Models\Producer;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::query()
            ->with('productor')
            ->latest()
            ->paginate(10);

        $total = Producto::query()->count();
        $activos = Producto::query()->where('activo', true)->count();

        return view('productos.index', compact('productos', 'total', 'activos'));
    }

    public function create()
    {
        $productores = Producer::query()
            ->where('is_active', true)
            ->orderBy('full_name')
            ->get();

        $tipos = Producto::tiposDisponibles();
        $producto = new Producto;

        return view('productos.create', compact('productores', 'tipos', 'producto'));
    }

    public function store(Request $request)
    {
        $tiposKeys = array_keys(Producto::tiposDisponibles());

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:2', 'max:150'],
            'tipo' => ['required', 'string', Rule::in($tiposKeys)],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'productor_id' => ['required', 'integer', 'exists:producers,id'],
            'activo' => ['nullable', 'boolean'],
        ], [], [
            'nombre' => 'nombre',
            'tipo' => 'tipo',
            'descripcion' => 'descripción',
            'productor_id' => 'productor',
            'activo' => 'estado',
        ]);

        $validated['activo'] = $request->boolean('activo');
        $validated['descripcion'] = $request->filled('descripcion') ? trim($request->input('descripcion')) : null;

        Producto::create($validated);

        return redirect()
            ->route('productos.index')
            ->with('status', 'Producto agrícola registrado correctamente.');
    }

    public function show(Producto $producto)
    {
        $producto->load('productor');

        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $productores = Producer::query()
            ->where(function ($q) use ($producto) {
                $q->where('is_active', true)
                    ->orWhere('id', $producto->productor_id);
            })
            ->orderBy('full_name')
            ->get();

        $tipos = Producto::tiposDisponibles();

        return view('productos.edit', compact('producto', 'productores', 'tipos'));
    }

    public function update(Request $request, Producto $producto)
    {
        $tiposKeys = array_keys(Producto::tiposDisponibles());

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:2', 'max:150'],
            'tipo' => ['required', 'string', Rule::in($tiposKeys)],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'productor_id' => ['required', 'integer', 'exists:producers,id'],
            'activo' => ['nullable', 'boolean'],
        ], [], [
            'nombre' => 'nombre',
            'tipo' => 'tipo',
            'descripcion' => 'descripción',
            'productor_id' => 'productor',
            'activo' => 'estado',
        ]);

        $validated['activo'] = $request->boolean('activo');
        $validated['descripcion'] = $request->filled('descripcion') ? trim($request->input('descripcion')) : null;

        $producto->update($validated);

        return redirect()
            ->route('productos.index')
            ->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('status', 'Producto eliminado correctamente.');
    }
}
