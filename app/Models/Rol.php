<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Rol extends SpatieRole
{
    public $timestamps = false;

    protected $fillable = [
        'name' // Spatie usa 'name' en lugar de 'nombre_rol'
    ];
}