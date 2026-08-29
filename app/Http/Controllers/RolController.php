<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolController extends Controller
{
    /**
     * Muestra la lista de roles.
     */
    public function index()
    {
        return response()->json(Role::all());
    }

    /**
     * Devuelve los datos de los roles para la tabla.
     */
    public function getData()
    {
        return response()->json([
            'data' => Role::with('permissions')->get() // Cargar permisos asociados
        ]);
    }

    /**
     * Almacena un nuevo rol en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_rol' => 'required|string|max:255|unique:roles,name'
        ]);

        $rol = Role::create(['name' => $request->nombre_rol]);

        return response()->json(['message' => 'Rol creado correctamente', 'rol' => $rol], 201);
    }

    /**
     * Muestra un rol específico.
     */
    public function show($id)
    {
        $rol = Role::with('permissions')->find($id);

        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado'], 404);
        }

        return response()->json($rol);
    }

    /**
     * Actualiza un rol existente.
     */
    public function update(Request $request, $id)
    {
        $rol = Role::find($id);

        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado'], 404);
        }

        $request->validate([
            'nombre_rol' => 'required|string|max:255|unique:roles,name,' . $id
        ]);

        $rol->update(['name' => $request->nombre_rol]);

        return response()->json(['message' => 'Rol actualizado correctamente']);
    }

    /**
     * Elimina un rol.
     */
    public function destroy($id)
    {
        $rol = Role::find($id);

        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado'], 404);
        }

        $rol->delete();

        return response()->json(['message' => 'Rol eliminado correctamente']);
    }

    /**
     * Asigna permisos a un rol.
     */
    public function assignPermissions(Request $request, $id)
    {
        $rol = Role::find($id);

        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado'], 404);
        }

        $request->validate([
            'permisos' => 'array',
            'permisos.*' => 'exists:permissions,name'
        ]);

        $rol->syncPermissions($request->permisos);

        return response()->json(['message' => 'Permisos asignados correctamente']);
    }

    public function getPermissions()
{
    return response()->json(Permission::all());
}
}
