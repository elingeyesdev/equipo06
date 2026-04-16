<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionEnvio extends Model
{
    use HasFactory;

    protected $table = 'asignaciones_envio';

    protected $fillable = [
        'envio_id',
        'transportista_id',
        'vehiculo_id',
        'fecha_asignacion',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_asignacion' => 'datetime',
        ];
    }

    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class, 'envio_id');
    }

    public function transportista(): BelongsTo
    {
        return $this->belongsTo(Transportista::class, 'transportista_id');
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }
}
