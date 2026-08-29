<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Orden;
use App\Models\Firma;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class OrdenController extends Controller
{
    public function guardar(Request $request)
    {
        $orden = new Orden();
        $orden->id_usuario = auth()->id();
        $orden->id_unidad = $request['unidad'];
        $orden->no_orden = "ORD-" . strtoupper(uniqid());
        $orden->conductor = $request['conductor'] ?? 'N/A';
        $orden->tecnico = $request['tecnico'];
        $orden->fecha = $request['fechaOrden'];
        $orden->hora_inicio = $request['horaInicio'] ?? null;
        $orden->hora_final = $request['horaFinal'] ?? null;
        $orden->tipo_mantenimiento = $request['tipoMantenimiento'];
        $orden->kilometraje = $request['kilometraje'] ?? null;
        $orden->detalles = $request['detalles'];
        $orden->formulario = "{}"; // Mantener compatibilidad

        $orden->save();

        return redirect()->route('orden.completar', ['id' => $orden->id_orden_trabajo]);
    }

    public function getData()
    {
        $ordenes = Orden::select('orden_trabajo.*', 'unidad.nombre', 'unidad.type')
            ->join('unidad', 'orden_trabajo.id_unidad', '=', 'unidad.id_unidad')
            ->get();

        $data = $ordenes->map(function ($orden) {
            return [
                'id_orden_trabajo' => $orden->id_orden_trabajo,
                'nombre' => $orden->nombre,
                'type' => $orden->type,
                'no_orden' => $orden->no_orden,
                'fecha' => $orden->fecha,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function editar($id)
    {
        $orden = Orden::findOrFail($id);
        $unidades = DB::table('unidad')->where('estado', true)->get();

        return view('editar_orden', [
            'orden'        => $orden,
            'unidades'     => $unidades,
        ]);
    }

    public function actualizar(Request $request, $id)
    {
        $orden = Orden::findOrFail($id);

        $orden->id_unidad          = $request->input('unidad');
        $orden->conductor          = $request->input('conductor', 'N/A');
        $orden->tecnico            = $request->input('tecnico');
        $orden->fecha              = $request->input('fechaOrden');
        $orden->hora_inicio        = $request->input('horaInicio', null);
        $orden->hora_final         = $request->input('horaFinal', null);
        $orden->tipo_mantenimiento = $request->input('tipoMantenimiento');
        $orden->kilometraje        = $request->input('kilometraje', null);
        $orden->detalles           = $request->input('detalles');

        $orden->save();

        return redirect()->route('thanks');
    }

    public function destroy($id)
    {
        $orden = Orden::find($id);
        if (!$orden) {
            return response()->json(['message' => 'Orden no encontrada.'], 404);
        }

        $firmas = $orden->firmas;

        foreach ($firmas as $firma) {
            $archivo = $firma->nombre_archivo;
            $rutaFirma = "public/firmas/{$archivo}";
            if (Storage::exists($rutaFirma)) {
                Storage::delete($rutaFirma);
            }
            $firma->delete();
        }
        $orden->delete();

        return response()->json(['message' => 'Orden y firmas asociadas eliminadas correctamente.']);
    }

    public function exportarPDF(Request $request, $id_orden_trabajo)
    {
        $ordenTrabajo = Orden::find($id_orden_trabajo);
        $unidad = Unidad::find($ordenTrabajo->id_unidad);

        $firmaTecnico = $ordenTrabajo->firmas->firstWhere('tipo_firmante', 'Tecnico');
        $firmaConductor = $ordenTrabajo->firmas->firstWhere('tipo_firmante', 'Conductor');

        $firmaTecnicoPath = $firmaTecnico ? public_path('storage/firmas/' . $firmaTecnico->nombre_archivo) : null;
        $firmaConductorPath = $firmaConductor ? public_path('storage/firmas/' . $firmaConductor->nombre_archivo) : null;

        $qrCode = base64_encode(QrCode::format('svg')->size(150)->generate(url("/orden/{$id_orden_trabajo}/detalle")));

        $data = [
            'no_orden' => $ordenTrabajo->no_orden,
            'tecnico' => $ordenTrabajo->tecnico,
            'conductor' => $ordenTrabajo->conductor,
            'hora_inicio' => $ordenTrabajo->hora_inicio,
            'hora_final' => $ordenTrabajo->hora_final,
            'fecha' => $ordenTrabajo->fecha,
            'type' => $unidad->type,
            'kilometraje' => $ordenTrabajo->kilometraje,
            'tb_id' => $unidad->tb_id,
            'nombre' => $unidad->nombre,
            
            
            'detalles' => $ordenTrabajo->detalles,
            'qrCode' => $qrCode,
            'firmaTecnico' => $firmaTecnicoPath,
            'firmaConductor' => $firmaConductorPath,
        ];

        $pdf = PDF::loadView('pdf.orden_trabajo', $data);

        if ($request->query('view')) {
            return response($pdf->stream('orden_trabajo_' . $ordenTrabajo->no_orden . '.pdf'))
                ->header('Content-Type', 'application/pdf');
        }

        return $pdf->download('orden_trabajo_' . $ordenTrabajo->no_orden . '.pdf');
    }

    public function verDetalle($id_orden_trabajo)
    {
        $ordenTrabajo = Orden::findOrFail($id_orden_trabajo);
        $unidad = Unidad::find($ordenTrabajo->id_unidad);

        return view('orden.detalle', compact(
            'ordenTrabajo',
            'unidad'
        ));
    }

    public function signature(Request $request)
    {
        $request->validate([
            'orden_id' => 'required|exists:orden_trabajo,id_orden_trabajo',
            'firma_tecnico' => 'required|string',
            'firma_conductor' => 'required|string',
        ]);

        $fecha = now()->format('Ymd_His');
        $ordenId = $request->orden_id;
        $orden = Orden::find($ordenId);

        try {
            // Firma Técnico
            $firmaTecnico = base64_decode(str_replace('data:image/png;base64,', '', $request->firma_tecnico));
            $nombreTecnico = "{$fecha}_{$orden->no_orden}_{$orden->tecnico}.png";
            Storage::disk('public')->put("firmas/{$nombreTecnico}", $firmaTecnico);

            Firma::create([
                'id_orden_trabajo' => $ordenId,
                'nombre_archivo' => $nombreTecnico,
                'tipo_firmante' => 'Tecnico',
            ]);

            // Firma Conductor
            $firmaConductor = base64_decode(str_replace('data:image/png;base64,', '', $request->firma_conductor));
            $nombreConductor = "{$fecha}_{$orden->no_orden}_{$orden->conductor}.png";
            Storage::disk('public')->put("firmas/{$nombreConductor}", $firmaConductor);

            Firma::create([
                'id_orden_trabajo' => $ordenId,
                'nombre_archivo' => $nombreConductor,
                'tipo_firmante' => 'Conductor',
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error("Paso fallido: " . $e->getMessage(), [
                'orden_id' => $ordenId,
                'error'    => $e,
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function verOrden($id)
    {
        $orden = Orden::find($id);
        $unidad = Unidad::find($orden->id_unidad);

        $data = [
            'id_orden_trabajo' => $orden->id_orden_trabajo,
            'no_orden' => $orden->no_orden,
            'tecnico' => $orden->tecnico,
            'conductor' => $orden->conductor,
            'hora_inicio' => $orden->hora_inicio,
            'hora_final' => $orden->hora_final,
            'fecha' => $orden->fecha,
            'kilometraje' => $orden->kilometraje,
            'unidad' => $unidad->unidad,
            'type' => $unidad->type,
            'tb_id' => $unidad->tb_id,
            'nombre' => $unidad->nombre,
            'trabajos_realizados' => json_decode($orden->formulario, true) ?? [],
            'detalles' => $orden->detalles,
        ];

        return view('completar', $data);
    }

    public function obtenerDatosParaModal($id_orden_trabajo)
    {
        $ordenTrabajo = Orden::findOrFail($id_orden_trabajo);
        $unidad = Unidad::find($ordenTrabajo->id_unidad);

        return response()->json([
            'ordenTrabajo' => $ordenTrabajo,
            'unidad' => $unidad,
            'detalles' => $ordenTrabajo->detalles,
        ]);
    }
}
