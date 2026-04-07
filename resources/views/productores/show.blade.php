@extends('layouts.app')

@section('title', 'Detalle del productor')

@push('styles')
<style>
    .btn-producer-main {
        background-color: #2d8a6e;
        border-color: #2d8a6e;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
    }
    .btn-producer-main:hover { background-color: #247058; border-color: #247058; color: #fff; }
</style>
@endpush

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-2 d-flex align-items-center gap-2">
                <span aria-hidden="true">🧑‍🌾</span> {{ $producer->full_name }}
            </h1>
            <p class="text-muted mb-0">Ficha del productor y datos para trazabilidad.</p>
        </div>
        <span class="badge rounded-pill {{ $producer->is_active ? 'text-bg-success' : 'text-bg-secondary' }} px-3 py-2 fs-6">
            {{ $producer->is_active ? 'Activo' : 'Inactivo' }}
        </span>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Identificación</h2>
                    <dl class="row mb-0 g-3">
                        <dt class="col-sm-4 text-muted small">ID interno</dt>
                        <dd class="col-sm-8 mb-0 fw-medium">{{ $producer->id }}</dd>
                        <dt class="col-sm-4 text-muted small">Documento</dt>
                        <dd class="col-sm-8 mb-0">{{ $producer->document_number ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted small">Dirección</dt>
                        <dd class="col-sm-8 mb-0">{{ $producer->address ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100 bg-success-subtle bg-opacity-10">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Contacto</h2>
                    <p class="mb-3 d-flex align-items-start gap-2">
                        <i class="bi bi-telephone text-success mt-1"></i>
                        <span>{{ $producer->phone ?? 'Sin teléfono' }}</span>
                    </p>
                    <p class="mb-0 d-flex align-items-start gap-2 text-break">
                        <i class="bi bi-envelope text-success mt-1"></i>
                        <span>{{ $producer->email ?? 'Sin correo' }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @php
        $nProductos = $producer->productos()->count();
    @endphp
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary-subtle text-primary p-3"><i class="bi bi-flower1 fs-4"></i></div>
                <div>
                    <div class="fw-semibold">Productos agrícolas vinculados</div>
                    <div class="text-muted small">Desde aquí puedes saltar al inventario cuando los registres.</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill text-bg-light text-dark border px-3 py-2">{{ $nProductos }} {{ $nProductos === 1 ? 'producto' : 'productos' }}</span>
                <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">Ver productos</a>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex flex-wrap gap-2">
        <a href="{{ route('productores.edit', $producer) }}" class="btn btn-producer-main d-inline-flex align-items-center gap-2">
            <i class="bi bi-pencil-square"></i> Editar
        </a>
        <a href="{{ route('productores.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Volver al listado</a>
    </div>
@endsection
