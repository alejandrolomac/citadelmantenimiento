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
            'adjuntos.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $data = $request->only('titulo', 'descripcion', 'fecha_inicio', 'fecha_fin');

        if ($request->hasFile('adjuntos')) {
            $archivos = [];
            foreach ($request->file('adjuntos') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('agendas', $filename, 'public');
                $archivos[] = $path;
            }
            $data['adjuntos'] = json_encode($archivos);
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
            'adjuntos.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $data = $request->only('titulo', 'descripcion', 'fecha_inicio', 'fecha_fin');

        $viejosArchivos = [];
        if ($agenda->adjuntos) {
            $viejos = json_decode($agenda->adjuntos, true);
            if (is_array($viejos)) {
                $viejosArchivos = $viejos;
            }
        }

        // Eliminar archivos indicados
        if ($request->has('eliminar_archivos')) {
            $eliminar = $request->input('eliminar_archivos');
            if (is_array($eliminar)) {
                foreach ($eliminar as $delPath) {
                    if ($delPath && is_string($delPath)) {
                        if (Storage::disk('public')->exists($delPath)) {
                            Storage::disk('public')->delete($delPath);
                        }
                        $viejosArchivos = array_filter($viejosArchivos, function($path) use ($delPath) {
                            return $path !== $delPath;
                        });
                    }
                }
            }
        }

        // Agregar nuevos archivos
        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('agendas', $filename, 'public');
                $viejosArchivos[] = $path;
            }
        }

        $data['adjuntos'] = json_encode(array_values($viejosArchivos));

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

            // Eliminar archivos si existen
            if ($agenda->adjuntos) {
                $archivos = json_decode($agenda->adjuntos, true);
                if (is_array($archivos)) {
                    foreach ($archivos as $archivo) {
                        if (Storage::disk('public')->exists($archivo)) {
                            Storage::disk('public')->delete($archivo);
                        }
                    }
                }
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
                    'adjuntos' => $evento->adjuntos ? json_decode($evento->adjuntos, true) : [],
                    'estado' => $evento->estado
                ];
            })
        );
    }

    public function obtenerUnidades()
    {
        return response()->json(Unidad::all());
    }
}
