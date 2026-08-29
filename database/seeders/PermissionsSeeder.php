<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar caché de spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'Mantenimiento',
            'Dispositivos',
            'Tipos Dispositivos',
            'Incidencias',
            'Reportes',
            'Agenda',
            'Usuarios',
            'Roles',
        ];

        $actions = ['Ver', 'Crear', 'Editar', 'Eliminar'];

        $permissions = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissionName = $action . ' ' . $module;
                $permissions[] = $permissionName;
                \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            }
        }

        // Crear o buscar rol Administrador
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        
        // Asignar todos los permisos al rol
        $adminRole->syncPermissions(\Spatie\Permission\Models\Permission::all());

        // Asignar rol Administrador al usuario 1 (principal)
        $user = \App\Models\User::find(1);
        if ($user) {
            $user->assignRole($adminRole);
            $user->id_rol = $adminRole->id;
            $user->save();
        }
    }
}
