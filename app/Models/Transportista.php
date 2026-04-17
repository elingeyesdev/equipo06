<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Responsable de transporte (transportista): datos personales y licencia.
 *
 * Carnet de identidad: identificador personal (no usar nomenclatura DNI).
 * Teléfono: formato Bolivia recomendado +591 seguido de 8 dígitos (+591XXXXXXXX).
 *
 * Las relaciones con vehículos y asignaciones de envío se usarán en entregables posteriores.
 */
class Transportista extends Model
{
    use HasFactory;

    protected $table = 'transportistas';

    protected $fillable = [
        'nombre',
        'apellido',
        'carnet_identidad',
        'telefono',
        'email',
        'licencia',
        'tipo_licencia',
        'fecha_vencimiento_licencia',
        'estado',
    ];

    /**
     * Estados del registro del transportista (ciclo operativo / alta en sistema).
     *
     * @return array<string, string>
     */
    public static function estadosDisponibles(): array
    {
        return [
            'activo' => 'Activo',
            'inactivo' => 'Inactivo',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_vencimiento_licencia' => 'date',
        ];
    }

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'transportista_id');
    }

    public function asignacionesEnvio(): HasMany
    {
        return $this->hasMany(AsignacionEnvio::class, 'transportista_id');
    }
}
