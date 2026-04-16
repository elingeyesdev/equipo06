@php
    $esEdicion = ($modo ?? 'create') === 'edit';
    $estadosLote = \App\Models\Lote::estadosDisponibles();
    $idsProductosLote = $esEdicion && isset($lote)
        ? $lote->productos->pluck('id')->map(fn ($id) => (string) $id)->all()
        : [];
    $oldProductos = collect(old('productos', $esEdicion ? $idsProductosLote : []))->map(fn ($id) => (string) $id)->all();
@endphp

<div class="row g-4">
    <div class="col-md-6">
        <label for="codigo_lote" class="form-label fw-medium">
            <i class="bi bi-upc-scan text-primary me-1"></i>Código del lote
        </label>
        @if (! $esEdicion)
            <input type="text" id="codigo_lote" class="form-control form-control-lg codigo-readonly"
                   value="{{ $codigoPreview }}" readonly aria-describedby="codigoHelp">
            <div id="codigoHelp" class="form-text">Se generará al guardar (vista previa; el número puede variar si otro usuario crea un lote antes).</div>
        @else
            <input type="text" id="codigo_lote" class="form-control form-control-lg codigo-readonly"
                   value="{{ $lote->codigo_lote }}" readonly aria-describedby="codigoHelpEdit">
            <div id="codigoHelpEdit" class="form-text">El código no se modifica una vez creado el lote.</div>
        @endif
    </div>

    <div class="col-md-6">
        <label for="estado" class="form-label fw-medium">
            <i class="bi bi-flag text-primary me-1"></i>Estado *
        </label>
        <select id="estado" name="estado" class="form-select form-select-lg @error('estado') is-invalid @enderror" required>
            @foreach ($estadosLote as $valor => $etiqueta)
                <option value="{{ $valor }}" @selected(old('estado', $esEdicion ? $lote->estado : 'activo') === $valor)>{{ $etiqueta }}</option>
            @endforeach
        </select>
        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="productor_id" class="form-label fw-medium">
            <i class="bi bi-person-badge text-primary me-1"></i>Productor del lote *
        </label>
        <select id="productor_id" name="productor_id" class="form-select form-select-lg @error('productor_id') is-invalid @enderror" required>
            <option value="" disabled @selected(old('productor_id', $esEdicion ? (string) $lote->productor_id : '') === '')>Selecciona productor…</option>
            @foreach ($productores as $pr)
                <option value="{{ $pr->id }}" @selected((string) old('productor_id', $esEdicion ? $lote->productor_id : '') === (string) $pr->id)>{{ $pr->full_name }}</option>
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
               value="{{ old('fecha_cosecha', $esEdicion ? optional($lote->fecha_cosecha)->format('Y-m-d') : now()->format('Y-m-d')) }}">
        @error('fecha_cosecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="nombre_lote" class="form-label fw-medium">
            <i class="bi bi-bookmark text-primary me-1"></i>Nombre del lote <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <input type="text" id="nombre_lote" name="nombre_lote" maxlength="120"
               class="form-control @error('nombre_lote') is-invalid @enderror"
               value="{{ old('nombre_lote', $esEdicion ? $lote->nombre_lote : '') }}"
               placeholder="Ej: Tomates primera calidad 10 abril">
        @error('nombre_lote')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="tipo_producto" class="form-label fw-medium">
            <i class="bi bi-grid-3x2-gap text-primary me-1"></i>Tipo de producto (característica del lote) *
        </label>
        <select id="tipo_producto" name="tipo_producto" class="form-select form-select-lg @error('tipo_producto') is-invalid @enderror" required>
            <option value="" disabled @selected(old('tipo_producto', $esEdicion ? $lote->tipo_producto : '') === '')>Selecciona tipo…</option>
            @foreach ($tiposProducto as $valor => $etiqueta)
                <option value="{{ $valor }}" @selected(old('tipo_producto', $esEdicion ? $lote->tipo_producto : '') === $valor)>{{ $etiqueta }}</option>
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
               value="{{ old('cantidad', $esEdicion ? $lote->cantidad : '') }}"
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
                  placeholder="Ej: madurez uniforme, calibre medio">{{ old('descripcion', $esEdicion ? $lote->descripcion : '') }}</textarea>
        @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label for="productos" class="form-label fw-medium">
            <i class="bi bi-box-seam text-primary me-1"></i>Productos incluidos * <span class="text-muted fw-normal">(Ctrl/Cmd + clic)</span>
        </label>
        <select id="productos" name="productos[]" class="form-select form-select-lg @error('productos') is-invalid @enderror" multiple size="10" required>
            @foreach ($productos as $p)
                <option value="{{ $p->id }}" @selected(in_array((string) $p->id, $oldProductos, true))>
                    {{ $p->etiquetaNombreYProductor() }} ({{ $p->etiquetaTipo() }})
                </option>
            @endforeach
        </select>
        @error('productos')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        <div class="hint-lote">
            <i class="bi bi-info-circle me-1"></i>
            @if (! $esEdicion)
                Solo aparecen productos <strong>sin lote</strong>. Deben pertenecer al <strong>mismo productor</strong> que seleccionó arriba y el <strong>mismo tipo</strong> que el lote (se valida al guardar).
            @else
                Puede mantener o cambiar la selección. Deben ser del <strong>mismo productor</strong> y <strong>tipo</strong> del lote; los que quite quedarán sin lote.
            @endif
        </div>
    </div>
</div>
