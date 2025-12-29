<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

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

        // SEGURIDAD LOGIN
        'failed_attempts',
        'is_locked',
        'lock_code',

        // 2FA
        'two_factor_enabled',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'two_factor_expires_at' => 'datetime',
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
        return match ($this->rol) {
            1 => 'Administrador',
            2 => 'Operador',
            3 => 'Usuario',
            default => 'Desconocido',
        };
    }

    // Métodos para 2FA
    public function generateTwoFactorCode(): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->two_factor_code = $code;
        $this->two_factor_expires_at = Carbon::now()->addMinutes(10);
        $this->save();

        return $code;
    }

    public function resetTwoFactorCode(): void
    {
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    public function validateTwoFactorCode($code): bool
    {
        if (!$this->two_factor_code || !$this->two_factor_expires_at) {
            return false;
        }

        if (Carbon::now()->gt($this->two_factor_expires_at)) {
            return false;
        }

        return $this->two_factor_code === $code;
    }
}
