@extends('layouts.app')

@section('title', 'Editar envío')

@push('styles')
<style>
    .btn-envio-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
    }
    .btn-envio-main:hover { background-color: #327183; border-color: #327183; color: #fff; }
</style>
@endpush

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-2 d-flex align-items-center gap-2">
            <span aria-hidden="true">🚚</span> Editar envío
        </h1>
        <p class="text-muted mb-0">Código de seguimiento: <span class="font-monospace fw-semibold text-success">{{ $envio->codigo }}</span> (no editable).</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('envios.update', $envio) }}" method="POST">
                @method('PUT')
                @include('envios._form')
                <hr class="my-4 opacity-25">
                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-envio-main d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check2-circle"></i> Actualizar envío
                    </button>
                    <a href="{{ route('envios.show', $envio) }}" class="btn btn-outline-secondary rounded-pill px-4">Ver ficha</a>
                    <a href="{{ route('envios.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Volver al listado</a>
                </div>
            </form>
        </div>
    </div>
@endsection
