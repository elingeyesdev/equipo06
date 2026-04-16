@extends('layouts.app')

@section('title', 'Gestión de envíos')

@push('styles')
<style>
    .btn-envio-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        padding: 0.55rem 1.25rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
    }
    .btn-envio-main:hover {
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
    .table-envios thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6c757d;
        border-bottom-width: 1px;
    }
    .table-envios tbody tr { border-bottom: 1px solid rgba(0,0,0,0.04); }
    .envio-code {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 600;
        letter-spacing: 0.02em;
        color: #1a5c38;
    }
</style>
@endpush

@section('content')
    <div class="mb-4 pb-2 border-bottom border-2 border-opacity-10">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h1 class="h3 mb-2 d-flex align-items-center gap-2">
                    <span class="fs-2" aria-hidden="true">🚚</span>
                    S1-ENT_2.1 Gestión de envíos y transporte
                </h1>
                <p class="text-muted mb-0 lead fs-6">
                    Origen, destino y estado del envío. El seguimiento y el detalle de productos se completan en los pasos siguientes del módulo.
                </p>
            </div>
            <a href="{{ route('envios.create') }}" class="btn btn-envio-main d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                Nuevo envío
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary p-3"><i class="bi bi-truck fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Total envíos</div>
                        <div class="h4 mb-0 fw-bold">{{ $total }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-warning-subtle text-warning-emphasis p-3"><i class="bi bi-hourglass-split fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Pendientes</div>
                        <div class="h4 mb-0 fw-bold">{{ $pendientes }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-info-subtle text-info-emphasis p-3"><i class="bi bi-sign-turn-slight-right fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Asignados / en tránsito</div>
                        <div class="h4 mb-0 fw-bold">{{ $enRuta }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
            <i class="bi bi-table text-primary"></i>
            <span class="fw-semibold">Listado de envíos</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-envios mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Código</th>
                            <th>Origen → Destino</th>
                            <th>Estado</th>
                            <th>Programado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($envios as $envio)
                            <tr>
                                <td class="ps-4"><span class="envio-code">{{ $envio->codigo }}</span></td>
                                <td>
                                    <div class="small text-muted text-truncate" style="max-width: 22rem;" title="{{ $envio->origen }} → {{ $envio->destino }}">
                                        {{ $envio->origen }} → {{ $envio->destino }}
                                    </div>
                                </td>
                                <td><span class="badge rounded-pill {{ $envio->badgeEstadoClass() }}">{{ $envio->etiquetaEstado() }}</span></td>
                                <td class="text-muted small">{{ $envio->fecha_programada?->format('d/m/Y') ?? '—' }}</td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('envios.show', $envio) }}" class="btn btn-outline-primary" title="Ver"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('envios.edit', $envio) }}" class="btn btn-outline-secondary" title="Editar"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('envios.destroy', $envio) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Eliminar este envío? Se borrarán detalles y asignaciones vinculados.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    No hay envíos registrados. Crea el primero para comenzar el seguimiento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($envios->hasPages())
            <div class="card-footer bg-white border-top-0 py-3">{{ $envios->links() }}</div>
        @endif
    </div>
@endsection
