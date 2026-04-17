@extends('layouts.app')

@section('title', 'Registrar ubicación')

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
    <div class="mb-4">
        <h1 class="h3 mb-2 d-flex align-items-center gap-2">
            <span aria-hidden="true">📍</span> Registrar ubicación
        </h1>
        <p class="text-muted mb-0">Datos geográficos o de referencia para rutas y puntos de control. Use el mapa o las coordenadas (sin seguimiento en tiempo real).</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('ubicaciones.store') }}" method="POST">
                @include('ubicaciones._form', ['ubicacion' => $ubicacion, 'tipos' => $tipos])
                <hr class="my-4 opacity-25">
                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-ubicacion-main d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check2-circle"></i> Guardar ubicación
                    </button>
                    <a href="{{ route('ubicaciones.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    @include('ubicaciones._leaflet_map')
@endsection
