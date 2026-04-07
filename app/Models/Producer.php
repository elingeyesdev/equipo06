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
        'address',
        'is_active',
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'productor_id');
    }
}
