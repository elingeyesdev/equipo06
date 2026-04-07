@extends('layouts.app')

@section('title', 'Editar productor')

@section('content')
    <h1 class="h3 mb-3">Editar productor</h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('productores.update', $producer) }}" method="POST">
                @method('PUT')
                @include('productores._form')
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                    <a href="{{ route('productores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
