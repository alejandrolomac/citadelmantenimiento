<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDispositivo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion'];

    public function archivos()
    {
        return $this->hasMany(TipoDispositivoArchivo::class, 'tipo_dispositivo_id');
    }

    public function unidades()
    {
        return $this->hasMany(Unidad::class, 'tipo_dispositivo_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tipo_dispositivo_user', 'tipo_dispositivo_id', 'user_id')->withTimestamps();
    }
}
