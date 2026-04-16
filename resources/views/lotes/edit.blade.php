@extends('layouts.app')

@section('title', 'Editar lote '.$lote->codigo_lote)

@push('styles')
<style>
    .btn-lote-main {
        background-color: #3d8b9e;
        border-color: #3d8b9e;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
        box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
    }
    .btn-lote-main:hover { background-color: #327183; border-color: #327183; color: #fff; }
    .codigo-readonly {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 600;
        letter-spacing: 0.03em;
        background: linear-gradient(135deg, #f8fffb 0%, #f0f7f4 100%);
        border: 1px dashed rgba(26, 92, 56, 0.35);
    }
    .hint-lote {
        font-size: 0.875rem;
        border-radius: 0.5rem;
        padding: 0.65rem 1rem;
        margin-top: 0.5rem;
        background: linear-gradient(90deg, #e8f4fc 0%, #eef8f3 100%);
        color: #2d4a5a;
        border: 1px solid rgba(0,0,0,0.06);
    }
</style>
@endpush

@section('content')
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="h3 mb-2 d-flex align-items-center gap-2">
                <span aria-hidden="true">📦</span> Editar lote
            </h1>
            <p class="text-muted mb-0">
                Ajuste cabecera y productos vinculados; la coherencia (productor, tipo y productos) se valida al guardar.
            </p>
        </div>
        <a href="{{ route('lotes.show', $lote) }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i>Volver al detalle
        </a>
    </div>

    @if ($productos->isEmpty())
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>
                <strong>No hay productos disponibles.</strong> No es posible mantener un lote sin ítems. Cree productos activos o libere algunos de otros lotes.
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-lg-5">
                <form action="{{ route('lotes.update', $lote) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('lotes._form', [
                        'modo' => 'edit',
                        'lote' => $lote,
                        'productores' => $productores,
                        'productos' => $productos,
                        'tiposProducto' => $tiposProducto,
                        'codigoPreview' => $lote->codigo_lote,
                    ])

                    <hr class="my-4 opacity-25">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-lote-main d-inline-flex align-items-center gap-2">
                            <i class="bi bi-check2-circle"></i> Guardar cambios
                        </button>
                        <a href="{{ route('lotes.show', $lote) }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
