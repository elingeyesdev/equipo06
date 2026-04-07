@extends('layouts.app')

@section('title', 'Detalle del producto')

@push('styles')
<style>
    .btn-product-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
    }
    .btn-product-main:hover { background-color: #327183; border-color: #327183; color: #fff; }
</style>
@endpush

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-2 d-flex align-items-center gap-2">
                <span aria-hidden="true">📦</span> {{ $producto->nombre }}
            </h1>
            <p class="text-muted mb-0">Ficha del producto y vínculo con el productor.</p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="badge rounded-pill {{ $producto->badgeTipoClass() }} px-3 py-2">{{ $producto->etiquetaTipo() }}</span>
            <span class="badge rounded-pill {{ $producto->activo ? 'text-bg-success' : 'text-bg-secondary' }} px-3 py-2">
                {{ $producto->activo ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Descripción</h2>
                    <p class="mb-0 {{ $producto->descripcion ? '' : 'text-muted fst-italic' }}">
                        {{ $producto->descripcion ?: 'Sin descripción registrada.' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100 bg-primary-subtle bg-opacity-10">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Productor</h2>
                    <p class="fw-semibold mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-primary"></i>
                        {{ $producto->productor->full_name ?? '—' }}
                    </p>
                    <a href="{{ route('productores.show', $producto->productor) }}" class="small">Ver ficha del productor →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex flex-wrap gap-2">
        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-product-main d-inline-flex align-items-center gap-2">
            <i class="bi bi-pencil-square"></i> Editar
        </a>
        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Volver al listado</a>
    </div>
@endsection
