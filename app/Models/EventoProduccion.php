<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class EventoProduccion extends Model
{
    use HasFactory;

    protected $table = 'eventos_produccion';

    protected $fillable = [
        'producto_id',
        'etapa',
        'fecha',
        'inicia_en',
        'descripcion',
        'estado',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'inicia_en' => 'datetime',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function etapasDisponibles(): array
    {
        return [
            'siembra' => 'Siembra',
            'cultivo' => 'Cultivo',
            'cosecha' => 'Cosecha',
        ];
    }

    /**
     * Valores guardados en BD (editable manualmente).
     *
     * @return array<string, string>
     */
    public static function estadosDisponibles(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'en_proceso' => 'En proceso',
            'completado' => 'Completado',
        ];
    }

    /**
     * Etiquetas para el estado efectivo (incluye regla automática).
     *
     * @return array<string, string>
     */
    public static function etiquetasEstadoEfectivo(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'en_proceso' => 'En proceso',
            'completado' => 'Completado',
        ];
    }

    /**
     * Estado mostrado en listados: si está pendiente en BD pero ya pasó
     * la fecha/hora programada (inicia_en), se considera "en proceso" hasta
     * que marques "completado" manualmente.
     */
    public function estadoEfectivo(): string
    {
        if ($this->estado === 'completado') {
            return 'completado';
        }

        if ($this->estado === 'en_proceso') {
            return 'en_proceso';
        }

        if ($this->inicia_en && Carbon::now()->greaterThanOrEqualTo($this->inicia_en)) {
            return 'en_proceso';
        }

        return 'pendiente';
    }

    public function etiquetaEstadoEfectivo(): string
    {
        return self::etiquetasEstadoEfectivo()[$this->estadoEfectivo()] ?? $this->estadoEfectivo();
    }

    public function badgeEstadoEfectivoClass(): string
    {
        return match ($this->estadoEfectivo()) {
            'completado' => 'text-bg-success',
            'en_proceso' => 'text-bg-info text-dark',
            'pendiente' => 'text-bg-warning text-dark',
            default => 'text-bg-secondary',
        };
    }

    /**
     * True cuando el listado muestra "en proceso" solo por haber llegado la hora programada.
     */
    public function estadoEsAutomaticoEnProceso(): bool
    {
        return $this->estado === 'pendiente'
            && $this->inicia_en !== null
            && Carbon::now()->greaterThanOrEqualTo($this->inicia_en);
    }

    public function etiquetaEtapa(): string
    {
        return self::etapasDisponibles()[$this->etapa] ?? ucfirst($this->etapa);
    }

    public function emojiEtapa(): string
    {
        return match ($this->etapa) {
            'siembra' => '🌱',
            'cultivo' => '🌿',
            'cosecha' => '🌾',
            default => '📌',
        };
    }

    public function colorEtapaHex(): string
    {
        return match ($this->etapa) {
            'siembra' => '#7dcea0',
            'cultivo' => '#52b788',
            'cosecha' => '#e9c46a',
            default => '#adb5bd',
        };
    }

    public function badgeEtapaClass(): string
    {
        return match ($this->etapa) {
            'siembra' => 'badge-etapa-siembra',
            'cultivo' => 'badge-etapa-cultivo',
            'cosecha' => 'badge-etapa-cosecha',
            default => 'text-bg-secondary',
        };
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public static function contarCompletados(): int
    {
        return static::query()->where('estado', 'completado')->count();
    }

    public static function contarEnProcesoEfectivo(): int
    {
        return static::query()
            ->where('estado', '!=', 'completado')
            ->where(function ($q) {
                $q->where('estado', 'en_proceso')
                    ->orWhere(function ($q2) {
                        $q2->where('estado', 'pendiente')
                            ->whereNotNull('inicia_en')
                            ->where('inicia_en', '<=', now());
                    });
            })
            ->count();
    }

    public static function contarPendientesEfectivo(): int
    {
        return static::query()
            ->where('estado', 'pendiente')
            ->where(function ($q) {
                $q->whereNull('inicia_en')
                    ->orWhere('inicia_en', '>', now());
            })
            ->count();
    }
}
