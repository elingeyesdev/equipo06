<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recepcion extends Model
{
    use HasFactory;

    protected $table = 'recepciones';

    protected $fillable = [
        'envio_id',
        'fecha_llegada',
        'estado_entrega',
        'conforme',
        'cantidad_recibida',
        'resultado_validacion',
        'observaciones',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_llegada' => 'datetime',
            'conforme' => 'boolean',
            'cantidad_recibida' => 'decimal:3',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function estadosEntregaDisponibles(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'recibido' => 'Recibido',
            'observado' => 'Observado',
        ];
    }

    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class, 'envio_id');
    }

    public function etiquetaResultadoValidacion(): string
    {
        return match ($this->resultado_validacion) {
            'completa' => 'Recepción completa',
            'parcial' => 'Recepción parcial',
            'exceso' => 'Exceso recibido',
            default => 'Sin validar',
        };
    }
}
