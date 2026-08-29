<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    public function index()
    {
        return view('pages.agenda');
    }

    // AgendaController.php
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'unidades' => 'required|array'
        ]);

        // Crear el evento de mantenimiento
        $agenda = Agenda::create($request->only('titulo', 'descripcion', 'fecha_inicio', 'fecha_fin'));

        // Asignar las unidades seleccionadas al evento de mantenimiento en la tabla intermedia
        foreach ($request->unidades as $unidad_id) {
            DB::table('agenda_unidad')->insert([
                'agenda_id' => $agenda->id,
                'unidad_id' => $unidad_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['mensage' => 'Mantenimiento programado correctamente']);
    }


    public function show($id)
    {
        return response()->json(Agenda::findOrFail($id));
    }


    // AgendaController.php
    public function update(Request $request, Agenda $agenda)
    {
        $request->validate([
            'titulo' => 'required',
            'descripcion' => 'required',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'unidades' => 'required|array'
        ]);

        // Actualizar los datos del evento de mantenimiento
        $agenda->update($request->only('titulo', 'descripcion', 'fecha_inicio', 'fecha_fin'));

        // Actualizar las unidades asociadas al evento de mantenimiento
        $agenda->unidades()->sync($request->unidades);

        return response()->json(['message' => 'Mantenimiento actualizado correctamente']);
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Eliminar las relaciones en agenda_unidad
            DB::table('agenda_unidad')->where('agenda_id', $id)->delete();

            // Eliminar el mantenimiento en la tabla agenda
            Agenda::destroy($id);

            DB::commit();

            return response()->json(['mensaje' => 'Mantenimiento eliminado correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Hubo un error al eliminar el mantenimiento.'], 500);
        }
    }

    public function listar()
    {
        return response()->json(
            Agenda::with('unidades')->get()->map(function ($evento) {
                return [
                    'id' => $evento->id,
                    'title' => $evento->titulo,
                    'start' => $evento->fecha_inicio,
                    'end' => $evento->fecha_fin,
                    'description' => $evento->descripcion,
                    'unidades' => $evento->unidades->pluck('id_unidad')->toArray()
                ];
            })
        );
    }

    public function obtenerUnidades()
    {
        return response()->json(Unidad::all());
    }
}
