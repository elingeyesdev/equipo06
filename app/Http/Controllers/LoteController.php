<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Producer;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LoteController extends Controller
{
    public function index()
    {
        $lotes = Lote::query()
            ->with('productor')
            ->withCount('productos')
            ->orderByDesc('fecha_cosecha')
            ->orderByDesc('id')
            ->paginate(12);

        $total = Lote::query()->count();
        $activos = Lote::query()->where('estado', 'activo')->count();

        return view('lotes.index', compact('lotes', 'total', 'activos'));
    }

    public function create()
    {
        $productores = Producer::query()
            ->where('is_active', true)
            ->orderBy('full_name')
            ->get();

        $productos = Producto::query()
            ->sinLote()
            ->where('activo', true)
            ->with('productor')
            ->orderBy('nombre')
            ->get();

        $tiposProducto = Producto::tiposDisponibles();
        $codigoPreview = Lote::previewSiguienteCodigo();

        return view('lotes.create', compact('productores', 'productos', 'tiposProducto', 'codigoPreview'));
    }

    public function store(Request $request)
    {
        $tiposKeys = array_keys(Producto::tiposDisponibles());
        $estadosKeys = array_keys(Lote::estadosDisponibles());

        $validated = $request->validate([
            'productor_id' => ['required', 'integer', 'exists:producers,id'],
            'fecha_cosecha' => ['required', 'date'],
            'nombre_lote' => ['nullable', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
            'cantidad' => ['required', 'numeric', 'min:0.001'],
            'tipo_producto' => ['required', 'string', Rule::in($tiposKeys)],
            'estado' => ['required', 'string', Rule::in($estadosKeys)],
            'productos' => ['required', 'array', 'min:1'],
            'productos.*' => ['integer', 'distinct', 'exists:productos,id'],
        ], [], [
            'productor_id' => 'productor',
            'fecha_cosecha' => 'fecha de cosecha',
            'nombre_lote' => 'nombre del lote',
            'descripcion' => 'descripción',
            'cantidad' => 'cantidad',
            'tipo_producto' => 'tipo de producto',
            'estado' => 'estado',
            'productos' => 'productos',
        ]);

        $validated['nombre_lote'] = $request->filled('nombre_lote') ? trim($request->input('nombre_lote')) : null;
        $validated['descripcion'] = $request->filled('descripcion') ? trim($request->input('descripcion')) : null;

        $productoIds = collect($validated['productos'])
            ->map(fn (int|string $id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $error = $this->validarProductosParaLote(
            $productoIds,
            (int) $validated['productor_id'],
            $validated['tipo_producto'],
            null
        );

        if ($error !== null) {
            return back()->withInput()->withErrors(['productos' => $error]);
        }

        $lote = DB::transaction(function () use ($validated, $productoIds) {
            $codigo = Lote::siguienteCodigoLote(lockForUpdate: true);

            $lote = Lote::create([
                'codigo_lote' => $codigo,
                'nombre_lote' => $validated['nombre_lote'],
                'fecha_cosecha' => $validated['fecha_cosecha'],
                'productor_id' => $validated['productor_id'],
                'descripcion' => $validated['descripcion'],
                'cantidad' => $validated['cantidad'],
                'tipo_producto' => $validated['tipo_producto'],
                'estado' => $validated['estado'],
            ]);

            Producto::query()
                ->whereIn('id', $productoIds)
                ->update(['lote_id' => $lote->id]);

            return $lote;
        });

        return redirect()
            ->route('lotes.show', $lote)
            ->with('status', 'Lote '.$lote->codigo_lote.' creado correctamente.');
    }

    public function show(Lote $lote)
    {
        $lote->load([
            'productor',
            'productos' => fn ($q) => $q->orderBy('nombre'),
        ]);

        return view('lotes.show', compact('lote'));
    }

    /**
     * @param  array<int, int>  $productoIds
     */
    private function validarProductosParaLote(
        array $productoIds,
        int $productorId,
        string $tipoProducto,
        ?int $loteIdPermitido
    ): ?string {
        $ids = collect($productoIds)->map(fn ($id) => (int) $id)->unique()->values()->all();
        if ($ids === []) {
            return 'Debe seleccionar al menos un producto.';
        }

        $productos = Producto::query()->whereIn('id', $ids)->get();
        if ($productos->count() !== count($ids)) {
            return 'No se encontraron todos los productos seleccionados.';
        }

        foreach ($productos as $p) {
            if ((int) $p->productor_id !== $productorId) {
                return 'Todos los productos deben pertenecer al mismo productor del lote.';
            }
            if ($p->lote_id !== null && ($loteIdPermitido === null || (int) $p->lote_id !== (int) $loteIdPermitido)) {
                return 'Uno o más productos ya están asignados a otro lote.';
            }
            if (! $p->activo) {
                return 'Solo se pueden incluir productos activos.';
            }
            if ($p->tipo !== $tipoProducto) {
                return 'El tipo de cada producto debe coincidir con el tipo de producto del lote.';
            }
        }

        return null;
    }
}
