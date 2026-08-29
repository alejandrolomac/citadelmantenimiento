<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidenciaController;
use App\Models\Incidencia;
use Illuminate\Support\Facades\File;

Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);

    if (! File::exists($fullPath)) {
        abort(404);
    }

    return Response::file($fullPath, [
        'Cache-Control' => 'public, max-age=604800',
    ]);
})->where('path', '.*');


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login2', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::view('/', 'pages.dashboard')->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getDashboardStats']);
    Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData']);
    Route::get('/dashboard/events', [DashboardController::class, 'getEvents']);
    Route::get('/dashboard/next-events', [DashboardController::class, 'getNextMaintenances']);
    Route::get('/dashboard/urgent-incidencias', [DashboardController::class, 'getUrgentIncidencias']);

    Route::view('/mantenimiento', 'pages.mantenimiento')->name('mantenimiento')->middleware('can:Ver Mantenimiento');
    Route::get('orden/{id_orden_trabajo}/exportar-pdf', [OrdenController::class, 'exportarPDF'])->middleware('can:Ver Mantenimiento');
    Route::get('orden/{id_orden_trabajo}/detalle', [OrdenController::class, 'verDetalle'])->middleware('can:Ver Mantenimiento');
    Route::get('orden/{id_orden_trabajo}/detalle-modal', [OrdenController::class, 'obtenerDatosParaModal'])->middleware('can:Ver Mantenimiento');

    //Agenda
    Route::view('/agenda', 'pages.agenda')->name('agenda')->middleware('can:Ver Agenda');
    Route::resource('agendas', AgendaController::class)->middleware('can:Ver Agenda');
    Route::get('/agenda/listar', [AgendaController::class, 'listar'])->middleware('can:Ver Agenda');
    Route::get('/obtener-unidades', [AgendaController::class, 'obtenerUnidades'])->middleware('can:Ver Agenda');


    Route::view('/unidades', 'pages.unidades')->name('unidades')->middleware('can:Ver Dispositivos');
    Route::view('/perfil', 'pages.perfil')->name('perfil');

    // Usuarios
    Route::resource('usuario', UserController::class)->middleware('can:Ver Usuarios');
    Route::view('/usuarios', 'pages.usuarios')->name('usuarios')->middleware('can:Ver Usuarios');
    Route::get('usuarios/data', [UserController::class, 'getUsuarios'])->middleware('can:Ver Usuarios');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/rol-usuario', [UserController::class, 'obtenerRoles']);
    Route::post('/change-pass', [UserController::class, 'changePassword']);

    // Roles
    Route::resource('rol', RolController::class)->middleware('can:Ver Roles');
    Route::view('/roles', 'pages.roles')->name('roles')->middleware('can:Ver Roles');
    Route::get('roles/data', [RolController::class, 'getData'])->middleware('can:Ver Roles');
    Route::get('/role/{id}', [RolController::class, 'show'])->middleware('can:Editar Roles');
    Route::post('/roles/{id}/assign-permissions', [RolController::class, 'assignPermissions'])->middleware('can:Editar Roles');
    Route::get('/roles/permissions', [RolController::class, 'getPermissions'])->middleware('can:Editar Roles');

    // Reportes    
    Route::view('/reportes', 'pages.reportes')->name('reportes')->middleware('can:Ver Reportes');
    Route::get('/reportes/prev/chart-data', [ReportesController::class, 'cantMantPreventivo'])->middleware('can:Ver Reportes');
    Route::get('/reportes/chart-data', [ReportesController::class, 'chartData'])->middleware('can:Ver Reportes');
    Route::get('/reportes/tecnicosPreventivos', [ReportesController::class, 'getMantTecnicosPreventivo'])->middleware('can:Ver Reportes');
    Route::get('/reportes/tecnicosCorrectivos', [ReportesController::class, 'getMantTecnicosCorrectivo'])->middleware('can:Ver Reportes');
    //Route::get('/reporte/mantenimiento/data', [ReportesController::class, 'getMantenimientos']);
    Route::get('/reporte/mantTablePrev/data', [ReportesController::class, 'obtenerMantenimientoPrev'])->middleware('can:Ver Reportes');
    Route::get('/reporte/mantTableCorrec/data', [ReportesController::class, 'obtenerMantenimientoCorrec'])->middleware('can:Ver Reportes');
    Route::get('/reportes/tipos-mantenimiento', [ReportesController::class, 'tiposMantenimiento'])->middleware('can:Ver Reportes');
    Route::get('/reportes/kilometraje-mantenimiento', [ReportesController::class, 'kilometrajeMantenimiento'])->middleware('can:Ver Reportes');
    Route::get('/reportes/informe-inactividad', [ReportesController::class, 'informeInactividad'])->middleware('can:Ver Reportes');
    Route::get('/reportes/tendencia-mantenimiento', [ReportesController::class, 'tendenciaMantenimiento'])->middleware('can:Ver Reportes');
    Route::get('/reportes/eficiencia-tecnico', [ReportesController::class, 'eficienciaTecnico'])->middleware('can:Ver Reportes');
    Route::get('/reportes/resumen-unidad', [ReportesController::class, 'resumenUnidad'])->middleware('can:Ver Reportes');

    // Incidencias
    Route::resource('incidencia', IncidenciaController::class)->middleware('can:Ver Incidencias');
    Route::view('/incidencias', 'pages.incidencias')->name('incidencias')->middleware('can:Ver Incidencias');
    Route::get('incidencias/data', [IncidenciaController::class, 'getData'])->middleware('can:Ver Incidencias');
    Route::get('/incidencias/{id}', [IncidenciaController::class, 'show'])->middleware('can:Ver Incidencias');
    Route::get('/incidencias-unidad', [IncidenciaController::class, 'obtenerUnidades']);
    Route::get('/incidencias-usuarios', [IncidenciaController::class, 'obtenerUsuarios']);

    //Orden
    Route::get('/orden', function () {
        $unidades = DB::table('unidad')->where('estado', true)->get();
        return view('home', compact('unidades'));
    })->middleware('can:Crear Mantenimiento');
    Route::get('/ordenes/data', [OrdenController::class, 'getData'])->name('ordenes.data')->middleware('can:Ver Mantenimiento');

    //Guardar ordenes
    Route::post('/guardar-orden', [OrdenController::class, 'guardar'])->name('guardar.orden')->middleware('can:Crear Mantenimiento');

    //Editar ordenes
    Route::get('/orden/{id}/editar', [OrdenController::class, 'editar'])->name('orden.editar')->middleware('can:Editar Mantenimiento');
    Route::put('/orden/{id}', [OrdenController::class, 'actualizar'])->name('orden.actualizar')->middleware('can:Editar Mantenimiento');


    //Mostrar info de unidades
    Route::get('/unidad/{id}', function ($id) {
        $unidad = DB::table('unidad')->where('id_unidad', $id)->first();
        if (!$unidad) {
            return response()->json(['error' => 'Unidad no encontrada'], 404);
        }
        return response()->json($unidad);
    })->name('unidad.show')->middleware('can:Ver Dispositivos');

    Route::get('detalle_unidad/{id}', [UnidadController::class, 'verUnidad'])->middleware('can:Ver Dispositivos');
    Route::get('/unidades/data', [UnidadController::class, 'getData'])->name('unidad.getData')->middleware('can:Ver Dispositivos');
    Route::get('/incidencias_unidad/{id}', [UnidadController::class, 'incidencias_unidad'])->middleware('can:Ver Incidencias'); 

    //Pagina luego de guardar
    Route::get('/registro-correcto', function () {
        return view('thanks');
    })->name('thanks');

    //Eliminar Orden
    Route::delete('/orden/delete/{id}', [\App\Http\Controllers\OrdenController::class, 'destroy'])->middleware('can:Eliminar Mantenimiento');

    //Subir fotos
    Route::post('/upload-photos', [OrdenController::class, 'uploadPhotos'])->name('upload.photos')->middleware('can:Editar Mantenimiento');

    //Registrar unidad
    Route::get('/unidad', [UnidadController::class, 'create'])->middleware('can:Crear Dispositivos');
    Route::post('/nueva-unidad', [UnidadController::class, 'store'])->name('unidad.store')->middleware('can:Crear Dispositivos');

    Route::get('/unidades/data', [UnidadController::class, 'getData'])->name('unidad.data')->middleware('can:Ver Dispositivos');

    //Editar unidad
    Route::get('/unidad/{id}/editar', [UnidadController::class, 'editar'])->name('unidad.editar')->middleware('can:Editar Dispositivos');
    Route::put('/unidad/{id}/actualizar', [UnidadController::class, 'actualizar'])->name('unidad.actualizar')->middleware('can:Editar Dispositivos');

    //Tipos Dispositivos
    Route::resource('tipos', \App\Http\Controllers\TipoDispositivoController::class)->middleware('can:Ver Tipos Dispositivos');
    Route::delete('/tipos-archivo/{id}', [\App\Http\Controllers\TipoDispositivoController::class, 'deleteFile'])->name('tipos.deleteFile')->middleware('can:Editar Tipos Dispositivos');

    //Firmas
    Route::get('/orden/{id}/completar-registro', [OrdenController::class, 'verOrden'])->name('orden.completar')->middleware('can:Editar Mantenimiento');
    Route::post('/firmas/guardar', [OrdenController::class, 'signature'])->name('firmas.guardar')->middleware('can:Editar Mantenimiento');
});
