@extends('layouts.app')

@section('title', 'Lote '.$lote->codigo_lote)

@push('styles')
<style>
    .btn-lote-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        padding: 0.45rem 1.1rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
    }
    .btn-lote-main:hover { background-color: #327183; border-color: #327183; color: #fff; }
    .lote-resumen-card {
        border-radius: 0.85rem;
        border: none;
        background: linear-gradient(145deg, #ffffff 0%, #f4faf7 45%, #eef6f3 100%);
        box-shadow: 0 4px 18px rgba(26, 92, 56, 0.08), 0 0 0 1px rgba(26, 92, 56, 0.06);
        position: relative;
        overflow: hidden;
    }
    .lote-resumen-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 5px;
        background: linear-gradient(180deg, #2d8a6e, #3d8b9e);
        border-radius: 0.85rem 0 0 0.85rem;
    }
    .lote-resumen-line {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.05rem;
        padding: 0.35rem 0;
        border-bottom: 1px dashed rgba(0,0,0,0.06);
    }
    .lote-resumen-line:last-child { border-bottom: none; }
    .lote-code-display {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 700;
        color: #14532d;
    }
    .traz-card {
        border-radius: 0.75rem;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .table-traz thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
    @if (session('status'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('status') }}</div>
    @endif

    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="h3 mb-1 d-flex align-items-center gap-2 flex-wrap">
                <span aria-hidden="true">📦</span>
                <span class="lote-code-display">{{ $lote->codigo_lote }}</span>
                <span class="badge rounded-pill {{ $lote->badgeEstadoClass() }} px-3 py-2">{{ $lote->etiquetaEstado() }}</span>
            </h1>
            <p class="text-muted mb-0 small">{{ $lote->tituloLinea() }}</p>
            @if ($lote->nombre_lote)
                <p class="text-muted mb-0 small">{{ $lote->nombre_lote }}</p>
            @endif
        </div>
        <div class="d-flex flex-wrap gap-2 justify-content-md-end">
            <a href="{{ route('lotes.edit', $lote) }}" class="btn btn-lote-main d-inline-flex align-items-center gap-2">
                <i class="bi bi-pencil-square"></i>Editar
            </a>
            <form action="{{ route('lotes.destroy', $lote) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este lote? Los productos quedarán sin lote.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                    <i class="bi bi-trash me-1"></i>Eliminar
                </button>
            </form>
            <a href="{{ route('lotes.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i>Listado
            </a>
        </div>
    </div>

    <div class="card lote-resumen-card shadow-sm mb-4">
        <div class="card-body p-4 ps-4 ps-md-5">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="rounded-3 bg-success-subtle text-success p-2"><i class="bi bi-clipboard-data fs-5"></i></span>
                <span class="fw-semibold fs-5 text-dark">Resumen del lote</span>
            </div>
            <div class="lote-resumen-line">
                <span class="fs-4" aria-hidden="true">🏷️</span>
                <span><strong>Código:</strong> <span class="lote-code-display fs-6">{{ $lote->codigo_lote }}</span></span>
            </div>
            <div class="lote-resumen-line">
                <span class="fs-4" aria-hidden="true">🧑‍🌾</span>
                <span><strong>Productor:</strong> {{ $lote->productor->full_name ?? '—' }}</span>
            </div>
            <div class="lote-resumen-line">
                <span class="fs-4" aria-hidden="true">🥬</span>
                <span><strong>Producto / tipo:</strong> {{ $lote->etiquetaTipoProducto() }}</span>
            </div>
            <div class="lote-resumen-line">
                <span class="fs-4" aria-hidden="true">📅</span>
                <span><strong>Fecha de cosecha:</strong> {{ $lote->fecha_cosecha?->format('d/m/Y') ?? '—' }}</span>
            </div>
            <div class="lote-resumen-line">
                <span class="fs-4" aria-hidden="true">📊</span>
                <span><strong>Cantidad:</strong> {{ number_format((float) $lote->cantidad, 3, ',', '.') }}</span>
            </div>
            <div class="lote-resumen-line">
                <span class="fs-4" aria-hidden="true">✅</span>
                <span class="d-flex align-items-center gap-2 flex-wrap">
                    <strong>Estado:</strong>
                    <span class="badge rounded-pill {{ $lote->badgeEstadoClass() }} px-3 py-2">{{ $lote->etiquetaEstado() }}</span>
                </span>
            </div>
            <div class="lote-resumen-line">
                <span class="fs-4" aria-hidden="true">📦</span>
                <span><strong>Productos en el lote:</strong> {{ $lote->productos->count() }}</span>
            </div>
            @if ($lote->descripcion)
                <div class="lote-resumen-line align-items-start">
                    <span class="fs-4" aria-hidden="true">📝</span>
                    <span><strong>Descripción:</strong> {{ $lote->descripcion }}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow border-0 traz-card mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-diagram-3 text-primary"></i>
                <span class="fw-semibold">Proceso productivo vinculado</span>
            </div>
            <a href="{{ route('eventos-produccion.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">Ver todos los eventos</a>
        </div>
        <div class="card-body p-0">
            @if ($eventosProduccion->isEmpty())
                <p class="text-muted text-center py-4 px-3 mb-0">
                    No hay eventos de producción registrados para los productos de este lote.
                    <a href="{{ route('eventos-produccion.create') }}" class="fw-semibold">Registrar evento</a>
                </p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle table-traz">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Fecha</th>
                                <th>Etapa</th>
                                <th>Producto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eventosProduccion as $ev)
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $ev->fecha?->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $ev->badgeEtapaClass() }}">{{ $ev->emojiEtapa() }} {{ $ev->etiquetaEtapa() }}</span>
                                    </td>
                                    <td class="small">{{ $ev->etiquetaProductoProductor() }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $ev->badgeEstadoEfectivoClass() }}">{{ $ev->etiquetaEstadoEfectivo() }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow border-0 traz-card mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
            <i class="bi bi-box-seam text-primary"></i>
            <span class="fw-semibold">Productos en el lote</span>
        </div>
        <div class="card-body p-0">
            @if ($lote->productos->isEmpty())
                <p class="text-muted text-center py-4 mb-0">Este lote no tiene productos asociados.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach ($lote->productos as $p)
                        <li class="list-group-item d-flex flex-wrap align-items-center gap-2 py-3">
                            <span class="badge rounded-pill {{ $p->badgeTipoClass() }}">{{ $p->etiquetaTipo() }}</span>
                            <span class="fw-semibold">{{ $p->etiquetaNombreYProductor() }}</span>
                            <a href="{{ route('productos.show', $p) }}" class="btn btn-sm btn-outline-primary rounded-pill ms-auto">Ficha producto</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('lotes.create') }}" class="btn btn-lote-main d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i>Crear otro lote
        </a>
    </div>
@endsection
