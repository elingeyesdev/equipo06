@csrf

<div class="row g-4">
    <div class="col-md-8">
        <label for="nombre" class="form-label fw-medium">
            <i class="bi bi-tag text-primary me-1"></i>Nombre del producto *
        </label>
        <input id="nombre" name="nombre" type="text"
               class="form-control form-control-lg @error('nombre') is-invalid @enderror"
               value="{{ old('nombre', $producto->nombre ?? '') }}"
               placeholder="Ej: Papa amarilla orgánica"
               required>
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="tipo" class="form-label fw-medium">
            <i class="bi bi-grid-3x2-gap text-primary me-1"></i>Tipo *
        </label>
        <select id="tipo" name="tipo" class="form-select form-select-lg @error('tipo') is-invalid @enderror" required>
            <option value="" disabled {{ old('tipo', $producto->tipo ?? '') === '' ? 'selected' : '' }}>Selecciona un tipo…</option>
            @foreach ($tipos as $valor => $etiqueta)
                <option value="{{ $valor }}" @selected(old('tipo', $producto->tipo ?? '') === $valor)>{{ $etiqueta }}</option>
            @endforeach
        </select>
        @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label for="descripcion" class="form-label fw-medium">
            <i class="bi bi-text-paragraph text-primary me-1"></i>Descripción <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <textarea id="descripcion" name="descripcion" rows="3"
                  class="form-control @error('descripcion') is-invalid @enderror"
                  placeholder="Variedad, origen esperado, notas de calidad…">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
        @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
        <label for="productor_id" class="form-label fw-medium">
            <i class="bi bi-person-badge text-primary me-1"></i>Productor *
        </label>
        <select id="productor_id" name="productor_id" class="form-select form-select-lg @error('productor_id') is-invalid @enderror" required>
            <option value="" disabled {{ old('productor_id', $producto->productor_id ?? '') === '' ? 'selected' : '' }}>Elige al productor responsable…</option>
            @foreach ($productores as $p)
                <option value="{{ $p->id }}" @selected((string) old('productor_id', $producto->productor_id ?? '') === (string) $p->id)>
                    {{ $p->full_name }}
                </option>
            @endforeach
        </select>
        @error('productor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1"
                   {{ old('activo', ($producto->activo ?? true) ? '1' : '0') === '1' ? 'checked' : '' }}>
            <label class="form-check-label fw-medium" for="activo">Producto activo</label>
        </div>
        @error('activo')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
