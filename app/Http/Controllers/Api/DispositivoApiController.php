<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unidad;

class DispositivoApiController extends Controller
{
    public function index()
    {
        return response()->json(Unidad::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tb_id' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'fecha' => 'nullable|date',
            'estado' => 'nullable|boolean',
            'ip' => 'nullable|string|max:45',
            'tipo_dispositivo_id' => 'nullable|exists:tipo_dispositivos,id',
        ]);

        $unidad = Unidad::create($validated);
        return response()->json($unidad, 201);
    }

    public function update(Request $request, $id)
    {
        $unidad = Unidad::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'tb_id' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'fecha' => 'nullable|date',
            'estado' => 'nullable|boolean',
            'ip' => 'nullable|string|max:45',
            'tipo_dispositivo_id' => 'nullable|exists:tipo_dispositivos,id',
        ]);

        $unidad->update($validated);
        return response()->json($unidad);
    }
}
