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
    .table-events thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6c757d;
        border-bottom-width: 1px;
    }
    .table-events tbody tr { border-bottom: 1px solid rgba(0,0,0,0.04); }
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
    /* Timeline vertical */
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
    .timeline-item {
        position: relative;
        padding-bottom: 1.35rem;
    }
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
</style>
@endpush

@section('content')
    <div class="mb-4 pb-2 border-bottom border-2 border-opacity-10">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h1 class="h3 mb-2 d-flex align-items-center gap-2">
                    <span class="fs-2" aria-hidden="true">📋</span>
                    ENT_1.3 Gestión del proceso de producción
                </h1>
                <p class="text-muted mb-0 lead fs-6">
                    Registro y seguimiento de etapas: pendiente, en proceso (manual o por hora programada) y completado.
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

    {{-- Línea de tiempo --}}
    <div class="card shadow border-0 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-primary"></i>
                <span class="fw-semibold">Historial reciente (línea de tiempo)</span>
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
                                <div class="small text-secondary mb-2">
                                    <i class="bi bi-box-seam me-1"></i>
                                    <strong>Producto:</strong> {{ $ev->producto->nombre ?? '—' }}
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
                </div>
            @endif
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card shadow border-0 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
            <i class="bi bi-table text-primary"></i>
            <span class="fw-semibold">Listado de eventos</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-events mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Producto</th>
                            <th>Etapa</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($eventos as $evento)
                            <tr>
                                <td class="ps-4 text-muted">{{ $evento->id }}</td>
                                <td class="fw-semibold">{{ $evento->producto->nombre ?? '—' }}</td>
                                <td>
                                    <span class="d-inline-flex align-items-center gap-1">
                                        <span aria-hidden="true">{{ $evento->emojiEtapa() }}</span>
                                        <span class="badge rounded-pill {{ $evento->badgeEtapaClass() }} px-3 py-2">{{ $evento->etiquetaEtapa() }}</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ $evento->fecha->format('d/m/Y') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge rounded-pill {{ $evento->badgeEstadoEfectivoClass() }} px-3 py-2 align-self-start">
                                            {{ $evento->etiquetaEstadoEfectivo() }}
                                        </span>
                                        @if ($evento->estadoEsAutomaticoEnProceso())
                                            <span class="small text-muted">Auto. por horario</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex flex-wrap gap-2 justify-content-end">
                                        @if ($evento->estado !== 'completado')
                                            <form action="{{ route('eventos-produccion.completar', $evento) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 d-inline-flex align-items-center gap-1" title="Marcar como completado">
                                                    <i class="bi bi-check2-circle"></i>
                                                    Completado
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('eventos-produccion.edit', $evento) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            Editar
                                        </a>
                                        <form action="{{ route('eventos-produccion.destroy', $evento) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('¿Eliminar este evento?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted mb-2 fs-1" aria-hidden="true">📋</div>
                                    <p class="mb-1 fw-semibold text-secondary">Aún no hay eventos de producción registrados</p>
                                    <p class="small text-muted mb-3">Registra siembra, cultivo o cosecha vinculados a un producto.</p>
                                    <a href="{{ route('eventos-produccion.create') }}" class="btn btn-process-main btn-sm">Registrar evento</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $eventos->links() }}
    </div>
@endsection
