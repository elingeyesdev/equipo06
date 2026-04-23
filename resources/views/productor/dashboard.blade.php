@extends('layouts.app')

@section('title', 'Panel de productor')

@push('styles')
<style>
    .productor-dash-tile {
        border-radius: 0.75rem;
        border: none;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .productor-dash-tile:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.35rem 1rem rgba(13, 80, 53, 0.12) !important;
    }
    .productor-dash-tile .tile-icon {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
    <div class="mb-4 pb-2 border-bottom border-2 border-opacity-10">
        <h1 class="h3 mb-2 d-flex align-items-center gap-2 flex-wrap">
            <span class="fs-2" aria-hidden="true">🌾</span>
            Tu espacio de trabajo
        </h1>
        <p class="text-muted mb-0 lead fs-6">
            Accedé a la trazabilidad de <strong>producción</strong>. Los módulos de envíos, responsables de transporte y ubicaciones globales están reservados para administración.
        </p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('productores.index') }}" class="text-decoration-none text-reset">
                <div class="card productor-dash-tile shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <div class="tile-icon bg-success-subtle text-success"><i class="bi bi-people fs-4"></i></div>
                        <div>
                            <div class="fw-semibold">Productores</div>
                            <div class="small text-muted">Directorio</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('productos.index') }}" class="text-decoration-none text-reset">
                <div class="card productor-dash-tile shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <div class="tile-icon bg-primary-subtle text-primary"><i class="bi bi-flower1 fs-4"></i></div>
                        <div>
                            <div class="fw-semibold">Productos</div>
                            <div class="small text-muted">Catálogo agrícola</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('eventos-produccion.index') }}" class="text-decoration-none text-reset">
                <div class="card productor-dash-tile shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <div class="tile-icon bg-warning-subtle text-warning-emphasis"><i class="bi bi-calendar2-week fs-4"></i></div>
                        <div>
                            <div class="fw-semibold">Proceso productivo</div>
                            <div class="small text-muted">Eventos</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('lotes.index') }}" class="text-decoration-none text-reset">
                <div class="card productor-dash-tile shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <div class="tile-icon bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-box-seam fs-4"></i></div>
                        <div>
                            <div class="fw-semibold">Lotes</div>
                            <div class="small text-muted">Seguimiento</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    @if($notificaciones->isNotEmpty())
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 border-start border-4 border-warning">
                    <div class="card-body p-4">
                        <h2 class="h6 text-uppercase text-muted mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-bell text-warning"></i> Alertas y confirmaciones
                        </h2>
                        @foreach($notificaciones as $notif)
                            <div class="alert {{ $notif->read_at ? 'alert-success' : 'alert-warning' }} border-0 d-flex align-items-start gap-3 mb-3">
                                <i class="bi {{ $notif->read_at ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' }} fs-4 mt-1"></i>
                                <div class="flex-grow-1">
                                    <strong>{{ $notif->read_at ? 'Confirmado' : 'Pendiente' }}: Comenzar cultivo</strong> del producto: {{ $notif->data['mensaje'] ?? 'Producto' }}
                                    @if($notif->read_at)
                                        <div class="small text-muted mt-1">Confirmado el {{ $notif->read_at->format('d/m/Y H:i') }}</div>
                                    @endif
                                    <div class="mt-2">
                                        <a href="{{ route('productos.show', $notif->data['producto_id']) }}" class="btn btn-sm {{ $notif->read_at ? 'btn-success' : 'btn-warning' }}">Ver producto</a>
                                        @if(!$notif->read_at)
                                            <form action="{{ route('notifications.markAsRead', $notif->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">Marcar como leída</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 border-start border-4 border-success">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Resumen</h2>
                    <p class="mb-0 text-muted">
                        Usá el menú superior o los accesos rápidos para registrar y consultar información de campo. Si necesitás gestión de logística o catálogos administrativos, contactá a un usuario con rol <span class="badge text-bg-light text-success border">Administrador</span>.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Tu cuenta</h2>
                    <p class="mb-1"><span class="text-muted">Nombre:</span> {{ auth()->user()->nombreCompleto() }}</p>
                    <p class="mb-1"><span class="text-muted">Correo:</span> {{ auth()->user()->email }}</p>
                    <p class="mb-0"><span class="text-muted">Rol:</span> <span class="badge rounded-pill text-bg-success text-capitalize">{{ auth()->user()->rol }}</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection
