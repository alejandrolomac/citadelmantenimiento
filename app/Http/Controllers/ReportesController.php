<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Orden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    // ==========================================
    // 1. GENERAL (Resumen y KPIs)
    // ==========================================
    public function getKPIs()
    {
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        $totalDispositivos = DB::table('unidad')->where('estado', true)->count();
        
        $incidenciasStats = DB::table('incidencias')
            ->selectRaw("
                SUM(CASE WHEN status = 'Abierta' THEN 1 ELSE 0 END) as abiertas,
                SUM(CASE WHEN status IN ('Resuelta', 'Cerrada') THEN 1 ELSE 0 END) as resueltas
            ")
            ->first();

        $mantenimientosStats = DB::table('orden_trabajo')
            ->selectRaw("
                SUM(CASE WHEN tipo_mantenimiento = 'Preventivo' THEN 1 ELSE 0 END) as preventivos,
                SUM(CASE WHEN tipo_mantenimiento = 'Correctivo' THEN 1 ELSE 0 END) as correctivos
            ")
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->first();

        return response()->json([
            'dispositivos' => $totalDispositivos,
            'incidencias_abiertas' => $incidenciasStats->abiertas ?? 0,
            'incidencias_resueltas' => $incidenciasStats->resueltas ?? 0,
            'mant_preventivos' => $mantenimientosStats->preventivos ?? 0,
            'mant_correctivos' => $mantenimientosStats->correctivos ?? 0,
        ]);
    }

    public function getMantenimientosMensuales()
    {
        $anioActual = Carbon::now()->year;
        
        $data = DB::table('orden_trabajo')
            ->selectRaw("MONTH(fecha) as mes, tipo_mantenimiento, COUNT(*) as total")
            ->whereYear('fecha', $anioActual)
            ->groupBy('mes', 'tipo_mantenimiento')
            ->get();

        $preventivos = array_fill(1, 12, 0);
        $correctivos = array_fill(1, 12, 0);

        foreach ($data as $row) {
            if ($row->tipo_mantenimiento == 'Preventivo') {
                $preventivos[$row->mes] = $row->total;
            } else {
                $correctivos[$row->mes] = $row->total;
            }
        }

        return response()->json([
            'meses' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            'preventivos' => array_values($preventivos),
            'correctivos' => array_values($correctivos)
        ]);
    }

    public function getTiposMantenimiento()
    {
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        $mantenimientos = DB::table('orden_trabajo')
            ->selectRaw('tipo_mantenimiento, COUNT(*) as total')
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->groupBy('tipo_mantenimiento')
            ->pluck('total', 'tipo_mantenimiento');

        return response()->json([
            'Preventivo' => $mantenimientos['Preventivo'] ?? 0,
            'Correctivo' => $mantenimientos['Correctivo'] ?? 0
        ]);
    }

    // ==========================================
    // 2. INCIDENCIAS
    // ==========================================
    public function getIncidenciasMensuales()
    {
        $anioActual = Carbon::now()->year;
        
        $data = DB::table('incidencias')
            ->selectRaw("MONTH(created_at) as mes, status, COUNT(*) as total")
            ->whereYear('created_at', $anioActual)
            ->groupBy('mes', 'status')
            ->get();

        $reportadas = array_fill(1, 12, 0); // Todas cuentan como reportadas
        $resueltas = array_fill(1, 12, 0); // Cerradas o Resueltas

        foreach ($data as $row) {
            $reportadas[$row->mes] += $row->total;
            if (in_array($row->status, ['Resuelta', 'Cerrada'])) {
                $resueltas[$row->mes] += $row->total;
            }
        }

        return response()->json([
            'meses' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            'reportadas' => array_values($reportadas),
            'resueltas' => array_values($resueltas)
        ]);
    }

    public function getTopDispositivosIncidencias()
    {
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        $top = DB::table('incidencias')
            ->join('unidad', 'incidencias.unidad_id', '=', 'unidad.id_unidad')
            ->selectRaw('unidad.nombre as dispositivo, COUNT(*) as total')
            ->whereMonth('incidencias.created_at', $mesActual)
            ->whereYear('incidencias.created_at', $anioActual)
            ->groupBy('unidad.nombre')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return response()->json($top);
    }

    public function getEstatusIncidencias()
    {
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        $estatus = DB::table('incidencias')
            ->selectRaw('status, COUNT(*) as total')
            ->whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $anioActual)
            ->groupBy('status')
            ->pluck('total', 'status');
            
        $abiertas = $estatus['Abierta'] ?? 0;
        $resueltas = ($estatus['Resuelta'] ?? 0) + ($estatus['Cerrada'] ?? 0);

        return response()->json([
            'Abiertas' => $abiertas,
            'Resueltas' => $resueltas
        ]);
    }

    public function getComparacionIncidencias(Request $request)
    {
        $mes = $request->input('mes', Carbon::now()->month);
        $anio = $request->input('anio', Carbon::now()->year);

        $query = DB::table('incidencias')
            ->join('unidad', 'incidencias.unidad_id', '=', 'unidad.id_unidad')
            ->select(
                'incidencias.id as incidencia_id',
                'incidencias.created_at as fecha_incidencia',
                'unidad.nombre as dispositivo',
                'unidad.id_unidad'
            )
            ->whereIn('incidencias.status', ['Resuelta', 'Cerrada']);

        if ($mes !== 'all') {
            $query->whereMonth('incidencias.created_at', $mes);
        }

        if ($anio !== 'all') {
            $query->whereYear('incidencias.created_at', $anio);
        }

        $incidencias = $query->orderBy('incidencias.created_at', 'desc')->get();

        $resultados = [];

        foreach ($incidencias as $inc) {
            // Buscar la orden más cercana creada después de la incidencia
            $orden = DB::table('orden_trabajo')
                ->where('id_unidad', $inc->id_unidad)
                ->where('created_at', '>=', $inc->fecha_incidencia)
                ->orderBy('created_at', 'asc')
                ->first();

            if ($orden) {
                $fechaInc = Carbon::parse($inc->fecha_incidencia);
                $fechaOrd = Carbon::parse($orden->created_at);
                $minutosTotales = $fechaInc->diffInMinutes($fechaOrd);
                
                if ($minutosTotales < 60) {
                    $tiempoStr = $minutosTotales . ' min';
                } elseif ($minutosTotales < 1440) { // Menos de un día
                    $horas = floor($minutosTotales / 60);
                    $minutos = $minutosTotales % 60;
                    $tiempoStr = $horas . ':' . str_pad($minutos, 2, '0', STR_PAD_LEFT) . ' hrs';
                } else {
                    $dias = (int) floor($minutosTotales / 1440);
                    $tiempoStr = $dias . ($dias == 1 ? ' día' : ' días');
                }

                $resultados[] = [
                    'incidencia_id' => $inc->incidencia_id,
                    'orden_id' => $orden->id_orden_trabajo,
                    'no_orden' => $orden->no_orden,
                    'dispositivo' => $inc->dispositivo,
                    'fecha_incidencia' => $fechaInc->format('d/m/Y h:i A'),
                    'fecha_orden' => $fechaOrd->format('d/m/Y h:i A'),
                    'tiempo_resolucion' => $tiempoStr
                ];
            }
        }

        return response()->json($resultados);
    }

    // ==========================================
    // 3. TÉCNICOS
    // ==========================================
    public function getRendimientoTecnicos(Request $request)
    {
        $mes = $request->input('mes', Carbon::now()->month);
        $anio = $request->input('anio', Carbon::now()->year);

        $tecnicos = DB::table('orden_trabajo')
            ->join('users', 'orden_trabajo.id_usuario', '=', 'users.id')
            ->selectRaw("
                users.name as tecnico,
                SUM(CASE WHEN tipo_mantenimiento = 'Preventivo' THEN 1 ELSE 0 END) as preventivos,
                SUM(CASE WHEN tipo_mantenimiento = 'Correctivo' THEN 1 ELSE 0 END) as correctivos,
                COUNT(*) as total
            ")
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->groupBy('users.name')
            ->orderByDesc('total')
            ->get();

        return response()->json($tecnicos);
    }

    // ==========================================
    // 4. DISPOSITIVOS
    // ==========================================
    public function getTendenciaDispositivos()
    {
        $maintenanceTrend = DB::table('orden_trabajo')
            ->join('unidad', 'orden_trabajo.id_unidad', '=', 'unidad.id_unidad')
            ->select(
                DB::raw('CONCAT(unidad.nombre, " - ", unidad.type) as unidad'),
                'tipo_mantenimiento',
                DB::raw('DATE(orden_trabajo.fecha) as fecha'),
                DB::raw('COUNT(*) as cantidad')
            )
            ->groupBy('unidad.nombre', 'unidad.type', 'tipo_mantenimiento', DB::raw('DATE(orden_trabajo.fecha)'))
            ->orderBy('fecha')
            ->get();

        return response()->json($maintenanceTrend);
    }

    public function getResumenDispositivos(Request $request)
    {
        $mes = $request->input('mes', Carbon::now()->month);
        $anio = $request->input('anio', Carbon::now()->year);

        // Subquery for incidencias
        $incidencias = DB::table('incidencias')
            ->select('unidad_id', DB::raw('COUNT(*) as total_incidencias'))
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->groupBy('unidad_id');

        $unitSummary = DB::table('unidad')
            ->leftJoin('orden_trabajo', function($join) use ($mes, $anio) {
                $join->on('unidad.id_unidad', '=', 'orden_trabajo.id_unidad')
                     ->whereMonth('orden_trabajo.fecha', '=', $mes)
                     ->whereYear('orden_trabajo.fecha', '=', $anio);
            })
            ->leftJoinSub($incidencias, 'inc_stats', function($join) {
                $join->on('unidad.id_unidad', '=', 'inc_stats.unidad_id');
            })
            ->select(
                'unidad.nombre as dispositivo',
                'unidad.tb_id',
                DB::raw("COUNT(CASE WHEN orden_trabajo.tipo_mantenimiento = 'Preventivo' THEN 1 END) as total_preventivo"),
                DB::raw("COUNT(CASE WHEN orden_trabajo.tipo_mantenimiento = 'Correctivo' THEN 1 END) as total_correctivo"),
                DB::raw("COALESCE(MAX(inc_stats.total_incidencias), 0) as total_incidencias")
            )
            ->where('unidad.estado', true)
            ->groupBy('unidad.id_unidad', 'unidad.nombre', 'unidad.tb_id')
            ->orderByDesc('total_preventivo')
            ->orderByDesc('total_correctivo')
            ->get();

        return response()->json($unitSummary);
    }
}
