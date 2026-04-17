@extends('layouts.app')

@section('title', 'Gestión de responsables de transporte')

@push('styles')
<style>
    .btn-transport-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        padding: 0.55rem 1.25rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
    }
    .btn-transport-main:hover {
        background-color: #327183;
        border-color: #327183;
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
    .table-transportistas thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6c757d;
        border-bottom-width: 1px;
    }
    .table-transportistas tbody tr {
        border-bottom: 1px solid rgba(0,0,0,0.04);
    }
</style>
@endpush

@section('content')
    <div class="mb-4 pb-2 border-bottom border-2 border-opacity-10">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h1 class="h3 mb-2 d-flex align-items-center gap-2">
                    <span class="fs-2" aria-hidden="true">🚚</span>
                    ENT_2.3 Gestión de responsable de transporte
                </h1>
                <p class="text-muted mb-0 lead fs-6">
                    Registro y administración de responsables de transporte del sistema
                </p>
            </div>
            <a href="{{ route('transportistas.create') }}" class="btn btn-transport-main d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                + Registrar responsable
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary p-3"><i class="bi bi-people fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Total responsables</div>
                        <div class="h4 mb-0 fw-bold">{{ $total }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success-subtle text-success p-3"><i class="bi bi-check-circle fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Disponibles</div>
                        <div class="h4 mb-0 fw-bold">{{ $disponibles }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-4">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-secondary-subtle text-secondary p-3"><i class="bi bi-pie-chart fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">No disponibles</div>
                        <div class="h4 mb-0 fw-bold">{{ max(0, $total - $disponibles) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
            <i class="bi bi-table text-primary"></i>
            <span class="fw-semibold">Listado de responsables</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-transportistas mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Nombre completo</th>
                            <th>Carnet de Identidad</th>
                            <th>Teléfono</th>
                            <th>Licencia</th>
                            <th>Tipo licencia</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transportistas as $transportista)
                            <tr>
                                <td class="ps-4 font-monospace">{{ $transportista->id }}</td>
                                <td class="fw-semibold">{{ trim($transportista->nombre.' '.$transportista->apellido) }}</td>
                                <td>{{ $transportista->carnet_identidad }}</td>
                                <td>{{ $transportista->telefono }}</td>
                                <td>{{ $transportista->licencia }}</td>
                                <td>{{ $transportista->tipo_licencia }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $transportista->estado === 'activo' ? 'text-bg-success' : 'text-bg-secondary' }} px-3 py-2">
                                        {{ $transportista->estado === 'activo' ? 'Disponible' : 'No disponible' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('transportistas.show', $transportista) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Ver</a>
                                    <a href="{{ route('transportistas.edit', $transportista) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Editar</a>
                                    <form action="{{ route('transportistas.destroy', $transportista) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('¿Eliminar este responsable?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted mb-2 fs-1" aria-hidden="true">🧑‍✈️</div>
                                    <p class="mb-1 fw-semibold text-secondary">Aún no hay responsables de transporte registrados</p>
                                    <p class="small text-muted mb-3">Cuando registres el primero, aparecerá aquí con su estado y licencia.</p>
                                    <a href="{{ route('transportistas.create') }}" class="btn btn-transport-main btn-sm">Registrar el primero</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $transportistas->links() }}
    </div>
@endsection
