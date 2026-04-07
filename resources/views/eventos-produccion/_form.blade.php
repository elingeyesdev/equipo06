@php
    /** @var \App\Models\EventoProduccion $evento */
    $mode = $mode ?? 'create';
@endphp

<form action="{{ $mode === 'edit' ? route('eventos-produccion.update', $evento) : route('eventos-produccion.store') }}" method="POST">
    @csrf
    @if ($mode === 'edit')
        @method('PUT')
    @endif

    <div class="row g-4">
        <div class="col-md-6">
            <label for="producto_id" class="form-label fw-medium">
                <i class="bi bi-box-seam text-primary me-1"></i>Producto *
            </label>
            <select id="producto_id" name="producto_id" class="form-select form-select-lg @error('producto_id') is-invalid @enderror" required>
                <option value="" disabled {{ old('producto_id', $evento->producto_id) ? '' : 'selected' }}>Selecciona el producto…</option>
                @foreach ($productos as $p)
                    <option value="{{ $p->id }}" @selected((string) old('producto_id', $evento->producto_id) === (string) $p->id)>
                        {{ $p->nombre }} — {{ $p->productor->full_name ?? 'Sin productor' }}
                    </option>
                @endforeach
            </select>
            @error('producto_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="fecha" class="form-label fw-medium">
                <i class="bi bi-calendar3 text-primary me-1"></i>Fecha del hecho *
            </label>
            <input type="date" id="fecha" name="fecha" class="form-control form-control-lg @error('fecha') is-invalid @enderror"
                   value="{{ old('fecha', $evento->fecha?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
            @error('fecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="inicia_en" class="form-label fw-medium">
                <i class="bi bi-alarm text-primary me-1"></i>Inicio programado <span class="text-muted fw-normal">(opcional)</span>
            </label>
            <input type="datetime-local" id="inicia_en" name="inicia_en" step="60"
                   class="form-control form-control-lg @error('inicia_en') is-invalid @enderror"
                   value="{{ old('inicia_en', $evento->inicia_en?->format('Y-m-d\TH:i')) }}">
            @error('inicia_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">
                Si el estado guardado es <strong>Pendiente</strong> y defines aquí una fecha y hora, el listado pasará a mostrar <strong>En proceso</strong> al llegar ese momento.
                Puedes marcar <strong>En proceso</strong> o <strong>Completado</strong> manualmente cuando quieras.
            </div>
        </div>

        <div class="col-md-6">
            <label for="etapa" class="form-label fw-medium">
                <i class="bi bi-diagram-3 text-primary me-1"></i>Etapa *
            </label>
            <select id="etapa" name="etapa" class="form-select form-select-lg @error('etapa') is-invalid @enderror" required>
                <option value="" disabled {{ old('etapa', $evento->etapa) ? '' : 'selected' }}>Tipo de etapa…</option>
                @foreach ($etapas as $valor => $etiqueta)
                    <option value="{{ $valor }}" @selected(old('etapa', $evento->etapa) === $valor)>{{ $etiqueta }}</option>
                @endforeach
            </select>
            @error('etapa')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="hint-etapas">
                <strong>Ciclo típico:</strong>
                🌱 <em>Siembra</em> (inicio) → 🌿 <em>Cultivo</em> (manejo en campo) → 🌾 <em>Cosecha</em> (recolección).
            </div>
        </div>

        <div class="col-md-6">
            <label for="estado" class="form-label fw-medium">
                <i class="bi bi-flag text-primary me-1"></i>Estado *
            </label>
            <select id="estado" name="estado" class="form-select form-select-lg @error('estado') is-invalid @enderror" required>
                @foreach ($estados as $valor => $etiqueta)
                    <option value="{{ $valor }}" @selected(old('estado', $evento->estado ?? 'pendiente') === $valor)>{{ $etiqueta }}</option>
                @endforeach
            </select>
            @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label for="descripcion" class="form-label fw-medium">
                <i class="bi bi-text-paragraph text-primary me-1"></i>Descripción <span class="text-muted fw-normal">(opcional)</span>
            </label>
            <textarea id="descripcion" name="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror"
                      placeholder="Lote, observaciones del campo, condiciones climáticas…">{{ old('descripcion', $evento->descripcion) }}</textarea>
            @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <hr class="my-4 opacity-25">
    <div class="d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-process-main d-inline-flex align-items-center gap-2">
            <i class="bi bi-check2-circle"></i> {{ $mode === 'edit' ? 'Guardar cambios' : 'Guardar evento' }}
        </button>
        <a href="{{ route('eventos-produccion.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
    </div>
</form>
