@extends('layouts.app')

@section('title', 'Gestión del proceso de producción')

@push('styles')
<style>
    .btn-process-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        padding: 0.55rem 1.25rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
    }
    .btn-process-main:hover {
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
    .dash-etapa {
        border-radius: 1rem;
        border: none;
        overflow: hidden;
        min-height: 22rem;
        display: flex;
        flex-direction: column;
    }
    .dash-etapa-siembra { background: linear-gradient(180deg, #e8f8ef 0%, #fff 38%); border: 1px solid rgba(45,138,110,0.15) !important; }
    .dash-etapa-cultivo { background: linear-gradient(180deg, #e4f4ea 0%, #fff 38%); border: 1px solid rgba(45,138,110,0.18) !important; }
    .dash-etapa-cosecha { background: linear-gradient(180deg, #fdf6e4 0%, #fff 38%); border: 1px solid rgba(234,179,8,0.25) !important; }
    .dash-etapa-head {
        padding: 1rem 1.15rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .dash-etapa-body {
        padding: 0.75rem 1rem 1rem;
        flex: 1;
        overflow-y: auto;
        max-height: 28rem;
    }
    .mini-event {
        border-radius: 0.65rem;
        background: #fff;
        border: 1px solid rgba(0,0,0,0.06);
        padding: 0.65rem 0.75rem;
        margin-bottom: 0.6rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .mini-event:last-child { margin-bottom: 0; }
    .timeline-vertical {
        position: relative;
        padding-left: 2rem;
        margin-left: 0.25rem;
    }
    .timeline-vertical::before {
        content: '';
        position: absolute;
        left: 0.45rem;
        top: 0.25rem;
        bottom: 0.25rem;
        width: 3px;
        background: linear-gradient(180deg, #dee2e6, #e9ecef);
        border-radius: 3px;
    }
    .timeline-item { position: relative; padding-bottom: 1.35rem; }
    .timeline-item:last-child { padding-bottom: 0; }
    .timeline-dot {
        position: absolute;
        left: -1.6rem;
        top: 0.5rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: var(--tl-accent, #adb5bd);
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px var(--tl-accent, #adb5bd);
        z-index: 1;
    }
    .timeline-card {
        border-radius: 0.75rem;
        border: 1px solid rgba(0,0,0,0.06);
        border-left: 4px solid var(--tl-accent, #adb5bd);
        background: #fff;
    }
    .badge-etapa-siembra {
        background-color: #d8f3e4 !important;
        color: #1a5c38 !important;
        font-weight: 600;
    }
    .badge-etapa-cultivo {
        background-color: #c5ead4 !important;
        color: #14532d !important;
        font-weight: 600;
    }
    .badge-etapa-cosecha {
        background-color: #fdf0d5 !important;
        color: #7a5c16 !important;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
    <div class="mb-4 pb-2 border-bottom border-2 border-opacity-10">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h1 class="h3 mb-2 d-flex align-items-center gap-2">
                    <span class="fs-2" aria-hidden="true">📋</span>
                    ENT_1.3 Gestión del proceso de producción
                    @if($notificacionesPendientes > 0)
                        <span class="badge bg-danger rounded-pill fs-6 px-2 py-1">{{ $notificacionesPendientes }}</span>
                    @endif
                </h1>
                <p class="text-muted mb-0 lead fs-6">
                    Panel por etapas (siembra, cultivo, cosecha). Formato de producto: <strong>Producto - Productor</strong>.
                </p>
            </div>
            <a href="{{ route('eventos-produccion.create') }}" class="btn btn-process-main d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                Registrar evento
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary p-3"><i class="bi bi-list-task fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Total eventos</div>
                        <div class="h4 mb-0 fw-bold">{{ $total }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-info-subtle text-info-emphasis p-3"><i class="bi bi-arrow-repeat fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">En proceso</div>
                        <div class="h4 mb-0 fw-bold">{{ $enProceso }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card dash-stat shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success-subtle text-success p-3"><i class="bi bi-check2-all fs-4"></i></div>
                    <div>
                        <div class="text-muted small text-uppercase">Completados</div>
                        <div class="h4 mb-0 fw-bold">{{ $completados }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
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
    </div>

    <h2 class="h6 text-uppercase text-muted mb-3 px-1">Vista por etapa</h2>
    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card shadow-sm dash-etapa dash-etapa-siembra h-100">
                <div class="dash-etapa-head text-success border-bottom border-success border-opacity-25">
                    <span class="fs-3" aria-hidden="true">🌱</span> Siembra
                </div>
                <div class="dash-etapa-body">
                    @forelse ($eventosPorEtapa['siembra'] as $ev)
                        <div class="mini-event">
                            <div class="small text-muted mb-1"><i class="bi bi-calendar3 me-1"></i>{{ $ev->fecha->format('d/m/Y') }}</div>
                            <div class="fw-semibold small mb-2">{{ $ev->etiquetaProductoProductor() }}</div>
                            <span class="badge rounded-pill {{ $ev->badgeEstadoEfectivoClass() }}">{{ $ev->etiquetaEstadoEfectivo() }}</span>
                            <div class="mt-2 d-flex flex-wrap gap-1">
                                @if ($ev->estado !== 'completado')
                                    <form action="{{ route('eventos-produccion.completar', $ev) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill">Completar</button>
                                    </form>
                                @endif
                                <a href="{{ route('eventos-produccion.edit', $ev) }}" class="btn btn-sm btn-outline-primary rounded-pill">Editar</a>
                                <form action="{{ route('eventos-produccion.destroy', $ev) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este evento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small fst-italic mb-0">Sin eventos de siembra.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm dash-etapa dash-etapa-cultivo h-100">
                <div class="dash-etapa-head text-success border-bottom border-success border-opacity-25">
                    <span class="fs-3" aria-hidden="true">🌿</span> Cultivo
                </div>
                <div class="dash-etapa-body">
                    @forelse ($eventosPorEtapa['cultivo'] as $ev)
                        <div class="mini-event">
                            <div class="small text-muted mb-1"><i class="bi bi-calendar3 me-1"></i>{{ $ev->fecha->format('d/m/Y') }}</div>
                            <div class="fw-semibold small mb-2">{{ $ev->etiquetaProductoProductor() }}</div>
                            <span class="badge rounded-pill {{ $ev->badgeEstadoEfectivoClass() }}">{{ $ev->etiquetaEstadoEfectivo() }}</span>
                            <div class="mt-2 d-flex flex-wrap gap-1">
                                @if ($ev->estado !== 'completado')
                                    <form action="{{ route('eventos-produccion.completar', $ev) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill">Completar</button>
                                    </form>
                                @endif
                                <a href="{{ route('eventos-produccion.edit', $ev) }}" class="btn btn-sm btn-outline-primary rounded-pill">Editar</a>
                                <form action="{{ route('eventos-produccion.destroy', $ev) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este evento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small fst-italic mb-0">Sin eventos de cultivo.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm dash-etapa dash-etapa-cosecha h-100">
                <div class="dash-etapa-head text-warning-emphasis border-bottom border-warning border-opacity-25">
                    <span class="fs-3" aria-hidden="true">🌾</span> Cosecha
                </div>
                <div class="dash-etapa-body">
                    @forelse ($eventosPorEtapa['cosecha'] as $ev)
                        <div class="mini-event">
                            <div class="small text-muted mb-1"><i class="bi bi-calendar3 me-1"></i>{{ $ev->fecha->format('d/m/Y') }}</div>
                            <div class="fw-semibold small mb-2">{{ $ev->etiquetaProductoProductor() }}</div>
                            <span class="badge rounded-pill {{ $ev->badgeEstadoEfectivoClass() }}">{{ $ev->etiquetaEstadoEfectivo() }}</span>
                            <div class="mt-2 d-flex flex-wrap gap-1">
                                @if ($ev->estado !== 'completado')
                                    <form action="{{ route('eventos-produccion.completar', $ev) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill">Completar</button>
                                    </form>
                                @endif
                                <a href="{{ route('eventos-produccion.edit', $ev) }}" class="btn btn-sm btn-outline-primary rounded-pill">Editar</a>
                                <form action="{{ route('eventos-produccion.destroy', $ev) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este evento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small fst-italic mb-0">Sin eventos de cosecha.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-primary"></i>
                <span class="fw-semibold">Línea de tiempo reciente</span>
            </div>
            <span class="badge rounded-pill bg-light text-dark border small">Últimos {{ $timelineEventos->count() }} movimientos</span>
        </div>
        <div class="card-body bg-body-tertiary bg-opacity-50 p-4">
            @if ($timelineEventos->isEmpty())
                <div class="text-center py-5 text-muted">
                    <div class="fs-1 mb-2" aria-hidden="true">🌱</div>
                    <p class="mb-0 fw-medium">Aún no hay eventos de producción registrados</p>
                    <p class="small mb-3">Cuando registres el primero, verás aquí la línea de tiempo.</p>
                    <a href="{{ route('eventos-produccion.create') }}" class="btn btn-process-main btn-sm">Registrar evento</a>
                </div>
            @else
                <div class="timeline-vertical">
                    @if($notificacionesPendientes > 0)
                        <div class="timeline-item">
                            <span class="timeline-dot bg-warning"></span>
                            <div class="timeline-card shadow-sm p-3 border-warning">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="fs-4 lh-1" aria-hidden="true">🚨</span>
                                        <span class="badge rounded-pill bg-warning text-dark px-3 py-2">Alertas</span>
                                    </div>
                                    <div class="text-muted small d-flex align-items-center gap-1">
                                        <i class="bi bi-bell"></i>
                                        Pendientes
                                    </div>
                                </div>
                                <div class="small text-secondary mb-2 fw-semibold">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Tienes {{ $notificacionesPendientes }} alerta(s) para comenzar cultivo
                                </div>
                                <p class="small mb-2 text-body-secondary">Revisa tus notificaciones para ver los productos que requieren atención.</p>
                                <div class="d-flex flex-column align-items-start gap-1">
                                    <span class="badge rounded-pill bg-danger px-3 py-2">
                                        Acción requerida
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @foreach ($timelineEventos as $ev)
                        <div class="timeline-item">
                            <span class="timeline-dot" style="--tl-accent: {{ $ev->colorEtapaHex() }}"></span>
                            <div class="timeline-card shadow-sm p-3" style="--tl-accent: {{ $ev->colorEtapaHex() }}">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="fs-4 lh-1" aria-hidden="true">{{ $ev->emojiEtapa() }}</span>
                                        <span class="badge rounded-pill {{ $ev->badgeEtapaClass() }} px-3 py-2">{{ $ev->etiquetaEtapa() }}</span>
                                    </div>
                                    <div class="text-muted small d-flex align-items-center gap-1">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $ev->fecha->format('d/m/Y') }}
                                    </div>
                                </div>
                                <div class="small text-secondary mb-2 fw-semibold">
                                    <i class="bi bi-box-seam me-1"></i>{{ $ev->etiquetaProductoProductor() }}
                                </div>
                                @if($ev->descripcion)
                                    <p class="small mb-2 text-body-secondary">{{ \Illuminate\Support\Str::limit($ev->descripcion, 160) }}</p>
                                @endif
                                <div class="d-flex flex-column align-items-start gap-1">
                                    <span class="badge rounded-pill {{ $ev->badgeEstadoEfectivoClass() }} px-3 py-2">
                                        {{ $ev->etiquetaEstadoEfectivo() }}
                                    </span>
                                    @if ($ev->estadoEsAutomaticoEnProceso())
                                        <span class="small text-muted"><i class="bi bi-clock me-1"></i>En proceso por hora programada</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @foreach ($notificacionesConfirmadas as $notif)
                        <div class="timeline-item">
                            <span class="timeline-dot bg-success"></span>
                            <div class="timeline-card shadow-sm p-3 border-success">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="fs-4 lh-1" aria-hidden="true">✅</span>
                                        <span class="badge rounded-pill bg-success px-3 py-2">Confirmado</span>
                                    </div>
                                    <div class="text-muted small d-flex align-items-center gap-1">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $notif->read_at->format('d/m/Y') }}
                                    </div>
                                </div>
                                <div class="small text-secondary mb-2 fw-semibold">
                                    <i class="bi bi-check-circle me-1"></i>Pedido cumplido: {{ $notif->data['mensaje'] ?? 'Producto' }}
                                </div>
                                <p class="small mb-2 text-body-secondary">Se registró el evento de siembra correspondiente.</p>
                                <div class="d-flex flex-column align-items-start gap-1">
                                    <span class="badge rounded-pill bg-success px-3 py-2">
                                        Completado
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
