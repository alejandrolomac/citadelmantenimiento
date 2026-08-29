<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use App\Models\Unidad;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncidenciaController extends Controller
{
    public function index()
    {
        return view('pages.incidencias');
    }

    public function getData()
    {
        $incidencias = Incidencia::with(['unidad', 'usuario'])->get();
        return response()->json(['data' => $incidencias]);
    }

    public function show($id)
    {
        $incidencia = Incidencia::with(['unidad', 'usuario'])->findOrFail($id);
        return response()->json($incidencia);
    }

    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|string',
            'unidad_id' => 'required|exists:unidad,id_unidad',
            'user_id' => 'nullable|exists:users,id',
            'reportado_por' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
            'conteo' => 'nullable|integer',
            'nivel_importancia' => 'nullable|string|in:Baja,Normal,Urgente',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $incidencia = Incidencia::create([
            'descripcion' => $request->descripcion,
            'unidad_id' => $request->unidad_id,
            'user_id' => $request->user_id,
            'reportado_por' => $request->reportado_por,
            'status' => $request->status ?? 'Abierta',
            'conteo' => $request->conteo ?? 1,
            'nivel_importancia' => $request->nivel_importancia ?? 'Normal',
        ]);

        return response()->json(['message' => 'Incidencia creada correctamente', 'data' => $incidencia]);
    }


    public function update(Request $request, $id)
    {
        $incidencia = Incidencia::findOrFail($id);
        $incidencia->update($request->all());
        return response()->json(['message' => 'Incidencia actualizada correctamente']);
    }

    public function destroy($id)
    {
        $incidencia = Incidencia::findOrFail($id);
        $incidencia->delete();
        return response()->json(['message' => 'Incidencia eliminada correctamente']);
    }

    public function obtenerUnidades()
    {
        return response()->json(Unidad::all());
    }

    public function obtenerUsuarios()
    {
        // Recuperar el ID del rol 'Conductor'
        $rolConductor = Rol::where('name', 'Conductor')->first();

        // Asegúrate de que exista el rol antes de filtrar usuarios
        if ($rolConductor) {
            $conductores = User::where('id_rol', $rolConductor->id)->get();
            return response()->json($conductores);
        }

        // En caso de que no exista el rol "Conductor"
        return response()->json(['error' => 'Rol de Conductor no encontrado'], 404);
    }

}
