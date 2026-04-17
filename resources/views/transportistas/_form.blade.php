@csrf

<div class="row g-4">
    <div class="col-md-6">
        <label for="nombre" class="form-label fw-medium">
            <i class="bi bi-person text-primary me-1"></i>Nombre *
        </label>
        <input id="nombre" name="nombre" type="text"
               class="form-control form-control-lg @error('nombre') is-invalid @enderror"
               value="{{ old('nombre', $transportista->nombre ?? '') }}"
               placeholder="Ej: Juan"
               required>
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="apellido" class="form-label fw-medium">
            <i class="bi bi-person text-primary me-1"></i>Apellido *
        </label>
        <input id="apellido" name="apellido" type="text"
               class="form-control form-control-lg @error('apellido') is-invalid @enderror"
               value="{{ old('apellido', $transportista->apellido ?? '') }}"
               placeholder="Ej: Pérez"
               required>
        @error('apellido')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="carnet_identidad" class="form-label fw-medium">
            <i class="bi bi-person-vcard text-primary me-1"></i>Carnet de Identidad *
        </label>
        <input id="carnet_identidad" name="carnet_identidad" type="text"
               class="form-control form-control-lg @error('carnet_identidad') is-invalid @enderror"
               value="{{ old('carnet_identidad', $transportista->carnet_identidad ?? '') }}"
               placeholder="Ej: 12345678 o 1234567LP"
               required>
        @error('carnet_identidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="telefono" class="form-label fw-medium">
            <i class="bi bi-telephone text-primary me-1"></i>Teléfono *
        </label>
        <input id="telefono" name="telefono" type="text"
               class="form-control form-control-lg @error('telefono') is-invalid @enderror"
               value="{{ old('telefono', $transportista->telefono ?? '') }}"
               placeholder="+591XXXXXXXX"
               required>
        <div class="form-text">Formato Bolivia: +591 seguido de 8 dígitos.</div>
        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="licencia" class="form-label fw-medium">
            <i class="bi bi-card-checklist text-primary me-1"></i>Licencia *
        </label>
        <input id="licencia" name="licencia" type="text"
               class="form-control form-control-lg @error('licencia') is-invalid @enderror"
               value="{{ old('licencia', $transportista->licencia ?? '') }}"
               placeholder="Ej: LIC-2026-001"
               required>
        @error('licencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="tipo_licencia" class="form-label fw-medium">
            <i class="bi bi-truck-front text-primary me-1"></i>Tipo de licencia *
        </label>
        <input id="tipo_licencia" name="tipo_licencia" type="text"
               class="form-control form-control-lg @error('tipo_licencia') is-invalid @enderror"
               value="{{ old('tipo_licencia', $transportista->tipo_licencia ?? '') }}"
               placeholder="Ej: B"
               required>
        @error('tipo_licencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="fecha_vencimiento_licencia" class="form-label fw-medium">
            <i class="bi bi-calendar-event text-primary me-1"></i>Vencimiento licencia
        </label>
        <input id="fecha_vencimiento_licencia" name="fecha_vencimiento_licencia" type="date"
               class="form-control form-control-lg @error('fecha_vencimiento_licencia') is-invalid @enderror"
               value="{{ old('fecha_vencimiento_licencia', isset($transportista->fecha_vencimiento_licencia) ? optional($transportista->fecha_vencimiento_licencia)->format('Y-m-d') : '') }}">
        @error('fecha_vencimiento_licencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="estado" class="form-label fw-medium">
            <i class="bi bi-toggle-on text-primary me-1"></i>Estado *
        </label>
        <select id="estado" name="estado" class="form-select form-select-lg @error('estado') is-invalid @enderror" required>
            @foreach ($estados as $valor => $etiqueta)
                <option value="{{ $valor }}" @selected(old('estado', $transportista->estado ?? 'activo') === $valor)>{{ $etiqueta }}</option>
            @endforeach
        </select>
        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
