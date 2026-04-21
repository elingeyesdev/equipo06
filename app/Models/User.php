<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Roles MVP permitidos en el sistema (ENT 2.5).
     *
     * @return array<string, string>
     */
    public static function rolesDisponibles(): array
    {
        return [
            'admin' => 'Administrador',
            'productor' => 'Productor',
        ];
    }

    /**
     * Campos base del usuario para autenticación y clasificación por rol.
     *
     * Nota: algunos campos (nombre/apellido/rol/telefono) se preparan para
     * la siguiente tarea de migración, manteniendo compatibilidad con name actual.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nombre',
        'apellido',
        'email',
        'password',
        'rol',
        'telefono',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function esAdmin(): bool
    {
        return ($this->rol ?? 'productor') === 'admin';
    }

    public function esProductor(): bool
    {
        return ($this->rol ?? 'productor') === 'productor';
    }

    public function nombreCompleto(): string
    {
        $nombre = trim((string) ($this->nombre ?? ''));
        $apellido = trim((string) ($this->apellido ?? ''));
        if ($nombre !== '' || $apellido !== '') {
            return trim($nombre.' '.$apellido);
        }

        return (string) ($this->name ?? '');
    }
}
