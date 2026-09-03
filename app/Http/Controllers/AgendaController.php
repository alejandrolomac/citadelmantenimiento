<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            'unidades' => 'required',
            'adjunto' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $data = $request->only('titulo', 'descripcion', 'fecha_inicio', 'fecha_fin');

        if ($request->hasFile('adjunto')) {
            $file = $request->file('adjunto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('agendas', $filename, 'public');
            $data['adjunto'] = $path;
        }

        // Crear el evento de mantenimiento
        $agenda = Agenda::create($data);

        // Convertir unidades a array si viene como string
        $unidadesArray = is_array($request->unidades) ? $request->unidades : explode(',', $request->unidades);

        // Asignar las unidades seleccionadas al evento de mantenimiento en la tabla intermedia
        foreach ($unidadesArray as $unidad_id) {
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
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'unidades' => 'required',
            'adjunto' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $data = $request->only('titulo', 'descripcion', 'fecha_inicio', 'fecha_fin');

        if ($request->hasFile('adjunto')) {
            // Eliminar archivo anterior si existe
            if ($agenda->adjunto && Storage::disk('public')->exists($agenda->adjunto)) {
                Storage::disk('public')->delete($agenda->adjunto);
            }

            $file = $request->file('adjunto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('agendas', $filename, 'public');
            $data['adjunto'] = $path;
        }

        // Actualizar los datos del evento de mantenimiento
        $agenda->update($data);

        // Convertir unidades a array si viene como string
        $unidadesArray = is_array($request->unidades) ? $request->unidades : explode(',', $request->unidades);

        // Actualizar las unidades asociadas al evento de mantenimiento
        $agenda->unidades()->sync($unidadesArray);

        return response()->json(['message' => 'Mantenimiento actualizado correctamente']);
    }


    public function destroy($id)
    {
        try {
            $agenda = Agenda::findOrFail($id);

            DB::beginTransaction();

            // Eliminar las relaciones en agenda_unidad
            DB::table('agenda_unidad')->where('agenda_id', $id)->delete();

            // Eliminar archivo si existe
            if ($agenda->adjunto && Storage::disk('public')->exists($agenda->adjunto)) {
                Storage::disk('public')->delete($agenda->adjunto);
            }

            // Eliminar el mantenimiento en la tabla agenda
            $agenda->delete();

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
                    'unidades' => $evento->unidades->pluck('id_unidad')->toArray(),
                    'adjunto' => $evento->adjunto ? '/storage/' . $evento->adjunto : null,
                ];
            })
        );
    }

    public function obtenerUnidades()
    {
        return response()->json(Unidad::all());
    }
}
