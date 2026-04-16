@extends('layouts.app')

@section('title', 'Detalle del envío')

@push('styles')
<style>
    .btn-envio-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
    }
    .btn-envio-main:hover { background-color: #327183; border-color: #327183; color: #fff; }
    .envio-code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-weight: 600; color: #1a5c38; }
</style>
@endpush

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-2 d-flex align-items-center gap-2">
                <span aria-hidden="true">🚚</span> Envío <span class="envio-code">{{ $envio->codigo }}</span>
            </h1>
            <p class="text-muted mb-0">Resumen del envío, productos asociados y asignación de transporte.</p>
        </div>
        <span class="badge rounded-pill {{ $envio->badgeEstadoClass() }} px-3 py-2 fs-6">{{ $envio->etiquetaEstado() }}</span>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Ruta</h2>
                    <p class="mb-2"><i class="bi bi-geo-alt text-primary me-2"></i><strong>Origen</strong><br><span class="text-muted">{{ $envio->origen }}</span></p>
                    <p class="mb-0"><i class="bi bi-geo-alt-fill text-primary me-2"></i><strong>Destino</strong><br><span class="text-muted">{{ $envio->destino }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100 bg-primary-subtle bg-opacity-10">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Fechas</h2>
                    <p class="mb-2"><strong>Creación:</strong> {{ $envio->fecha_creacion?->format('d/m/Y') }}</p>
                    <p class="mb-2"><strong>Programada:</strong> {{ $envio->fecha_programada?->format('d/m/Y') ?? '—' }}</p>
                    <p class="mb-0 small text-muted">Registro actualizado: {{ $envio->updated_at?->format('d/m/Y H:i') ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Observaciones</h2>
                    <p class="mb-0 {{ $envio->observaciones ? '' : 'text-muted fst-italic' }}">{{ $envio->observaciones ?: 'Sin observaciones.' }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3 fw-semibold d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam text-primary"></i> Productos del envío
                </div>
                <div class="card-body p-0">
                    @if ($envio->detalles->isEmpty())
                        <p class="text-muted fst-italic p-4 mb-0">Aún no hay productos asignados a este envío.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light"><tr><th class="ps-4">Producto - Productor</th><th class="text-end pe-4">Cantidad</th></tr></thead>
                                <tbody>
                                    @foreach ($envio->detalles as $d)
                                        <tr>
                                            <td class="ps-4 fw-medium">{{ $d->producto ? $d->producto->etiquetaNombreYProductor() : '—' }}</td>
                                            <td class="text-end pe-4 font-monospace">{{ number_format((float) $d->cantidad, 3, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3 fw-semibold d-flex align-items-center gap-2">
                    <i class="bi bi-person-badge text-primary"></i> Asignaciones de transporte
                </div>
                <div class="card-body">
                    @if ($envio->asignaciones->isEmpty())
                        <p class="text-muted fst-italic mb-0">Sin asignación de transportista o vehículo.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($envio->asignaciones as $a)
                                <li class="list-group-item px-0 border-0 border-bottom">
                                    <div class="small text-muted">{{ $a->fecha_asignacion?->format('d/m/Y H:i') }}</div>
                                    <div><strong>{{ $a->transportista->nombre ?? '—' }}</strong></div>
                                    <div class="small">Vehículo: {{ $a->vehiculo->placa ?? '—' }} @if($a->vehiculo?->descripcion)<span class="text-muted">— {{ $a->vehiculo->descripcion }}</span>@endif</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex flex-wrap gap-2">
        <a href="{{ route('envios.edit', $envio) }}" class="btn btn-envio-main d-inline-flex align-items-center gap-2">
            <i class="bi bi-pencil-square"></i> Editar
        </a>
        <a href="{{ route('envios.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Volver al listado</a>
    </div>
@endsection
