<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencias';
    protected $fillable = ['descripcion', 'unidad_id', 'user_id', 'status', 'conteo', 'reportado_por', 'nivel_importancia'];

    protected static function booted()
    {
        static::addGlobalScope('filtro_tipos_incidencia', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check()) {
                $tiposPermitidos = auth()->user()->tipoDispositivos->pluck('id')->toArray();
                $builder->whereHas('unidad', function ($q) use ($tiposPermitidos) {
                    $q->withoutGlobalScope('filtro_tipos')->whereIn('tipo_dispositivo_id', $tiposPermitidos);
                });
            }
        });
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}