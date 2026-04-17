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

    <div class="alert border-0 shadow-sm d-flex align-items-start gap-3 mb-4" role="status" style="background: linear-gradient(90deg, #eef7fc 0%, #f2fbf6 100%); border: 1px solid rgba(0,0,0,0.06) !important;">
        <span class="rounded-3 bg-white p-2 shadow-sm"><i class="bi bi-signpost-2 text-primary fs-4"></i></span>
        <div class="flex-grow-1">
            <div class="fw-semibold text-dark mb-2">Seguimiento de llegada</div>
            <span class="badge rounded-pill {{ $envio->badgeValidacionLlegadaClass() }} px-3 py-2 fs-6">{{ $envio->etiquetaValidacionLlegada() }}</span>
            <p class="small text-muted mb-0 mt-2">Basado en la <strong>ubicación actual</strong> y su <strong>tipo</strong> (origen, punto intermedio o destino). Sin GPS ni distancias.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Ruta</h2>
                    <p class="mb-2"><i class="bi bi-geo-alt text-primary me-2"></i><strong>Origen</strong><br><span class="text-muted">{{ $envio->origen }}</span></p>
                    <p class="mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i><strong>Destino</strong><br><span class="text-muted">{{ $envio->destino }}</span></p>
                    <hr class="text-muted opacity-25 my-3">
                    <p class="mb-1 small text-uppercase text-muted">Ubicación actual (seguimiento)</p>
                    @if ($envio->ubicacionActual)
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="badge rounded-pill {{ $envio->ubicacionActual->badgeTipoClass() }}">{{ $envio->ubicacionActual->etiquetaTipo() }}</span>
                            <a href="{{ route('ubicaciones.show', $envio->ubicacionActual) }}" class="fw-semibold text-decoration-none">{{ $envio->ubicacionActual->nombre_ubicacion }}</a>
                        </div>
                        @if ($envio->ubicacionActual->direccion)
                            <p class="small text-muted mb-0 mt-2">{{ $envio->ubicacionActual->direccion }}</p>
                        @endif
                    @else
                        <p class="text-muted fst-italic mb-0 small">Sin ubicación actual asignada. Asígnala al editar el envío.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100 bg-primary-subtle bg-opacity-10">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Fechas</h2>
                    <p class="mb-2"><strong>Creación:</strong> {{ $envio->fecha_creacion?->format('d/m/Y') }}</p>
                    <p class="mb-2"><strong>Programada:</strong> {{ $envio->fecha_programada?->format('d/m/Y') ?? '—' }}</p>
                    <p class="mb-2">
                        <strong>Responsable asignado:</strong>
                        @if ($envio->transportista)
                            <a href="{{ route('transportistas.show', $envio->transportista) }}" class="text-decoration-none">
                                {{ trim($envio->transportista->nombre.' '.$envio->transportista->apellido) }}
                            </a>
                        @else
                            <span class="text-muted">Sin asignar</span>
                        @endif
                    </p>
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
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 fw-semibold d-flex align-items-center gap-2">
                    <i class="bi bi-clipboard2-check text-primary"></i> Conformidad de recibido (ENT 2.4)
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('envios.recepcion.conformidad', $envio) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Check de recibido *</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="conforme" id="conforme_si" value="1"
                                               {{ old('conforme', $envio->recepcion?->conforme) === true || old('conforme', (int) ($envio->recepcion?->conforme ?? 1)) === 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="conforme_si">Conforme</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="conforme" id="conforme_no" value="0"
                                               {{ old('conforme', $envio->recepcion?->conforme) === false || (string) old('conforme') === '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="conforme_no">No conforme</label>
                                    </div>
                                </div>
                                @error('conforme')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="observaciones_recepcion" class="form-label fw-medium">Observación <span class="text-muted fw-normal">(opcional)</span></label>
                                <textarea id="observaciones_recepcion" name="observaciones" rows="3"
                                          class="form-control @error('observaciones') is-invalid @enderror"
                                          placeholder="Detalle breve si la entrega llegó observada...">{{ old('observaciones', $envio->recepcion?->observaciones) }}</textarea>
                                @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 d-flex flex-wrap align-items-center gap-3">
                                <button type="submit" class="btn btn-envio-main d-inline-flex align-items-center gap-2">
                                    <i class="bi bi-check2-circle"></i> Guardar conformidad
                                </button>
                                @if ($envio->recepcion)
                                    <span class="small text-muted">
                                        Último registro:
                                        @if ($envio->recepcion->conforme === true)
                                            <span class="badge text-bg-success">Conforme</span>
                                        @elseif ($envio->recepcion->conforme === false)
                                            <span class="badge text-bg-warning text-dark">No conforme</span>
                                        @else
                                            <span class="badge text-bg-secondary">Pendiente</span>
                                        @endif
                                        · {{ $envio->recepcion->updated_at?->format('d/m/Y H:i') ?? '—' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </form>
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
