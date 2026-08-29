<?php

namespace App\Models;

use App\Models\Rol;
use Spatie\Permission\Traits\HasRoles;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Model
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'id_rol', 'status', 'foto', 'failed_attempts'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rol()
{
    return $this->belongsTo(Rol::class, 'id_rol');
}
}
