<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes';

    protected $fillable = [
        'codigo_lote',
        'fecha_creacion',
        'estado',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_creacion' => 'date',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function estadosDisponibles(): array
    {
        return [
            'activo' => 'Activo',
        ];
    }

    public function etiquetaEstado(): string
    {
        return self::estadosDisponibles()[$this->estado] ?? ucfirst($this->estado);
    }

    public function badgeEstadoClass(): string
    {
        return match ($this->estado) {
            'activo' => 'text-bg-success',
            default => 'text-bg-light text-dark border',
        };
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'lote_producto', 'lote_id', 'producto_id');
    }

    /**
     * Vista previa del siguiente código (sin bloqueo). Formato: LOTE-2026-0001
     */
    public static function previewSiguienteCodigo(): string
    {
        return self::siguienteCodigoLote(lockForUpdate: false);
    }

    /**
     * Siguiente código consecutivo por año. Con lockForUpdate debe llamarse dentro de DB::transaction.
     */
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
