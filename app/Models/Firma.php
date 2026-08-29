<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firma extends Model
{
    protected $fillable = ['id_orden_trabajo', 'nombre_archivo', 'tipo_firmante'];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'id_orden_trabajo', 'id_orden_trabajo');
    }
}