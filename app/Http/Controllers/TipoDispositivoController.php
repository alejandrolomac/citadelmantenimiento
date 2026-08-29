<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipoDispositivoController extends Controller
{
    public function index()
    {
        $tipos = \App\Models\TipoDispositivo::with('archivos')->withCount('unidades')->get();
        return view('pages.tipos_dispositivos.index', compact('tipos'));
    }

    public function show($id)
    {
        $tipo = \App\Models\TipoDispositivo::with('archivos')->findOrFail($id);
        return view('pages.tipos_dispositivos.show', compact('tipo'));
    }

    public function create()
    {
        $users = \App\Models\User::all();
        return view('pages.tipos_dispositivos.form', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivos.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240' // 10MB max per file
        ]);

        $tipo = \App\Models\TipoDispositivo::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        if ($request->has('users')) {
            $tipo->users()->sync($request->users);
        }

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $nombre_original = $archivo->getClientOriginalName();
                $ruta_archivo = $archivo->store('tipos_dispositivos/' . $tipo->id, 'public');
                $tipo_archivo = $archivo->getClientOriginalExtension();

                $tipo->archivos()->create([
                    'nombre_original' => $nombre_original,
                    'ruta_archivo' => $ruta_archivo,
                    'tipo_archivo' => $tipo_archivo
                ]);
            }
        }

        return redirect()->route('tipos.index')->with('success', 'Tipo de dispositivo creado correctamente.');
    }

    public function edit($id)
    {
        $tipo = \App\Models\TipoDispositivo::with('archivos', 'users')->findOrFail($id);
        $users = \App\Models\User::all();
        return view('pages.tipos_dispositivos.form', compact('tipo', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivos.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        $tipo = \App\Models\TipoDispositivo::findOrFail($id);
        $tipo->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        if ($request->has('users')) {
            $tipo->users()->sync($request->users);
        } else {
            $tipo->users()->detach();
        }

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $nombre_original = $archivo->getClientOriginalName();
                $ruta_archivo = $archivo->store('tipos_dispositivos/' . $tipo->id, 'public');
                $tipo_archivo = $archivo->getClientOriginalExtension();

                $tipo->archivos()->create([
                    'nombre_original' => $nombre_original,
                    'ruta_archivo' => $ruta_archivo,
                    'tipo_archivo' => $tipo_archivo
                ]);
            }
        }

        return redirect()->route('tipos.index')->with('success', 'Tipo de dispositivo actualizado.');
    }

    public function destroy($id)
    {
        $tipo = \App\Models\TipoDispositivo::findOrFail($id);
        
        // Borrar archivos fisicos
        foreach ($tipo->archivos as $archivo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($archivo->ruta_archivo);
        }
        
        $tipo->delete();
        return redirect()->route('tipos.index')->with('success', 'Tipo de dispositivo eliminado.');
    }

    public function deleteFile($file_id)
    {
        $archivo = \App\Models\TipoDispositivoArchivo::findOrFail($file_id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($archivo->ruta_archivo);
        $archivo->delete();

        return response()->json(['success' => true]);
    }
}
