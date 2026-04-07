<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public static function tiposDisponibles(): array
    {
        return [
            'fruta' => 'Fruta',
            'verdura' => 'Verdura',
            'grano' => 'Grano',
            'legumbre' => 'Legumbre',
            'tuberculo' => 'Tubérculo',
            'otro' => 'Otro',
        ];
    }

    public function etiquetaTipo(): string
    {
        return self::tiposDisponibles()[$this->tipo] ?? ucfirst($this->tipo);
    }

    /**
     * Clase Bootstrap para badge según tipo (variación visual).
     */
    public function badgeTipoClass(): string
    {
        return match ($this->tipo) {
            'fruta' => 'text-bg-warning',
            'verdura' => 'text-bg-success',
            'grano' => 'text-bg-primary',
            'legumbre' => 'text-bg-info',
            'tuberculo' => 'text-bg-secondary',
            default => 'text-bg-light text-dark border',
        };
    }

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'tipo',
        'descripcion',
        'productor_id',
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

    public function productor(): BelongsTo
    {
        return $this->belongsTo(Producer::class, 'productor_id');
    }
}
