@extends('layouts.app')

@section('title', 'Editar responsable de transporte')

@push('styles')
<style>
    .btn-transport-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
    }
    .btn-transport-main:hover { background-color: #327183; border-color: #327183; color: #fff; }
</style>
@endpush

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-2 d-flex align-items-center gap-2">
            <span aria-hidden="true">🚚</span> Editar responsable de transporte
        </h1>
        <p class="text-muted mb-0">Actualiza los datos personales, estado y licencia del responsable.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('transportistas.update', $transportista) }}" method="POST">
                @method('PUT')
                @include('transportistas._form')
                <hr class="my-4 opacity-25">
                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-transport-main d-inline-flex align-items-center gap-2">
                        <i class="bi bi-arrow-repeat"></i> Actualizar
                    </button>
                    <a href="{{ route('transportistas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
