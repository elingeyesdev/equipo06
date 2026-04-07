@extends('layouts.app')

@section('title', 'Gestión de Productores')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">ENT_1.1 Gestión de Productores</h1>
            <p class="text-muted mb-0">Registro, edición, consulta y eliminación de productores.</p>
        </div>
        <a href="{{ route('productores.create') }}" class="btn btn-success">+ Registrar productor</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($producers as $producer)
                            <tr>
                                <td>{{ $producer->id }}</td>
                                <td class="fw-semibold">{{ $producer->full_name }}</td>
                                <td>{{ $producer->document_number ?? '-' }}</td>
                                <td>{{ $producer->phone ?? '-' }}</td>
                                <td>{{ $producer->email ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $producer->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ $producer->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('productores.show', $producer) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                                    <a href="{{ route('productores.edit', $producer) }}" class="btn btn-sm btn-outline-warning">Editar</a>
                                    <form action="{{ route('productores.destroy', $producer) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar este productor?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Aún no hay productores registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $producers->links() }}
    </div>
@endsection
