<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
   use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'tel',
        'email',
        'password',
        'rol',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Métodos helper para roles
    public function esAdministrador(): bool
    {
        return $this->rol === 1;
    }

    public function esOperador(): bool
    {
        return $this->rol === 2;
    }

    public function esUsuario(): bool
    {
        return $this->rol === 3;
    }

    public function estaActivo(): bool
    {
        return $this->estado === 1;
    }

    // Obtener nombre del rol
    public function getNombreRolAttribute(): string
    {
        return match($this->rol) {
            1 => 'Administrador',
            2 => 'Operador',
            3 => 'Usuario',
            default => 'Desconocido',
        };
    }
}
