@extends('layouts.app')

@section('title', 'Editar ubicación')

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
</style>
@endpush

@section('content')
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="h3 mb-2 d-flex align-items-center gap-2">
                <span aria-hidden="true">📍</span> Editar ubicación
            </h1>
            <p class="text-muted mb-0 small">ID {{ $ubicacion->id }} · {{ $ubicacion->nombre_ubicacion }}</p>
        </div>
        <a href="{{ route('ubicaciones.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i>Volver al listado
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('ubicaciones.update', $ubicacion) }}" method="POST">
                @method('PUT')
                @include('ubicaciones._form', ['ubicacion' => $ubicacion, 'tipos' => $tipos])
                <hr class="my-4 opacity-25">
                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-ubicacion-main d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check2-circle"></i> Guardar cambios
                    </button>
                    <a href="{{ route('ubicaciones.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
