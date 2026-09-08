<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incidencia;
use App\Models\Unidad;

class IncidenciaApiController extends Controller
{
    public function index()
    {
        return response()->json(Incidencia::all());
    }

    public function store(Request $request)
    {
        // Si el otro backend envía 'tb_id' en lugar de 'unidad_id', buscamos la unidad
        if ($request->has('tb_id') && !$request->has('unidad_id')) {
            $unidad = Unidad::where('tb_id', $request->tb_id)->first();
            if ($unidad) {
                $request->merge(['unidad_id' => $unidad->id_unidad]);
            } else {
                return response()->json(['error' => 'Dispositivo con tb_id ' . $request->tb_id . ' no encontrado en la base de datos.'], 404);
            }
        }

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
