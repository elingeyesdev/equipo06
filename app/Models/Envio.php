<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Envio extends Model
{
    use HasFactory;

    protected $table = 'envios';

    protected $fillable = [
        'codigo',
        'origen',
        'destino',
        'estado',
        'fecha_creacion',
        'fecha_programada',
        'observaciones',
        'ubicacion_actual_id',
        'transportista_id',
    ];

    /**
     * Estados del ciclo de vida del envío (valores en base de datos).
     *
     * @return array<string, string>
     */
    public static function estadosDisponibles(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'asignado' => 'Asignado a transporte',
            'en_transito' => 'En tránsito',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_creacion' => 'date',
            'fecha_programada' => 'date',
        ];
    }

    public function etiquetaEstado(): string
    {
        return self::estadosDisponibles()[$this->estado] ?? ucfirst((string) $this->estado);
    }

    public function badgeEstadoClass(): string
    {
        return match ($this->estado) {
            'pendiente' => 'text-bg-warning text-dark',
            'asignado' => 'text-bg-primary',
            'en_transito' => 'text-bg-info text-dark',
            'entregado' => 'text-bg-success',
            'cancelado' => 'text-bg-secondary',
            default => 'text-bg-light text-dark border',
        };
    }

    /**
     * Mensaje MVP según ubicación actual (tipo de punto) y estado del envío. Sin GPS ni distancias.
     */
    public function etiquetaValidacionLlegada(): string
    {
        if ($this->estado === 'cancelado') {
            return 'Envío cancelado';
        }
        if ($this->estado === 'entregado') {
            return 'Llegó a destino final';
        }
        if (! $this->ubicacion_actual_id) {
            return 'En tránsito';
        }
        if (! $this->relationLoaded('ubicacionActual')) {
            $this->load('ubicacionActual');
        }
        $tipo = $this->ubicacionActual?->tipo;

        return match ($tipo) {
            'destino' => 'Llegó a destino final',
            'punto_control' => 'Llegó a punto intermedio',
            'origen' => 'Ubicación en origen',
            default => 'En tránsito',
        };
    }

    public function badgeValidacionLlegadaClass(): string
    {
        if ($this->estado === 'cancelado') {
            return 'text-bg-secondary';
        }
        if ($this->estado === 'entregado') {
            return 'text-bg-success';
        }
        if (! $this->ubicacion_actual_id) {
            return 'text-bg-warning text-dark';
        }
        if (! $this->relationLoaded('ubicacionActual')) {
            $this->load('ubicacionActual');
        }

        return match ($this->ubicacionActual?->tipo) {
            'destino' => 'text-bg-success',
            'punto_control' => 'text-bg-info text-dark',
            'origen' => 'text-bg-primary',
            default => 'text-bg-light text-dark border',
        };
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleEnvio::class, 'envio_id');
    }

    /**
     * Punto geográfico registrado como posición actual del envío (base para seguimiento logístico).
     */
    public function ubicacionActual(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_actual_id');
    }

    /**
     * Responsable de transporte asignado de forma directa al envío (MVP ENT 2.3).
     */
    public function transportista(): BelongsTo
    {
        return $this->belongsTo(Transportista::class, 'transportista_id');
    }

    /**
     * Registro de recepción en destino (ENT 2.4, relación 1:1).
     */
    public function recepcion(): HasOne
    {
        return $this->hasOne(Recepcion::class, 'envio_id');
    }

    /**
     * Asignación vigente (la más reciente por fecha).
     */
    public function asignacionActual(): HasOne
    {
        return $this->hasOne(AsignacionEnvio::class, 'envio_id')
            ->latestOfMany('fecha_asignacion');
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignacionEnvio::class, 'envio_id')
            ->orderByDesc('fecha_asignacion');
    }

    /**
     * Puntos geográficos de ruta / seguimiento asociados al envío (ENT 2.2).
     */
    public function ubicaciones(): HasMany
    {
        return $this->hasMany(Ubicacion::class, 'envio_id');
    }

    /**
     * Rutas planificadas o de seguimiento vinculadas al envío (ENT 2.2).
     */
    public function rutas(): HasMany
    {
        return $this->hasMany(Ruta::class, 'envio_id');
    }

    /**
     * Vista previa del siguiente código de guía (sin bloqueo). Formato: GUIA-2026-0001
     */
    public static function previewSiguienteCodigoGuia(): string
    {
        return self::siguienteCodigoGuia(lockForUpdate: false);
    }

    /**
     * Siguiente código consecutivo por año. Con lockForUpdate debe llamarse dentro de DB::transaction.
     */
    public static function siguienteCodigoGuia(bool $lockForUpdate = false): string
    {
        $year = now()->year;
        $prefix = 'GUIA-'.$year.'-';
        $prefixLen = strlen($prefix);

        $query = static::query()->where('codigo', 'like', $prefix.'%');

        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        $maxNum = (int) $query->get(['codigo'])
            ->map(function (self $row) use ($prefix, $prefixLen) {
                $code = $row->codigo;
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
