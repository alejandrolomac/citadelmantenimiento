<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firma extends Model
{
    protected $fillable = ['orden_id', 'nombre_archivo', 'tipo_firmante'];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'orden_id', 'id_orden_trabajo');
    }
}