@csrf

<div class="row g-3">
    <div class="col-md-8">
        <label for="full_name" class="form-label">Nombre completo *</label>
        <input id="full_name" name="full_name" type="text" value="{{ old('full_name', $producer->full_name ?? '') }}" required class="form-control @error('full_name') is-invalid @enderror">
        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="document_number" class="form-label">Documento</label>
        <input id="document_number" name="document_number" type="text" value="{{ old('document_number', $producer->document_number ?? '') }}" class="form-control @error('document_number') is-invalid @enderror">
        @error('document_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="phone" class="form-label">Teléfono</label>
        <input id="phone" name="phone" type="text" value="{{ old('phone', $producer->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Correo</label>
        <input id="email" name="email" type="email" value="{{ old('email', $producer->email ?? '') }}" class="form-control @error('email') is-invalid @enderror">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label for="address" class="form-label">Dirección</label>
        <input id="address" name="address" type="text" value="{{ old('address', $producer->address ?? '') }}" class="form-control @error('address') is-invalid @enderror">
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $producer->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Productor activo</label>
        </div>
        @error('is_active')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
