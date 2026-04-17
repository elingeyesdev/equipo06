@extends('layouts.app')

@section('title', 'Detalle de responsable de transporte')

@push('styles')
<style>
    .table-historial thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
    <div class="mb-4 pb-2 border-bottom border-2 border-opacity-10">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3">
            <div class="flex-grow-1">
                <p class="small text-muted text-uppercase mb-1 fw-semibold">ENT_2.3 Gestión de responsable de transporte</p>
                <h1 class="h3 mb-2 d-flex align-items-center gap-2 flex-wrap">
                    <span class="fs-2" aria-hidden="true">🧑‍✈️</span>
                    <span class="fw-bold">{{ trim($transportista->nombre.' '.$transportista->apellido) }}</span>
                    <span class="badge rounded-pill {{ $transportista->estado === 'activo' ? 'text-bg-success' : 'text-bg-secondary' }} px-3 py-2">
                        {{ $transportista->estado === 'activo' ? 'Disponible' : 'No disponible' }}
                    </span>
                </h1>
                <p class="text-muted mb-0 small">Registro #{{ $transportista->id }}</p>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                <a href="{{ route('transportistas.edit', $transportista) }}" class="btn btn-outline-primary rounded-pill px-4">Editar</a>
                <a href="{{ route('transportistas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Volver al listado</a>
                <form action="{{ route('transportistas.destroy', $transportista) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este responsable?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger rounded-pill px-4">Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <dl class="row mb-0">
                <dt class="col-md-3 text-muted">Carnet de Identidad</dt>
                <dd class="col-md-9">{{ $transportista->carnet_identidad }}</dd>
                <dt class="col-md-3 text-muted">Teléfono</dt>
                <dd class="col-md-9">{{ $transportista->telefono }}</dd>
                <dt class="col-md-3 text-muted">Licencia</dt>
                <dd class="col-md-9">{{ $transportista->licencia }}</dd>
                <dt class="col-md-3 text-muted">Tipo de licencia</dt>
                <dd class="col-md-9">{{ $transportista->tipo_licencia }}</dd>
                <dt class="col-md-3 text-muted">Vencimiento licencia</dt>
                <dd class="col-md-9">{{ $transportista->fecha_vencimiento_licencia?->format('d/m/Y') ?? '—' }}</dd>
            </dl>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-bottom py-3 fw-semibold d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-primary"></i> HISTORIAL DE ENVÍOS
        </div>
        <div class="card-body p-0">
            @if ($transportista->envios->isEmpty())
                <div class="p-4 text-center">
                    <p class="mb-1 fw-semibold text-secondary">Este responsable aún no tiene envíos registrados</p>
                    <p class="small text-muted mb-0">Cuando se asocie a un envío, aparecerá aquí su historial básico.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-historial mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Código</th>
                                <th>Origen</th>
                                <th>Destino</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th class="text-end pe-4">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transportista->envios as $envio)
                                <tr>
                                    <td class="ps-4 fw-semibold font-monospace">{{ $envio->codigo }}</td>
                                    <td>{{ $envio->origen }}</td>
                                    <td>{{ $envio->destino }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $envio->badgeEstadoClass() }}">{{ $envio->etiquetaEstado() }}</span>
                                    </td>
                                    <td class="small text-muted">{{ $envio->fecha_programada?->format('d/m/Y') ?? $envio->fecha_creacion?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('envios.show', $envio) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Ver envío</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
