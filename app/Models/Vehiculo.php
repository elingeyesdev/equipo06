<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = [
        'transportista_id',
        'placa',
        'descripcion',
        'activo',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function transportista(): BelongsTo
    {
        return $this->belongsTo(Transportista::class, 'transportista_id');
    }

    public function asignacionesEnvio(): HasMany
    {
        return $this->hasMany(AsignacionEnvio::class, 'vehiculo_id');
    }
}
