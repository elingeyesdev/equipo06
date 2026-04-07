@extends('layouts.app')

@section('title', 'Registrar producto')

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
            <span aria-hidden="true">🌱</span> Registrar producto agrícola
        </h1>
        <p class="text-muted mb-0">Completa los datos y asígnalo a un productor existente.</p>
    </div>

    @if ($productores->isEmpty())
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>
                <strong>No hay productores activos.</strong> Registra al menos un productor activo antes de crear productos.
                <div class="mt-2">
                    <a href="{{ route('productores.create') }}" class="btn btn-success btn-sm">Ir a productores</a>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-lg-5">
                <form action="{{ route('productos.store') }}" method="POST">
                    @include('productos._form')
                    <hr class="my-4 opacity-25">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-product-main d-inline-flex align-items-center gap-2">
                            <i class="bi bi-check2-circle"></i> Guardar producto
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
