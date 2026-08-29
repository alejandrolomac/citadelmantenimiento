<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
//use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'El correo electrónico no está registrado.'], 401);
        }

        if ($user->userBlocked()) {
            return response()->json(['error' => 'Su cuenta está bloqueada. Contacte a un administrador para reactivarla.'], 401);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            $user->registerFailedAttempt(); // Incrementa los intentos fallidos
            return response()->json(['error' => 'Contraseña incorrecta.'], 401);
        }

        $user->resetFailedAttempts(); // Restablece intentos fallidos en login exitoso
        $request->session()->regenerate();

        return response()->json([
            'message' => "Bienvenido, {$user->name}!",
        ]);
    }

    /*public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'El correo electrónico no está registrado.'], 401);
        }

        if ($user->userBlocked()) {
            return response()->json(['error' => 'Su cuenta está bloqueada. Contacte a un administrador para reactivarla.'], 401);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            $user->registerFailedAttempt(); // Incrementa los intentos fallidos
            return response()->json(['error' => 'Contraseña incorrecta.'], 401);
        }

        $user->resetFailedAttempts(); // Restablece intentos fallidos en login exitoso
        $request->session()->regenerate();

        return response()->json(['message' => 'Inicio de Sesión Correcto.']);
    }*/

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
