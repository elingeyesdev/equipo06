@extends('layouts.app')

@section('title', 'Crear lote')

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
    .hint-multiselect {
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
    <div class="mb-4">
        <h1 class="h3 mb-2 d-flex align-items-center gap-2">
            <span aria-hidden="true">📦</span> Crear lote
        </h1>
        <p class="text-muted mb-0">El código se asigna automáticamente al guardar. Selecciona uno o más productos activos.</p>
    </div>

    @if ($productos->isEmpty())
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>
                <strong>No hay productos activos.</strong> Registra productos agrícolas antes de crear un lote.
                <div class="mt-2">
                    <a href="{{ route('productos.create') }}" class="btn btn-primary btn-sm">Ir a productos</a>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-lg-5">
                <form action="{{ route('lotes.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="codigo_lote" class="form-label fw-medium">
                                <i class="bi bi-upc-scan text-primary me-1"></i>Código del lote
                            </label>
                            <input type="text" id="codigo_lote" class="form-control form-control-lg codigo-readonly"
                                   value="{{ $codigoPreview }}" readonly aria-describedby="codigoHelp">
                            <div id="codigoHelp" class="form-text">Se generará al guardar (vista previa: puede cambiar si otro lote se crea antes).</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                <i class="bi bi-flag text-primary me-1"></i>Estado
                            </label>
                            <input type="text" class="form-control form-control-lg" value="Activo" readonly>
                            <div class="form-text">Para este módulo, todos los lotes se crean como activos.</div>
                        </div>

                        <div class="col-12">
                            <label for="productos" class="form-label fw-medium">
                                <i class="bi bi-box-seam text-primary me-1"></i>Productos * <span class="text-muted fw-normal">(mantén Ctrl o Cmd para varios)</span>
                            </label>
                            <select id="productos" name="productos[]" class="form-select form-select-lg @error('productos') is-invalid @enderror" multiple size="10" required>
                                @foreach ($productos as $p)
                                    <option value="{{ $p->id }}" @selected(collect(old('productos', []))->contains((string) $p->id))>
                                        {{ $p->nombre }} — {{ $p->productor->full_name ?? 'Sin productor' }} ({{ $p->etiquetaTipo() }})
                                    </option>
                                @endforeach
                            </select>
                            @error('productos')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <div class="hint-multiselect">
                                <i class="bi bi-info-circle me-1"></i>
                                Cada producto puede pertenecer a varios lotes; dentro de un mismo lote no se repite el producto.
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-lote-main d-inline-flex align-items-center gap-2">
                            <i class="bi bi-check2-circle"></i> Guardar lote
                        </button>
                        <a href="{{ route('lotes.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
