<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoteController extends Controller
{
    public function index()
    {
        $lotes = Lote::query()
            ->withCount('productos')
            ->orderByDesc('fecha_creacion')
            ->orderByDesc('id')
            ->paginate(12);

        $total = Lote::query()->count();
        $activos = Lote::query()->where('estado', 'activo')->count();

        return view('lotes.index', compact('lotes', 'total', 'activos'));
    }

    public function create()
    {
        $productos = Producto::query()
            ->where('activo', true)
            ->with('productor')
            ->orderBy('nombre')
            ->get();

        $codigoPreview = Lote::previewSiguienteCodigo();

        return view('lotes.create', compact('productos', 'codigoPreview'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'productos' => ['required', 'array', 'min:1'],
            'productos.*' => ['integer', 'distinct', 'exists:productos,id'],
        ], [], [
            'productos' => 'productos',
        ]);

        $productoIds = collect($validated['productos'])
            ->map(fn (int|string $id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $activosCount = Producto::query()
            ->whereIn('id', $productoIds)
            ->where('activo', true)
            ->count();

        if ($activosCount !== count($productoIds)) {
            return back()
                ->withInput()
                ->withErrors(['productos' => 'Solo puedes incluir productos activos en el lote.']);
        }

        $lote = DB::transaction(function () use ($validated, $productoIds) {
            $codigo = Lote::siguienteCodigoLote(lockForUpdate: true);

            $lote = Lote::create([
                'codigo_lote' => $codigo,
                'fecha_creacion' => now()->toDateString(),
                'estado' => 'activo',
            ]);

            $lote->refresh();

            $lote->productos()->attach($productoIds);

            return $lote;
        });

        return redirect()
            ->route('lotes.show', $lote)
            ->with('status', 'Lote '.$lote->codigo_lote.' creado correctamente.');
    }

    public function show(Lote $lote)
    {
        $lote->load(['productos' => function ($q) {
            $q->with('productor')->orderBy('nombre');
        }]);

        return view('lotes.show', compact('lote'));
    }
}
