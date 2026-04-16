@extends('layouts.app')

@section('title', 'Gestión de empaquetación por lote')

@push('styles')
<style>
    .btn-lote-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        padding: 0.55rem 1.25rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
    }
    .btn-lote-main:hover {
        background-color: #327183;
        border-color: #327183;
        color: #fff;
    }
    .dash-stat {
        border-radius: 0.75rem;
        border: none;
        transition: transform 0.15s ease;
    }
    .dash-stat:hover { transform: translateY(-2px); }
    .table-lotes thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6c757d;
        border-bottom-width: 1px;
    }
    .table-lotes tbody tr { border-bottom: 1px solid rgba(0,0,0,0.04); }
    .lote-code {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 600;
        letter-spacing: 0.02em;
        color: #1a5c38;
    }
    .lote-titulo-h {
        font-size: 0.8rem;
        color: #6c757d;
        max-width: 22rem;
    }
</style>
@endpush

@section('content')
    <div class="mb-4 pb-2 border-bottom border-2 border-opacity-10">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h1 class="h3 mb-2 d-flex align-items-center gap-2">
                    <span class="fs-2" aria-hidden="true">📦</span>
                    ENT_1.4 Gestión de empaquetación por lote
                </h1>
                <p class="text-muted mb-0 lead fs-6">
                    Lotes coherentes: mismo productor, misma fecha de cosecha, mismo tipo de producto y productos vinculados para trazabilidad.
                </p>
            </div>
            <a href="{{ route('lotes.create') }}" class="btn btn-lote-main d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                Crear lote
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('status') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary p-3"><i class="bi bi-collection fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Lotes en trazabilidad</div>
                        <div class="h4 mb-0 fw-bold">{{ $total }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success-subtle text-success p-3"><i class="bi bi-unlock fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Activos</div>
                        <div class="h4 mb-0 fw-bold">{{ $activos }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-secondary-subtle text-secondary p-3"><i class="bi bi-archive fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Cerrados</div>
                        <div class="h4 mb-0 fw-bold">{{ $cerrados }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
            <i class="bi bi-table text-primary"></i>
            <span class="fw-semibold">Listado de lotes</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-lotes mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Código</th>
                            <th>Productor</th>
                            <th>Tipo</th>
                            <th>Cosecha</th>
                            <th class="text-end">Cantidad</th>
                            <th>Productos</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lotes as $lote)
                            <tr>
                                <td class="ps-4">
                                    <span class="lote-code">{{ $lote->codigo_lote }}</span>
                                    @if ($lote->nombre_lote)
                                        <div class="small text-muted">{{ $lote->nombre_lote }}</div>
                                    @endif
                                    <div class="lote-titulo-h text-truncate mt-1" title="{{ $lote->tituloLinea() }}">{{ $lote->tituloLinea() }}</div>
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $lote->productor->full_name ?? '—' }}</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border px-2 py-2">{{ $lote->etiquetaTipoProducto() }}</span>
                                </td>
                                <td>
                                    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ $lote->fecha_cosecha?->format('d/m/Y') ?? '—' }}</span>
                                </td>
                                <td class="text-end small font-monospace">{{ number_format((float) $lote->cantidad, 3, ',', '.') }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                                        <i class="bi bi-box-seam me-1"></i>{{ $lote->productos_count }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ $lote->badgeEstadoClass() }} px-3 py-2">{{ $lote->etiquetaEstado() }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('lotes.show', $lote) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2">Ver</a>
                                    <a href="{{ route('lotes.edit', $lote) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2">Editar</a>
                                    <form action="{{ route('lotes.destroy', $lote) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2"
                                                onclick="return confirm('¿Eliminar este lote? Los productos quedarán sin lote.')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted mb-2 fs-1" aria-hidden="true">📦</div>
                                    <p class="mb-1 fw-semibold text-secondary">No hay lotes listos para mostrar</p>
                                    <p class="small text-muted mb-3">Se muestran solo lotes con productor, al menos un producto y datos coherentes.</p>
                                    <a href="{{ route('lotes.create') }}" class="btn btn-lote-main btn-sm">Crear lote</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $lotes->links() }}
    </div>
@endsection
