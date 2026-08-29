@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Editar Unidad'])
<div class="container mt-3">
    <form action="{{ route('unidad.actualizar', $unidad->id_unidad) }}" method="POST">
        @csrf
        @method('PUT')
                <!-- Nombre -->
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $unidad->nombre ?? '') }}" maxlength="100" required>
        </div>

        <!-- TB ID -->
        <div class="mb-3">
            <label for="tb_id" class="form-label">ThingsBoard ID (tb_id)</label>
            <input type="text" class="form-control" id="tb_id" name="tb_id" value="{{ old('tb_id', $unidad->tb_id ?? '') }}" maxlength="100">
        </div>

        <!-- Fecha -->
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha" value="{{ old('fecha', $unidad->fecha ?? '') }}">
        </div>

        <!-- IP (Opcional) -->
        <div class="mb-3">
            <label for="ip" class="form-label">Dirección IP (Opcional)</label>
            <input type="text" class="form-control" id="ip" name="ip" value="{{ old('ip', $unidad->ip ?? '') }}" maxlength="45" placeholder="Ej: 192.168.1.10">
        </div>

        <!-- Tipo de Dispositivo -->
        <div class="mb-3">
            <label for="tipo_dispositivo_id" class="form-label">Tipo de Dispositivo</label>
            <select class="form-control" id="tipo_dispositivo_id" name="tipo_dispositivo_id" required>
                <option value="" disabled selected>Seleccione un tipo</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}" {{ old('tipo_dispositivo_id', $unidad->tipo_dispositivo_id ?? '') == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->nombre }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Si no encuentras el tipo, créalo en <a href="{{ route('tipos.index') }}">Tipos de Dispositivos</a>.</small>
        </div>

        <!-- Estado -->
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-control" id="estado" name="estado">
                <option value="1" {{ old('estado', $unidad->estado) == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('estado', $unidad->estado) == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <!-- Botón de enviar -->
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection
