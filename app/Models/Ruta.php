<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruta extends Model
{
    use HasFactory;

    protected $table = 'rutas';

    protected $fillable = [
        'envio_id',
        'nombre',
        'descripcion',
    ];

    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class, 'envio_id');
    }

    /**
     * Puntos de la ruta (origen, destino, controles) ordenados cuando exista columna orden.
     */
    public function ubicaciones(): HasMany
    {
        return $this->hasMany(Ubicacion::class, 'ruta_id')
            ->orderBy('orden')
            ->orderBy('id');
    }
}
