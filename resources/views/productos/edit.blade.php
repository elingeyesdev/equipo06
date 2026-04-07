@extends('layouts.app')

@section('title', 'Editar producto')

@push('styles')
<style>
    .btn-product-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
    }
    .btn-product-main:hover { background-color: #327183; border-color: #327183; color: #fff; }
</style>
@endpush

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-2 d-flex align-items-center gap-2">
            <span aria-hidden="true">🌱</span> Editar producto
        </h1>
        <p class="text-muted mb-0">Actualiza tipo, descripción o productor asignado.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('productos.update', $producto) }}" method="POST">
                @method('PUT')
                @include('productos._form')
                <hr class="my-4 opacity-25">
                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-product-main d-inline-flex align-items-center gap-2">
                        <i class="bi bi-arrow-repeat"></i> Actualizar
                    </button>
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
