<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDispositivoArchivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_dispositivo_id',
        'nombre_original',
        'ruta_archivo',
        'tipo_archivo'
    ];

    public function tipoDispositivo()
    {
        return $this->belongsTo(TipoDispositivo::class, 'tipo_dispositivo_id');
    }
}
