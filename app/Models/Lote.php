<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes';

    protected $fillable = [
        'codigo_lote',
        'nombre_lote',
        'fecha_cosecha',
        'productor_id',
        'descripcion',
        'cantidad',
        'tipo_producto',
        'estado',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_cosecha' => 'date',
            'cantidad' => 'decimal:3',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function estadosDisponibles(): array
    {
        return [
            'activo' => 'Activo',
            'cerrado' => 'Cerrado',
            'anulado' => 'Anulado',
        ];
    }

    public function etiquetaEstado(): string
    {
        return self::estadosDisponibles()[$this->estado] ?? ucfirst((string) $this->estado);
    }

    public function badgeEstadoClass(): string
    {
        return match ($this->estado) {
            'activo' => 'text-bg-success',
            'cerrado' => 'text-bg-secondary',
            'anulado' => 'text-bg-danger',
            default => 'text-bg-light text-dark border',
        };
    }

    /**
     * Título legible para listados (ej. código + tipo + fecha + productor).
     */
    public function tituloLinea(): string
    {
        $tipo = $this->etiquetaTipoProducto();
        $fecha = $this->fecha_cosecha?->format('d/m/Y') ?? '—';
        $prod = $this->relationLoaded('productor')
            ? ($this->productor?->full_name ?? '—')
            : ($this->productor()->value('full_name') ?? '—');

        return $this->codigo_lote.' - '.$tipo.' - '.$fecha.' - '.$prod;
    }

    public function etiquetaTipoProducto(): string
    {
        return Producto::tiposDisponibles()[$this->tipo_producto] ?? ucfirst((string) $this->tipo_producto);
    }

    public function productor(): BelongsTo
    {
        return $this->belongsTo(Producer::class, 'productor_id');
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'lote_id');
    }

    public static function previewSiguienteCodigo(): string
    {
        return self::siguienteCodigoLote(lockForUpdate: false);
    }

    public static function siguienteCodigoLote(bool $lockForUpdate = false): string
    {
        $year = now()->year;
        $prefix = 'LOTE-'.$year.'-';
        $prefixLen = strlen($prefix);

        $query = static::query()->where('codigo_lote', 'like', $prefix.'%');

        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        $maxNum = (int) $query->get(['codigo_lote'])
            ->map(function (self $row) use ($prefix, $prefixLen) {
                $code = $row->codigo_lote;
                if (! is_string($code) || ! str_starts_with($code, $prefix)) {
                    return 0;
                }
                $suffix = substr($code, $prefixLen);

                return ctype_digit($suffix) ? (int) $suffix : 0;
            })
            ->max();

        $next = $maxNum + 1;

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
