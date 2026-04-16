@extends('layouts.app')

@section('title', 'Gestión de Productores')

@push('styles')
<style>
    .btn-producer-main {
        background-color: #2d8a6e;
        border-color: #2d8a6e;
        color: #fff;
        font-weight: 600;
        padding: 0.55rem 1.25rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(45, 138, 110, 0.35);
    }
    .btn-producer-main:hover {
        background-color: #247058;
        border-color: #247058;
        color: #fff;
    }
    .dash-stat {
        border-radius: 0.75rem;
        border: none;
        transition: transform 0.15s ease;
    }
    .dash-stat:hover {
        transform: translateY(-2px);
    }
    .table-producers thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6c757d;
        border-bottom-width: 1px;
    }
    .table-producers tbody tr {
        border-bottom: 1px solid rgba(0,0,0,0.04);
    }
</style>
@endpush

@section('content')
    <div class="mb-4 pb-2 border-bottom border-2 border-opacity-10">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h1 class="h3 mb-2 d-flex align-items-center gap-2">
                    <span class="fs-2" aria-hidden="true">🧑‍🌾</span>
                    ENT_1.1 Gestión de Productores
                </h1>
                <p class="text-muted mb-0 lead fs-6">
                    Aquí vive el directorio de quienes producen. Datos de contacto, estado y acciones rápidas en un solo vistazo.
                </p>
            </div>
            <a href="{{ route('productores.create') }}" class="btn btn-producer-main d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                Registrar productor
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success-subtle text-success p-3"><i class="bi bi-people fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Total productores</div>
                        <div class="h4 mb-0 fw-bold">{{ $total }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary p-3"><i class="bi bi-check-circle fs-4"></i></div>
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
                    <div class="rounded-3 bg-secondary-subtle text-secondary p-3"><i class="bi bi-pause-circle fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Inactivos</div>
                        <div class="h4 mb-0 fw-bold">{{ max(0, $total - $activos) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
            <i class="bi bi-table text-success"></i>
            <span class="fw-semibold">Directorio de productores</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-producers mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nombre</th>
                            <th>Carnet de identidad</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($producers as $producer)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $producer->full_name }}</td>
                                <td>
                                    @if($producer->document_number)
                                        <span class="font-monospace small">{{ $producer->document_number }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($producer->phone)
                                        <span class="d-inline-flex align-items-center gap-1"><i class="bi bi-telephone text-muted small"></i>{{ $producer->phone }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($producer->email)
                                        <span class="d-inline-flex align-items-center gap-1 text-break"><i class="bi bi-envelope text-muted small"></i>{{ $producer->email }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ $producer->is_active ? 'text-bg-success' : 'text-bg-secondary' }} px-3 py-2">
                                        {{ $producer->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('productores.show', $producer) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Ver</a>
                                    <a href="{{ route('productores.edit', $producer) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Editar</a>
                                    <form action="{{ route('productores.destroy', $producer) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('¿Seguro que deseas eliminar este productor?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted mb-2 fs-1" aria-hidden="true">👥</div>
                                    <p class="mb-1 fw-semibold text-secondary">Aún no hay productores registrados</p>
                                    <p class="small text-muted mb-3">Empieza por dar de alta al primer productor; luego podrás asignarle productos agrícolas.</p>
                                    <a href="{{ route('productores.create') }}" class="btn btn-producer-main btn-sm">Registrar el primero</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $producers->links() }}
    </div>
@endsection
