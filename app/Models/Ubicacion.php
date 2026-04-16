<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones';

    protected $fillable = [
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

    /**
     * Vínculo opcional con un envío (se usará cuando el módulo registre puntos por guía).
     */
    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class, 'envio_id');
    }
}
