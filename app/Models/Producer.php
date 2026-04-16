<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producer extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'document_number',
        'phone',
        'email',
        'address_type',
        'address_detail',
        'is_active',
    ];

    /**
     * Tipos de vía para dirección (valor guardado en BD).
     *
     * @return array<string, string>
     */
    public static function tiposVia(): array
    {
        return [
            'avenida' => 'Avenida',
            'calle' => 'Calle',
            'pasaje' => 'Pasaje',
            'carretera' => 'Carretera',
            'plaza' => 'Plaza',
            'otro' => 'Otro',
        ];
    }

    public function etiquetaTipoVia(): string
    {
        if ($this->address_type === null || $this->address_type === '') {
            return '';
        }

        return self::tiposVia()[$this->address_type] ?? ucfirst((string) $this->address_type);
    }

    /**
     * Dirección legible: "Avenida Banzer #123".
     */
    public function direccionCompleta(): ?string
    {
        if ($this->address_detail === null || trim((string) $this->address_detail) === '') {
            return null;
        }

        $tipo = $this->etiquetaTipoVia();
        $det = trim((string) $this->address_detail);

        return $tipo !== '' ? ($tipo.' '.$det) : $det;
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'productor_id');
    }

    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class, 'productor_id');
    }
}
