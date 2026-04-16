<hr class="my-4 opacity-25">
<h2 class="h6 text-uppercase text-muted mb-3 d-flex align-items-center gap-2">
    <i class="bi bi-box-seam text-primary"></i> Productos en este envío
</h2>
<p class="text-muted small mb-3">Indica la cantidad a transportar por producto. Solo se incluyen filas con cantidad mayor a cero.</p>

@if ($productos->isEmpty())
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-start gap-2">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div>
            <strong>No hay productos disponibles.</strong> Activa o registra productos agrícolas antes de armar el envío.
            <div class="mt-2">
                <a href="{{ route('productos.create') }}" class="btn btn-success btn-sm">Ir a productos</a>
            </div>
        </div>
    </div>
@else
    <div class="table-responsive border rounded-3 overflow-hidden bg-white">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Producto - Productor</th>
                    <th class="text-end pe-3" style="min-width: 9rem;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $p)
                    @php
                        $prev = $cantidadesPrevias[$p->id] ?? null;
                        $oldVal = old('cantidades.'.$p->id, $prev !== null ? (string) $prev : '');
                    @endphp
                    <tr>
                        <td class="ps-3 fw-medium">{{ $p->etiquetaNombreYProductor() }}</td>
                        <td class="text-end pe-3">
                            <input type="number"
                                   name="cantidades[{{ $p->id }}]"
                                   class="form-control form-control-sm text-end @error('cantidades.'.$p->id) is-invalid @enderror"
                                   min="0"
                                   step="0.001"
                                   max="999999"
                                   placeholder="0"
                                   value="{{ $oldVal }}"
                                   aria-label="Cantidad para {{ $p->nombre }}">
                            @error('cantidades.'.$p->id)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
