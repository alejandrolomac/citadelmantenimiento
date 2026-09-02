<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incidencia;

class IncidenciaApiController extends Controller
{
    public function index()
    {
        return response()->json(Incidencia::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'descripcion' => 'required|string',
            'unidad_id' => 'required|exists:unidad,id_unidad',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'nullable|string',
            'conteo' => 'nullable|integer',
            'reportado_por' => 'nullable|string',
            'nivel_importancia' => 'nullable|string',
        ]);

        $incidencia = Incidencia::create($validated);
        return response()->json($incidencia, 201);
    }

    public function update(Request $request, $id)
    {
        $incidencia = Incidencia::findOrFail($id);
        
        $validated = $request->validate([
            'descripcion' => 'sometimes|string',
            'unidad_id' => 'sometimes|exists:unidad,id_unidad',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'nullable|string',
            'conteo' => 'nullable|integer',
            'reportado_por' => 'nullable|string',
            'nivel_importancia' => 'nullable|string',
        ]);

        $incidencia->update($validated);
        return response()->json($incidencia);
    }
}
