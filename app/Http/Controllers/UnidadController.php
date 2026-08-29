<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidad;
use App\Models\Incidencia;

class UnidadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'tb_id' => 'nullable|string|max:100',
            'fecha' => 'nullable|date',
            'tipo_dispositivo_id' => 'nullable|exists:tipo_dispositivos,id',
            'type' => 'nullable|string|max:100',
            'ip' => 'nullable|string|max:45',
            'estado' => 'boolean',
        ]);

        if (isset($validated['estado']) && is_string($validated['estado'])) {
            $validated['estado'] = filter_var($validated['estado'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $validated['estado'] = $request->has('estado');
        }

        Unidad::create($validated);

        return redirect()->route('unidades')->with('success', 'Unidad creada correctamente');
    }

    public function create()
    {
        $tipos = \App\Models\TipoDispositivo::all();
        return view('unidad_form', compact('tipos'));
    }

    public function index()
    {
        return response()->json(Unidad::all());
    }

    public function getData()
    {
        return response()->json([
            'data' => Unidad::with('tipoDispositivo')->get()
        ]);
    }

    public function editar($id)
    {
        $unidad = Unidad::findOrFail($id);
        $formulario = json_decode($unidad->formulario, true) ?? [];
        $tipos = \App\Models\TipoDispositivo::all();

        return view('editar_unidad', compact('unidad', 'formulario', 'tipos'));
    }

    public function actualizar(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'tb_id' => 'nullable|string|max:100',
            'fecha' => 'nullable|date',
            'tipo_dispositivo_id' => 'nullable|exists:tipo_dispositivos,id',
            'type' => 'nullable|string|max:100',
            'ip' => 'nullable|string|max:45',
            'estado' => 'boolean',
        ]);

        if (isset($validated['estado']) && is_string($validated['estado'])) {
            $validated['estado'] = filter_var($validated['estado'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $validated['estado'] = $request->has('estado');
        }

        $unidad = Unidad::findOrFail($id);
        $unidad->update($validated);

        return redirect()->route('unidades')->with('success', 'Unidad actualizada correctamente');
    }

    public function verUnidad($id)
    {
        $unidad = Unidad::with(['usuario','incidencias.usuario', 'mantenimientos'])->findOrFail($id);
        return view('pages/detalle_unidad', compact('unidad'));
    }

    public function incidencias_unidad($id)
    {
        $incidencias = Incidencia::with(['unidad', 'usuario'])
            ->where('unidad_id', $id)
            ->get();
        return response()->json($incidencias);
    }
}
