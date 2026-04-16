@csrf

<div class="row g-4">
    <div class="col-md-6">
        <label for="origen" class="form-label fw-medium">
            <i class="bi bi-geo-alt text-primary me-1"></i>Origen *
        </label>
        <input id="origen" name="origen" type="text"
               class="form-control form-control-lg @error('origen') is-invalid @enderror"
               value="{{ old('origen', $envio->origen ?? '') }}"
               placeholder="Planta, finca, almacén u origen logístico"
               required>
        @error('origen')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="destino" class="form-label fw-medium">
            <i class="bi bi-geo-alt-fill text-primary me-1"></i>Destino *
        </label>
        <input id="destino" name="destino" type="text"
               class="form-control form-control-lg @error('destino') is-invalid @enderror"
               value="{{ old('destino', $envio->destino ?? '') }}"
               placeholder="Centro de distribución, cliente o punto de entrega"
               required>
        @error('destino')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="estado" class="form-label fw-medium">
            <i class="bi bi-flag text-primary me-1"></i>Estado *
        </label>
        <select id="estado" name="estado" class="form-select form-select-lg @error('estado') is-invalid @enderror" required>
            @foreach ($estados as $valor => $etiqueta)
                <option value="{{ $valor }}" @selected(old('estado', $envio->estado ?? 'pendiente') === $valor)>{{ $etiqueta }}</option>
            @endforeach
        </select>
        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="fecha_creacion" class="form-label fw-medium">
            <i class="bi bi-calendar3 text-primary me-1"></i>Fecha de creación *
        </label>
        <input id="fecha_creacion" name="fecha_creacion" type="date"
               class="form-control form-control-lg @error('fecha_creacion') is-invalid @enderror"
               value="{{ old('fecha_creacion', optional($envio->fecha_creacion)->format('Y-m-d') ?? '') }}"
               required>
        @error('fecha_creacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="fecha_programada" class="form-label fw-medium">
            <i class="bi bi-calendar-event text-primary me-1"></i>Fecha programada <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <input id="fecha_programada" name="fecha_programada" type="date"
               class="form-control form-control-lg @error('fecha_programada') is-invalid @enderror"
               value="{{ old('fecha_programada', optional($envio->fecha_programada)->format('Y-m-d') ?? '') }}">
        @error('fecha_programada')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label for="observaciones" class="form-label fw-medium">
            <i class="bi bi-chat-left-text text-primary me-1"></i>Observaciones <span class="text-muted fw-normal">(opcional)</span>
        </label>
        <textarea id="observaciones" name="observaciones" rows="3"
                  class="form-control @error('observaciones') is-invalid @enderror"
                  placeholder="Instrucciones de entrega, restricciones de horario, contacto en destino…">{{ old('observaciones', $envio->observaciones ?? '') }}</textarea>
        @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
