<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\IncidenciaApiController;
use App\Http\Controllers\Api\DispositivoApiController;

//Route::post('login', [AuthController::class, 'login']);
//Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('incidencias', [IncidenciaApiController::class, 'index']);
Route::post('incidencias', [IncidenciaApiController::class, 'store']);
Route::patch('incidencias/{id}', [IncidenciaApiController::class, 'update']);

Route::get('dispositivos', [DispositivoApiController::class, 'index']);
Route::post('dispositivos', [DispositivoApiController::class, 'store']);
Route::patch('dispositivos/{id}', [DispositivoApiController::class, 'update']);
