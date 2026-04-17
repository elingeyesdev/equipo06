@extends('layouts.app')

@section('title', 'Ubicación '.$ubicacion->nombre_ubicacion)

@push('styles')
<style>
    .btn-ubicacion-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
    }
    .btn-ubicacion-main:hover { background-color: #327183; border-color: #327183; color: #fff; }
    .detail-card {
        border-radius: 0.75rem;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .mono-coord {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }
</style>
@endpush

@section('content')
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="h3 mb-1 d-flex align-items-center gap-2 flex-wrap">
                <span aria-hidden="true">📍</span>
                <span>{{ $ubicacion->nombre_ubicacion }}</span>
                <span class="badge rounded-pill {{ $ubicacion->badgeTipoClass() }} px-3 py-2">{{ $ubicacion->etiquetaTipo() }}</span>
            </h1>
            <p class="text-muted mb-0 small">ID {{ $ubicacion->id }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('ubicaciones.edit', $ubicacion) }}" class="btn btn-ubicacion-main d-inline-flex align-items-center gap-2">
                <i class="bi bi-pencil-square"></i>Editar
            </a>
            <a href="{{ route('ubicaciones.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Listado</a>
        </div>
    </div>

    <div class="card shadow-sm border-0 detail-card mb-4">
        <div class="card-body p-4">
            <dl class="row mb-0">
                <dt class="col-sm-3 text-muted small text-uppercase">Dirección</dt>
                <dd class="col-sm-9">{{ $ubicacion->direccion ?: '—' }}</dd>
                <dt class="col-sm-3 text-muted small text-uppercase">Latitud</dt>
                <dd class="col-sm-9 mono-coord">{{ $ubicacion->latitud !== null ? $ubicacion->latitud : '—' }}</dd>
                <dt class="col-sm-3 text-muted small text-uppercase">Longitud</dt>
                <dd class="col-sm-9 mono-coord">{{ $ubicacion->longitud !== null ? $ubicacion->longitud : '—' }}</dd>
                <dt class="col-sm-3 text-muted small text-uppercase">Descripción</dt>
                <dd class="col-sm-9">{{ $ubicacion->descripcion ?: '—' }}</dd>
                @if ($ubicacion->envio)
                    <dt class="col-sm-3 text-muted small text-uppercase">Envío vinculado</dt>
                    <dd class="col-sm-9"><a href="{{ route('envios.show', $ubicacion->envio) }}">{{ $ubicacion->envio->codigo }}</a></dd>
                @endif
                @if ($ubicacion->ruta)
                    <dt class="col-sm-3 text-muted small text-uppercase">Ruta</dt>
                    <dd class="col-sm-9">#{{ $ubicacion->ruta->id }}{{ $ubicacion->ruta->nombre ? ' · '.$ubicacion->ruta->nombre : '' }}</dd>
                @endif
            </dl>
        </div>
    </div>

    <form action="{{ route('ubicaciones.destroy', $ubicacion) }}" method="POST" onsubmit="return confirm('¿Eliminar esta ubicación?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
            <i class="bi bi-trash me-1"></i>Eliminar ubicación
        </button>
    </form>
@endsection
