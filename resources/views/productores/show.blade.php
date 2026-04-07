@extends('layouts.app')

@section('title', 'Detalle del productor')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Detalle del productor</h1>
        <span class="badge {{ $producer->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
            {{ $producer->is_active ? 'Activo' : 'Inactivo' }}
        </span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-2"><strong>ID:</strong></div>
                <div class="col-md-10">{{ $producer->id }}</div>

                <div class="col-md-2"><strong>Nombre:</strong></div>
                <div class="col-md-10">{{ $producer->full_name }}</div>

                <div class="col-md-2"><strong>Documento:</strong></div>
                <div class="col-md-10">{{ $producer->document_number ?? '-' }}</div>

                <div class="col-md-2"><strong>Teléfono:</strong></div>
                <div class="col-md-10">{{ $producer->phone ?? '-' }}</div>

                <div class="col-md-2"><strong>Correo:</strong></div>
                <div class="col-md-10">{{ $producer->email ?? '-' }}</div>

                <div class="col-md-2"><strong>Dirección:</strong></div>
                <div class="col-md-10">{{ $producer->address ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('productores.edit', $producer) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('productores.index') }}" class="btn btn-outline-secondary">Volver al listado</a>
    </div>
@endsection
