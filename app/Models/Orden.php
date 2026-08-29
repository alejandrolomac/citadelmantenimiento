<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    protected $table = 'orden_trabajo'; 
    protected $primaryKey = 'id_orden_trabajo';
    protected $fillable = [
        'id_usuario',
        'id_unidad',
        'fecha',
        'hora_inicio',
        'no_orden',
        'conductor',
        'tecnico',
        'hora_final',
        'tipo_mantenimiento',
        'kilometraje',
        'formulario',
    ];

    protected static function booted()
    {
        static::addGlobalScope('filtro_tipos_orden', function (\Illuminate\Database\Eloquent\Builder $builder) {
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
        return $this->belongsTo(Unidad::class, 'id_unidad', 'id_unidad');
    }

    protected $casts = [
        'formulario' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function firmas()
    {
        return $this->hasMany(Firma::class, 'id_orden_trabajo', 'id_orden_trabajo');
    }
}
