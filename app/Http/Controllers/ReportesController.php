<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Orden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{

    public function chartData()
    {
        $data = DB::table('orden_trabajo')
            ->selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->where('tipo_mantenimiento', 'Correctivo')
            ->whereYear('created_at', Carbon::now()->year) // Solo datos del año actual
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // Rellenar los meses vacíos con 0
        $meses = array_fill(1, 12, 0);
        foreach ($data as $mes => $total) {
            $meses[$mes] = $total;
        }

        // Obtener el mes actual y el anterior
        $mesActual = Carbon::now()->month;
        $mesAnterior = $mesActual - 1;

        // Calcular el porcentaje de variación
        $mantenimientoActual = $meses[$mesActual] ?? 0;
        $mantenimientoAnterior = $meses[$mesAnterior] ?? 0;

        $variacion = 0;
        if ($mantenimientoAnterior > 0) {
            $variacion = (($mantenimientoActual - $mantenimientoAnterior) / $mantenimientoAnterior) * 100;
        } elseif ($mantenimientoActual > 0) {
            $variacion = 100; // Si antes era 0 y ahora hay valores, el aumento es del 100%
        }

        return response()->json([
            'data' => array_values($meses),
            'variacion' => round($variacion, 2) // Redondear a dos decimales
        ]);
    }

    public function cantMantPreventivo()
    {
        $data = DB::table('orden_trabajo')
            ->selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->where('tipo_mantenimiento', 'Preventivo')
            ->whereYear('created_at', Carbon::now()->year) // Solo datos del año actual
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // Rellenar los meses vacíos con 0
        $meses = array_fill(1, 12, 0);
        foreach ($data as $mes => $total) {
            $meses[$mes] = $total;
        }

        // Obtener el mes actual y el anterior
        $mesActual = Carbon::now()->month;
        $mesAnterior = $mesActual - 1;

        // Calcular el porcentaje de variación
        $mantenimientoActual = $meses[$mesActual] ?? 0;
        $mantenimientoAnterior = $meses[$mesAnterior] ?? 0;

        $variacion = 0;
        if ($mantenimientoAnterior > 0) {
            $variacion = (($mantenimientoActual - $mantenimientoAnterior) / $mantenimientoAnterior) * 100;
        } elseif ($mantenimientoActual > 0) {
            $variacion = 100; // Si antes era 0 y ahora hay valores, el aumento es del 100%
        }

        return response()->json([
            'data' => array_values($meses),
            'variacion' => round($variacion, 2) // Redondear a dos decimales
        ]);
    }



    /**
     * Extrae la cantidad de veces que los técnicos han realizado mantenimiento preventivo
     */
    public function getMantTecnicosPreventivo(Request $request)
    {
        $mes = $request->input('mes', Carbon::now()->format('m')); // Mes actual por defecto
        $anio = $request->input('anio', Carbon::now()->format('Y')); // Año actual por defecto

        $datos = Orden::whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->where('tipo_mantenimiento', 'Preventivo')
            ->join('users', 'orden_trabajo.id_usuario', '=', 'users.id')
            ->selectRaw('users.name AS tecnico, COUNT(*) AS cantidad')
            ->groupBy('users.name')
            ->orderByDesc('cantidad')
            ->get();

        return response()->json($datos);
    }

    /**
     * Extrae la cantidad de veces que los técnicos han realizado mantenimiento correctivo
     */
    public function getMantTecnicosCorrectivo(Request $request)
    {
        $mes = $request->input('mes', Carbon::now()->format('m')); // Mes actual por defecto
        $anio = $request->input('anio', Carbon::now()->format('Y')); // Año actual por defecto

        $datos = Orden::whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->where('tipo_mantenimiento', 'Correctivo')
            ->join('users', 'orden_trabajo.id_usuario', '=', 'users.id')
            ->selectRaw('users.name AS tecnico, COUNT(*) AS cantidad')
            ->groupBy('users.name')
            ->orderByDesc('cantidad')
            ->get();

        return response()->json($datos);
    }



    public function getMantenimientosPorUnidad(Request $request)
    {
        $mes = $request->input('mes', Carbon::now()->format('m'));
        $anio = $request->input('anio', Carbon::now()->format('Y'));

        $datos = Orden::whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->where('tipo_mantenimiento', 'Preventivo')
            ->join('unidad', 'orden_trabajo.id_unidad', '=', 'unidad.id_unidad')
            ->selectRaw('unidad.nombre AS unidad, COUNT(*) AS cantidad')
            ->groupBy('unidad.nombre')
            ->orderByDesc('cantidad')
            ->get();

        return response()->json($datos);
    }



    public function obtenerMantenimientoPrev(Request $request)
    {
        $mes = $request->input('mes', date('m')); // Si no hay mes, usar el actual
        $anio = $request->input('anio', date('Y')); // Si no hay año, usar el actual

        $mantenimientos = DB::table('orden_trabajo')
            ->select(DB::raw("CONCAT(unidad.nombre, ' - ', unidad.type) as unidad"), DB::raw('COUNT(*) as cantidad'))
            ->join('unidad', 'orden_trabajo.id_unidad', '=', 'unidad.id_unidad')
            ->where('orden_trabajo.tipo_mantenimiento', 'Preventivo')
            ->whereMonth('orden_trabajo.fecha', $mes)
            ->whereYear('orden_trabajo.fecha', $anio)
            ->groupBy('unidad.nombre', 'unidad.type')
            ->orderByDesc('cantidad')
            ->get();

        return response()->json($mantenimientos);
    }

    public function obtenerMantenimientoCorrec(Request $request)
    {
        $mes = $request->input('mes', date('m')); // Si no hay mes, usar el actual
        $anio = $request->input('anio', date('Y')); // Si no hay año, usar el actual

        $mantenimientos = DB::table('orden_trabajo')
            ->select(DB::raw("CONCAT(unidad.nombre, ' - ', unidad.type) as unidad"), DB::raw('COUNT(*) as cantidad'))
            ->join('unidad', 'orden_trabajo.id_unidad', '=', 'unidad.id_unidad')
            ->where('orden_trabajo.tipo_mantenimiento', 'Correctivo')
            ->whereMonth('orden_trabajo.fecha', $mes)
            ->whereYear('orden_trabajo.fecha', $anio)
            ->groupBy('unidad.nombre', 'unidad.type')
            ->orderByDesc('cantidad')
            ->get();

        return response()->json($mantenimientos);
    }

    public function tiposMantenimiento(Request $request)
    {
        // Rango de fechas (por defecto, el mes actual)
        $inicio = $request->input('inicio', Carbon::now()->startOfMonth()->toDateString());
        $fin = $request->input('fin', Carbon::now()->endOfMonth()->toDateString());

        // Contar mantenimientos preventivos y correctivos en el período seleccionado
        $mantenimientos = DB::table('orden_trabajo')
            ->select('tipo_mantenimiento', DB::raw('COUNT(*) as total'))
            ->whereBetween('orden_trabajo.fecha', [$inicio, $fin])
            ->groupBy('tipo_mantenimiento')
            ->pluck('total', 'tipo_mantenimiento');

        return response()->json([
            'preventivo' => $mantenimientos['Preventivo'] ?? 0,
            'correctivo' => $mantenimientos['Correctivo'] ?? 0
        ]);
    }

    

    

public function tendenciaMantenimiento()
{
    $maintenanceTrend = DB::table('orden_trabajo')
        ->join('unidad', 'orden_trabajo.id_unidad', '=', 'unidad.id_unidad')
        ->select(
            DB::raw('CONCAT(unidad.nombre, " - ", unidad.type) as unidad'),
            'tipo_mantenimiento',
            DB::raw('DATE(orden_trabajo.fecha) as fecha'),
            DB::raw('COUNT(*) as cantidad')
        )
        ->groupBy('unidad.nombre', 'unidad.type', 'tipo_mantenimiento', DB::raw('DATE(orden_trabajo.fecha)')) // Agrupar por las columnas reales
        ->orderBy('fecha')
        ->get();

    return response()->json($maintenanceTrend);
}


    

    public function resumenUnidad()
{
    $unitSummary = DB::table('orden_trabajo')
        ->join('unidad', 'orden_trabajo.id_unidad', '=', 'unidad.id_unidad')
        ->join('users', 'orden_trabajo.id_usuario', '=', 'users.id')
        ->select(
            DB::raw('CONCAT(unidad.nombre, " - ", unidad.type) as unidad'),
            DB::raw("COUNT(CASE WHEN tipo_mantenimiento = 'Preventivo' THEN 1 END) as total_preventivo"),
            DB::raw("COUNT(CASE WHEN tipo_mantenimiento = 'Correctivo' THEN 1 END) as total_correctivo"),
            'users.name as tecnico'
        )
        ->groupBy('unidad.nombre', 'unidad.type', 'users.name')
        ->get();

    return response()->json($unitSummary);
}

}
