<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function getUsuarios()
    {
        return response()->json([
            'data' => User::with('rol:id,name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'id_rol' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $foto = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('users', $foto, 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_rol' => $request->id_rol,
            'foto' => $foto,
        ]);

        // Asignar el rol al usuario
        $rol = Role::find($request->id_rol); // Busca el rol por el ID proporcionado
        if ($rol) {
            $user->assignRole($rol->name); // Asegúrate de pasar el nombre del rol
        }

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $usuario = User::with('rol')->find($id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($usuario);
    }

    public function obtenerRoles()
    {
        return response()->json(Rol::all());
    }

    public function update(Request $request, $id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'id_rol' => 'required|integer',
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $datos = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'id_rol' => $request->input('id_rol')
        ];

        if ($request->filled('password')) {
            $datos['password'] = bcrypt($request->input('password'));
        }

        if ($request->hasFile('foto')) {
            if ($usuario->foto && Storage::disk('public')->exists('users/' . $usuario->foto)) {
                Storage::disk('public')->delete('users/' . $usuario->foto);
            }

            $file = $request->file('foto');
            $nombreFoto = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('users', $nombreFoto, 'public');
            $datos['foto'] = $nombreFoto;
        }

        $usuario->update($datos);

        // Actualizar el rol del usuario
        $rol = Role::find($request->id_rol); // Busca el rol por el ID proporcionado
        if ($rol) {
            $usuario->syncRoles($rol->name); // Sincroniza el nuevo rol
        }

        return response()->json(['message' => 'Usuario actualizado correctamente'], 200);
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return response()->json(['message' => 'Usuario eliminado']);
    }



    public function changePassword(Request $request)
{
    // Validar los datos recibidos
    $request->validate([
        'pass' => 'required|string|min:8', // Asegurar que la contraseña tenga un mínimo de 8 caracteres
        'user_id' => 'required|exists:users,id' // Validar que el usuario exista
    ]);

    try {
        // Buscar el usuario por ID
        $user = User::findOrFail($request->user_id);

        // Actualizar la contraseña
        $user->password = Hash::make($request->pass);
        $user->save();

        // Retornar respuesta exitosa
        return response()->json(['message' => 'Contraseña actualizada correctamente'], 200);
    } catch (\Exception $e) {
        // Manejar errores
        return response()->json(['error' => 'Error al actualizar la contraseña', 'details' => $e->getMessage()], 500);
    }
}
}
