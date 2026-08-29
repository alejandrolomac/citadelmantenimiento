<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable 
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'id_rol', // Asegurar compatibilidad con roles
        'status',
        'foto',
        'failed_attempts',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relación con el rol.
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    /**
     * Verifica si el usuario está bloqueado.
     */
    public function userBlocked()
    {
        return $this->status === 0;
    }

    /**
     * Incrementa el contador de intentos fallidos y bloquea si supera el límite.
     */
    public function registerFailedAttempt()
    {
        $this->increment('failed_attempts');

        if ($this->failed_attempts >= 5) {
            $this->update(['status' => 0]); // Bloquea al usuario
        }
    }

    /**
     * Restablece el contador de intentos fallidos.
     */
    public function resetFailedAttempts()
    {
        $this->update(['failed_attempts' => 0]);
    }

    public function tipoDispositivos()
    {
        return $this->belongsToMany(TipoDispositivo::class, 'tipo_dispositivo_user', 'user_id', 'tipo_dispositivo_id')->withTimestamps();
    }
}
