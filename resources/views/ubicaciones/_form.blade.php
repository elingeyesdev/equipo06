@csrf

<div class="row g-4">
    <div class="col-md-8">
        <label for="nombre_ubicacion" class="form-label fw-medium">
            <i class="bi bi-geo text-primary me-1"></i>Nombre de ubicación *
        </label>
        <input id="nombre_ubicacion" name="nombre_ubicacion" type="text"
               class="form-control form-control-lg @error('nombre_ubicacion') is-invalid @enderror"
               value="{{ old('nombre_ubicacion', $ubicacion->nombre_ubicacion ?? '') }}"
               placeholder="Ej: Centro de acopio Santa Cruz"
               required maxlength="160">
        @error('nombre_ubicacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="tipo" class="form-label fw-medium">
            <i class="bi bi-signpost text-primary me-1"></i>Tipo *
        </label>
        <select id="tipo" name="tipo" class="form-select form-select-lg @error('tipo') is-invalid @enderror" required>
            <option value="" disabled {{ old('tipo', $ubicacion->tipo ?? '') === '' ? 'selected' : '' }}>Selecciona tipo…</option>
            @foreach ($tipos as $valor => $etiqueta)
                <option value="{{ $valor }}" @selected(old('tipo', $ubicacion->tipo ?? '') === $valor)>{{ $etiqueta }}</option>
            @endforeach
        </select>
        @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label for="direccion" class="form-label fw-medium">
            <i class="bi bi-pin-map text-primary me-1"></i>Dirección *
        </label>
        <input id="direccion" name="direccion" type="text"
               class="form-control @error('direccion') is-invalid @enderror"
               value="{{ old('direccion', $ubicacion->direccion ?? '') }}"
               placeholder="Dirección completa o referencia para ubicar el punto"
               maxlength="255" required>
        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-medium d-flex align-items-center gap-2">
            <i class="bi bi-map text-primary"></i>Mapa <span class="text-muted fw-normal">(clic para colocar o mover el pin)</span>
        </label>
        <p class="small text-muted mb-2">Centrado en Santa Cruz, Bolivia. Cada clic actualiza latitud y longitud.</p>
        <div class="map-ubicacion-wrap mb-1">
            <div id="map-picker-ubicacion" role="application" aria-label="Mapa para elegir coordenadas"></div>
        </div>
    </div>

    <div class="col-md-6">
        <label for="latitud" class="form-label fw-medium">
            <i class="bi bi-compass text-primary me-1"></i>Latitud <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <input id="latitud" name="latitud" type="text" inputmode="decimal"
               class="form-control @error('latitud') is-invalid @enderror"
               value="{{ old('latitud', $ubicacion->latitud !== null ? (string) $ubicacion->latitud : '') }}"
               placeholder="Ej: -17.783327">
        @error('latitud')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="longitud" class="form-label fw-medium">
            <i class="bi bi-compass text-primary me-1"></i>Longitud <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <input id="longitud" name="longitud" type="text" inputmode="decimal"
               class="form-control @error('longitud') is-invalid @enderror"
               value="{{ old('longitud', $ubicacion->longitud !== null ? (string) $ubicacion->longitud : '') }}"
               placeholder="Ej: -63.182140">
        @error('longitud')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label for="descripcion" class="form-label fw-medium">
            <i class="bi bi-chat-left-text text-primary me-1"></i>Descripción <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <textarea id="descripcion" name="descripcion" rows="3" maxlength="5000"
                  class="form-control @error('descripcion') is-invalid @enderror"
                  placeholder="Notas de acceso, horario, contacto en sitio…">{{ old('descripcion', $ubicacion->descripcion ?? '') }}</textarea>
        @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
