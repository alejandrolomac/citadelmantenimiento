<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'adjunto'
    ];

    protected static function booted()
    {
        static::addGlobalScope('filtro_tipos_agenda', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check()) {
                $tiposPermitidos = auth()->user()->tipoDispositivos->pluck('id')->toArray();
                $builder->where(function ($q) use ($tiposPermitidos) {
                    $q->whereHas('unidades', function ($subq) use ($tiposPermitidos) {
                        $subq->withoutGlobalScope('filtro_tipos')->whereIn('tipo_dispositivo_id', $tiposPermitidos);
                    })->orDoesntHave('unidades'); // Mostrar eventos sin unidad asignada a todos
                });
            }
        });
    }
    public function unidades()
    {
        return $this->belongsToMany(Unidad::class, 'agenda_unidad', 'agenda_id', 'unidad_id');
    }
}
