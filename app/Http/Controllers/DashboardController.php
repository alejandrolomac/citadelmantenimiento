<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Orden;
use App\Models\Agenda;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function chartData()
    {
        $preventivos = DB::table('orden_trabajo')
            ->selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->where('tipo_mantenimiento', 'Preventivo')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $correctivos = DB::table('orden_trabajo')
            ->selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->where('tipo_mantenimiento', 'Correctivo')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // Rellenar los meses vacíos con 0
        $mesesPreventivos = array_fill(1, 12, 0);
        foreach ($preventivos as $mes => $total) {
            $mesesPreventivos[$mes] = $total;
        }

        $mesesCorrectivos = array_fill(1, 12, 0);
        foreach ($correctivos as $mes => $total) {
            $mesesCorrectivos[$mes] = $total;
        }

        return response()->json([
            'preventivos' => array_values($mesesPreventivos),
            'correctivos' => array_values($mesesCorrectivos),
        ]);
    }


    public function getDashboardStats()
    {
        $totalUnidades = Unidad::count();
        $totalPreventivos = Orden::where('tipo_mantenimiento', 'Preventivo')->count();
        $totalCorrectivos = Orden::where('tipo_mantenimiento', 'Correctivo')->count();

        return response()->json([
            'unidades' => $totalUnidades,
            'preventivos' => $totalPreventivos,
            'correctivos' => $totalCorrectivos,
        ]);
    }

    // Método para obtener todos los eventos
    public function getEvents()
{
    $events = Agenda::select('id', 'titulo', 'descripcion', 'fecha_inicio', 'fecha_fin')
        ->where('estado', 1) // Solo eventos activos
        ->get()
        ->map(function ($evento) {
            return [
                'id'          => $evento->id,
                'title'       => $evento->titulo,
                'start'       => $evento->fecha_inicio,
                'end'         => $evento->fecha_fin,
                'description' => $evento->descripcion,
            ];
        });

    return response()->json($events);
}

    /*public function getEvents()
    {
        $events = Agenda::select('id', 'titulo', 'fecha_inicio', 'fecha_fin')
            ->where('estado', 1) // Solo eventos activos
            ->get();

        return response()->json($events);
    }*/

    // Método para obtener los próximos tres mantenimientos
    public function getNextMaintenances()
    {
        $nextMaintenances = Agenda::where('fecha_inicio', '>', now())
            ->orderBy('fecha_inicio')
            ->take(3)
            ->get(['titulo', 'fecha_inicio']);

        return response()->json($nextMaintenances);
    }

    public function getUrgentIncidencias()
    {
        $incidencias = \App\Models\Incidencia::with('unidad')
            ->where('status', '!=', 'Cerrada')
            ->orWhereNull('status')
            ->orderByDesc('conteo')
            ->take(5)
            ->get();

        return response()->json($incidencias);
    }
}
