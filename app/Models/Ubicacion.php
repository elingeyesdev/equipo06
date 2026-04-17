<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones';

    protected $fillable = [
        'ruta_id',
        'orden',
        'nombre_ubicacion',
        'tipo',
        'direccion',
        'latitud',
        'longitud',
        'descripcion',
        'envio_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'orden' => 'integer',
            'latitud' => 'decimal:7',
            'longitud' => 'decimal:7',
        ];
    }

    /**
     * Valores permitidos para el tipo de punto en la ruta / seguimiento.
     *
     * @return array<string, string>
     */
    public static function tiposPuntoDisponibles(): array
    {
        return [
            'origen' => 'Origen',
            'destino' => 'Destino',
            'punto_control' => 'Punto de control',
        ];
    }

    public function etiquetaTipo(): string
    {
        return self::tiposPuntoDisponibles()[$this->tipo] ?? ucfirst((string) $this->tipo);
    }

    public function badgeTipoClass(): string
    {
        return match ($this->tipo) {
            'origen' => 'text-bg-primary',
            'destino' => 'text-bg-success',
            'punto_control' => 'text-bg-info text-dark',
            default => 'text-bg-light text-dark border',
        };
    }

    /**
     * Vínculo opcional con un envío (se usará cuando el módulo registre puntos por guía).
     */
    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class, 'envio_id');
    }

    public function ruta(): BelongsTo
    {
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    /**
     * Envíos que tienen esta ubicación como posición actual.
     */
    public function enviosComoUbicacionActual(): HasMany
    {
        return $this->hasMany(Envio::class, 'ubicacion_actual_id');
    }
}
