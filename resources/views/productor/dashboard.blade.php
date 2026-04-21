@extends('layouts.app')

@section('title', 'Panel de productor')

@section('content')
    <div class="mb-4 pb-2 border-bottom border-2 border-opacity-10">
        <h1 class="h3 mb-2 d-flex align-items-center gap-2">
            <span class="fs-2" aria-hidden="true">🌾</span>
            Panel de productor
        </h1>
        <p class="text-muted mb-0 lead fs-6">
            Vista resumida para usuarios con rol <strong>productor</strong>. El resto de módulos administrativos no están disponibles aquí.
        </p>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Accesos permitidos</h2>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="{{ route('productores.index') }}" class="fw-semibold text-decoration-none">Productores</a></li>
                        <li class="mb-2"><a href="{{ route('productos.index') }}" class="fw-semibold text-decoration-none">Productos agrícolas</a></li>
                        <li class="mb-2"><a href="{{ route('eventos-produccion.index') }}" class="fw-semibold text-decoration-none">Proceso productivo</a></li>
                        <li class="mb-0"><a href="{{ route('lotes.index') }}" class="fw-semibold text-decoration-none">Lotes</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100 bg-primary-subtle bg-opacity-10">
                <div class="card-body p-4">
                    <h2 class="h6 text-uppercase text-muted mb-3">Tu cuenta</h2>
                    <p class="mb-1"><strong>Nombre:</strong> {{ auth()->user()->nombreCompleto() }}</p>
                    <p class="mb-1"><strong>Correo:</strong> {{ auth()->user()->email }}</p>
                    <p class="mb-0"><strong>Rol:</strong> <span class="badge text-bg-success text-capitalize">{{ auth()->user()->rol }}</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection
