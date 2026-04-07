@extends('layouts.app')

@section('title', 'Registrar evento de producción')

@push('styles')
<style>
    .btn-process-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
    }
    .btn-process-main:hover { background-color: #327183; border-color: #327183; color: #fff; }
    .hint-etapas {
        font-size: 0.875rem;
        border-radius: 0.5rem;
        padding: 0.65rem 1rem;
        margin-top: 0.5rem;
        background: linear-gradient(90deg, #e8f8ef 0%, #e4f4ea 50%, #fdf6e3 100%);
        color: #2d4a3a;
        border: 1px solid rgba(0,0,0,0.06);
    }
</style>
@endpush

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-2 d-flex align-items-center gap-2">
            <span aria-hidden="true">📋</span> Registrar evento de producción
        </h1>
        <p class="text-muted mb-0">Elige el producto y la etapa (siembra, cultivo o cosecha). Opcionalmente programa cuándo debe mostrarse como «en proceso».</p>
    </div>

    @if ($productos->isEmpty())
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>
                <strong>No hay productos activos.</strong> Crea primero un producto agrícola para poder registrar eventos.
                <div class="mt-2">
                    <a href="{{ route('productos.create') }}" class="btn btn-primary btn-sm">Ir a productos</a>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-lg-5">
                @include('eventos-produccion._form', [
                    'evento' => $evento,
                    'productos' => $productos,
                    'etapas' => $etapas,
                    'estados' => $estados,
                    'mode' => 'create',
                ])
            </div>
        </div>
    @endif
@endsection
