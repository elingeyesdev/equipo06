@extends('layouts.app')

@section('title', 'Registrar productor')

@push('styles')
<style>
    .btn-producer-main {
        background-color: #2d8a6e;
        border-color: #2d8a6e;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(45, 138, 110, 0.35);
    }
    .btn-producer-main:hover { background-color: #247058; border-color: #247058; color: #fff; }
</style>
@endpush

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-2 d-flex align-items-center gap-2">
            <span aria-hidden="true">🧑‍🌾</span> Registrar productor
        </h1>
        <p class="text-muted mb-0">Los campos mínimos son el nombre; el resto ayuda a localizarlo después.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('productores.store') }}" method="POST">
                @include('productores._form')
                <hr class="my-4 opacity-25">
                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-producer-main d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check2-circle"></i> Guardar productor
                    </button>
                    <a href="{{ route('productores.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
