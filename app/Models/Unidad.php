<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory;
    protected $table = 'unidad';
    protected $primaryKey = 'id_unidad';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'tb_id',
        'fecha',
        'type',
        'tipo_dispositivo_id',
        'estado',
        'ip',
    ];

    protected static function booted()
    {
        static::addGlobalScope('filtro_tipos', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check()) {
                $tiposPermitidos = auth()->user()->tipoDispositivos->pluck('id')->toArray();
                $builder->whereIn('tipo_dispositivo_id', $tiposPermitidos);
            }
        });
    }

    public function tipoDispositivo()
    {
        return $this->belongsTo(TipoDispositivo::class, 'tipo_dispositivo_id');
    }
    
    protected $hidden = [];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'unidad_id');
    }

    public function mantenimientos()
    {
        return $this->hasMany(Orden::class, 'id_unidad');
    }
}
