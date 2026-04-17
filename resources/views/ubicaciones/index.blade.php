@extends('layouts.app')

@section('title', 'Gestión de transporte por ubicación')

@push('styles')
<style>
    .btn-ubicacion-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        padding: 0.55rem 1.25rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
    }
    .btn-ubicacion-main:hover {
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
    .table-ubicaciones thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6c757d;
        border-bottom-width: 1px;
    }
    .table-ubicaciones tbody tr { border-bottom: 1px solid rgba(0,0,0,0.04); }
    .mono-coord {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
    <div class="mb-4 pb-2 border-bottom border-2 border-opacity-10">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h1 class="h3 mb-2 d-flex align-items-center gap-2">
                    <span class="fs-2" aria-hidden="true">📍</span>
                    ENT_2.2 Gestión de transporte por ubicación
                </h1>
                <p class="text-muted mb-0 lead fs-6">
                    Registro y administración de ubicaciones, rutas y puntos de control del transporte
                </p>
            </div>
            <a href="{{ route('ubicaciones.create') }}" class="btn btn-ubicacion-main d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                Registrar ubicación
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary p-3"><i class="bi bi-geo-alt fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Total ubicaciones</div>
                        <div class="h4 mb-0 fw-bold">{{ $total }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success-subtle text-success p-3"><i class="bi bi-crosshair fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Con coordenadas</div>
                        <div class="h4 mb-0 fw-bold">{{ $conCoordenadas }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
            <i class="bi bi-table text-primary"></i>
            <span class="fw-semibold">Ubicaciones registradas</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-ubicaciones mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Nombre de ubicación</th>
                            <th>Tipo</th>
                            <th>Dirección</th>
                            <th>Latitud</th>
                            <th>Longitud</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ubicaciones as $ubicacion)
                            <tr>
                                <td class="ps-4 text-muted small font-monospace">{{ $ubicacion->id }}</td>
                                <td class="fw-semibold">{{ $ubicacion->nombre_ubicacion }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $ubicacion->badgeTipoClass() }} px-3 py-2">{{ $ubicacion->etiquetaTipo() }}</span>
                                </td>
                                <td class="small text-muted">{{ $ubicacion->direccion ?: '—' }}</td>
                                <td class="mono-coord text-muted">{{ $ubicacion->latitud !== null ? $ubicacion->latitud : '—' }}</td>
                                <td class="mono-coord text-muted">{{ $ubicacion->longitud !== null ? $ubicacion->longitud : '—' }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('ubicaciones.show', $ubicacion) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2">Ver</a>
                                    <a href="{{ route('ubicaciones.edit', $ubicacion) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2">Editar</a>
                                    <form action="{{ route('ubicaciones.destroy', $ubicacion) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2"
                                                onclick="return confirm('¿Eliminar esta ubicación?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted mb-2 fs-1" aria-hidden="true">📍</div>
                                    <p class="mb-1 fw-semibold text-secondary">Aún no hay ubicaciones registradas</p>
                                    <p class="small text-muted mb-3">Crea la primera para origen, destino o puntos de control del transporte.</p>
                                    <a href="{{ route('ubicaciones.create') }}" class="btn btn-ubicacion-main btn-sm">Registrar ubicación</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $ubicaciones->links() }}
    </div>
@endsection
