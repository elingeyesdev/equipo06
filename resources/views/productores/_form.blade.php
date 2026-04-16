@php
    $tiposVia = $tiposVia ?? \App\Models\Producer::tiposVia();
@endphp
@csrf

<div class="row g-4">
    <div class="col-md-8">
        <label for="full_name" class="form-label fw-medium">
            <i class="bi bi-person text-success me-1"></i>Nombre completo *
        </label>
        <input id="full_name" name="full_name" type="text"
               class="form-control form-control-lg @error('full_name') is-invalid @enderror"
               value="{{ old('full_name', $producer->full_name ?? '') }}"
               placeholder="Ej: María López Huamán"
               required>
        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="document_number" class="form-label fw-medium">
            <i class="bi bi-card-text text-success me-1"></i>Carnet de identidad <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <input id="document_number" name="document_number" type="text" inputmode="numeric" pattern="[0-9]*"
               class="form-control form-control-lg @error('document_number') is-invalid @enderror"
               value="{{ old('document_number', $producer->document_number ?? '') }}"
               placeholder="Solo números, 5 a 10 dígitos">
        @error('document_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="phone" class="form-label fw-medium">
            <i class="bi bi-telephone text-success me-1"></i>Teléfono <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <input id="phone" name="phone" type="text"
               class="form-control @error('phone') is-invalid @enderror"
               value="{{ old('phone', $producer->phone ?? '') }}"
               placeholder="+59176045341 o 76045341">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text">Se guardará como +591 y 8 dígitos (Bolivia).</div>
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label fw-medium">
            <i class="bi bi-envelope text-success me-1"></i>Correo <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <input id="email" name="email" type="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $producer->email ?? '') }}"
               placeholder="nombre@ejemplo.com">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="address_type" class="form-label fw-medium">
            <i class="bi bi-signpost text-success me-1"></i>Tipo de vía <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <select id="address_type" name="address_type" class="form-select @error('address_type') is-invalid @enderror">
            <option value="" @selected(old('address_type', $producer->address_type ?? '') === '')>—</option>
            @foreach ($tiposVia as $valor => $etiqueta)
                <option value="{{ $valor }}" @selected(old('address_type', $producer->address_type ?? '') === $valor)>{{ $etiqueta }}</option>
            @endforeach
        </select>
        @error('address_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
        <label for="address_detail" class="form-label fw-medium">
            <i class="bi bi-geo-alt text-success me-1"></i>Nombre de vía y número <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <input id="address_detail" name="address_detail" type="text"
               class="form-control @error('address_detail') is-invalid @enderror"
               value="{{ old('address_detail', $producer->address_detail ?? '') }}"
               placeholder="Ej: Banzer #123 (junto al tipo queda: Avenida Banzer #123)">
        @error('address_detail')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                   {{ old('is_active', ($producer->is_active ?? true) ? '1' : '0') === '1' ? 'checked' : '' }}>
            <label class="form-check-label fw-medium" for="is_active">Productor activo en el sistema</label>
        </div>
        @error('is_active')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
