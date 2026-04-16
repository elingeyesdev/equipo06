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
    <div class="mb-4">
        <h1 class="h3 mb-2 d-flex align-items-center gap-2">
            <span aria-hidden="true">📦</span> Crear lote
        </h1>
        <p class="text-muted mb-0">
            Un lote agrupa productos del <strong>mismo productor</strong>, cosechados el <strong>mismo día</strong>, con características similares (tipo y calidad indicadas en el formulario).
        </p>
    </div>

    @if ($productos->isEmpty())
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>
                <strong>No hay productos disponibles para lote.</strong> Debe existir al menos un producto activo que <strong>no</strong> esté ya en otro lote.
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
                            <div id="codigoHelp" class="form-text">Se generará al guardar (vista previa; el número puede variar si otro usuario crea un lote antes).</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                <i class="bi bi-flag text-primary me-1"></i>Estado
                            </label>
                            <input type="text" class="form-control form-control-lg" value="Activo" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="productor_id" class="form-label fw-medium">
                                <i class="bi bi-person-badge text-primary me-1"></i>Productor del lote *
                            </label>
                            <select id="productor_id" name="productor_id" class="form-select form-select-lg @error('productor_id') is-invalid @enderror" required>
                                <option value="" disabled {{ old('productor_id') ? '' : 'selected' }}>Selecciona productor…</option>
                                @foreach ($productores as $pr)
                                    <option value="{{ $pr->id }}" @selected((string) old('productor_id') === (string) $pr->id)>{{ $pr->full_name }}</option>
                                @endforeach
                            </select>
                            @error('productor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_cosecha" class="form-label fw-medium">
                                <i class="bi bi-calendar3 text-primary me-1"></i>Fecha de cosecha *
                            </label>
                            <input type="date" id="fecha_cosecha" name="fecha_cosecha" required
                                   class="form-control form-control-lg @error('fecha_cosecha') is-invalid @enderror"
                                   value="{{ old('fecha_cosecha', now()->format('Y-m-d')) }}">
                            @error('fecha_cosecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nombre_lote" class="form-label fw-medium">
                                <i class="bi bi-bookmark text-primary me-1"></i>Nombre del lote <span class="text-muted fw-normal">(opcional)</span>
                            </label>
                            <input type="text" id="nombre_lote" name="nombre_lote" maxlength="120"
                                   class="form-control @error('nombre_lote') is-invalid @enderror"
                                   value="{{ old('nombre_lote') }}"
                                   placeholder="Ej: Tomates primera calidad 10 abril">
                            @error('nombre_lote')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tipo_producto" class="form-label fw-medium">
                                <i class="bi bi-grid-3x2-gap text-primary me-1"></i>Tipo de producto (característica del lote) *
                            </label>
                            <select id="tipo_producto" name="tipo_producto" class="form-select form-select-lg @error('tipo_producto') is-invalid @enderror" required>
                                <option value="" disabled {{ old('tipo_producto') ? '' : 'selected' }}>Selecciona tipo…</option>
                                @foreach ($tiposProducto as $valor => $etiqueta)
                                    <option value="{{ $valor }}" @selected(old('tipo_producto') === $valor)>{{ $etiqueta }}</option>
                                @endforeach
                            </select>
                            @error('tipo_producto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="cantidad" class="form-label fw-medium">
                                <i class="bi bi-boxes text-primary me-1"></i>Cantidad total del lote *
                            </label>
                            <input type="number" step="0.001" min="0.001" id="cantidad" name="cantidad" required
                                   class="form-control form-control-lg @error('cantidad') is-invalid @enderror"
                                   value="{{ old('cantidad') }}"
                                   placeholder="Ej: peso o unidades según su criterio interno">
                            @error('cantidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Valor de referencia para el conjunto (kg, cajas, etc. según acuerdo interno).</div>
                        </div>

                        <div class="col-12">
                            <label for="descripcion" class="form-label fw-medium">
                                <i class="bi bi-chat-left-text text-primary me-1"></i>Descripción <span class="text-muted fw-normal">(calidad, tamaño, madurez…)</span>
                            </label>
                            <textarea id="descripcion" name="descripcion" rows="2" maxlength="5000"
                                      class="form-control @error('descripcion') is-invalid @enderror"
                                      placeholder="Ej: madurez uniforme, calibre medio">{{ old('descripcion') }}</textarea>
                            @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label for="productos" class="form-label fw-medium">
                                <i class="bi bi-box-seam text-primary me-1"></i>Productos incluidos * <span class="text-muted fw-normal">(Ctrl/Cmd + clic)</span>
                            </label>
                            <select id="productos" name="productos[]" class="form-select form-select-lg @error('productos') is-invalid @enderror" multiple size="10" required>
                                @foreach ($productos as $p)
                                    <option value="{{ $p->id }}" @selected(collect(old('productos', []))->contains((string) $p->id))>
                                        {{ $p->etiquetaNombreYProductor() }} ({{ $p->etiquetaTipo() }})
                                    </option>
                                @endforeach
                            </select>
                            @error('productos')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <div class="hint-lote">
                                <i class="bi bi-info-circle me-1"></i>
                                Solo aparecen productos <strong>sin lote</strong>. Deben pertenecer al <strong>mismo productor</strong> que seleccionó arriba (se valida al guardar).
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
