@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Editar Orden'])

<div class="container" id="wrap-form">
    <form id="orden" method="POST" action="{{ route('orden.actualizar', $orden->id_orden_trabajo) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <fieldset>
            <input type="hidden"
                name="formulario_json"
                id="formulario_json"
                value="{{ $orden->formulario }}">

            <!-- Datos generales -->
            <div class="row">
                <div class="col-12 mb-3 mt-3">
                    <label for="tipoMantenimiento" class="form-label">Tipo de Mantenimiento</label>
                    <select id="tipoMantenimiento" name="tipoMantenimiento" class="form-select form-select-lg mb-0"
                        aria-label="Large select example">
                        <option disabled>Tipo de Mantenimiento</option>
                        <option value="Preventivo" {{ $orden->tipo_mantenimiento == 'Preventivo' ? 'selected' : '' }}>
                            Mantenimiento Preventivo</option>
                        <option value="Correctivo" {{ $orden->tipo_mantenimiento == 'Correctivo' ? 'selected' : '' }}>
                            Mantenimiento Correctivo</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 mb-3 mt-3">
                    <h5>Información del Dispositivo</h5>
                    <label for="unidad" class="form-label">Dispositivo</label>
                    <select id="unidad" name="unidad" class="form-select select2-dispositivo mb-0" style="width: 100%;">
                        <option disabled>Seleccione Dispositivo</option>
                        @foreach ($unidades as $uni)
                        <option value="{{ $uni->id_unidad }}" data-tbid="{{ $uni->tb_id }}" data-tipo="{{ $uni->type }}" data-fecha="{{ $uni->fecha }}" {{ $orden->id_unidad == $uni->id_unidad ? 'selected' : '' }}>{{ $uni->nombre }} {{ $uni->type }} - {{ $uni->tb_id }}</option>
                        @endforeach
                    </select>
                    <p class="mt-4"><strong>TB ID</strong>: <span id="folio">----</span></p>
                    <p><strong>Tipo</strong>: <span id="codigo">----</span></p>
                    <p><strong>Fecha</strong>: <span id="ubicacion">----</span></p>
                </div>
                <div class="col-12 col-md-6 mb-3 mt-3">
                    <h5>Técnico Encargado</h5>
                    <label for="tecnico" class="form-label">Técnico encargado</label>
                    <input type="text" id="tecnico" name="tecnico" class="form-control"
                        placeholder="Nombre Técnico" value="{{ $orden->tecnico }}">
                    <label for="fechaOrden" class="form-label">Fecha</label>
                    <input type="date" id="fechaOrden" name="fechaOrden" class="form-control"
                        value="{{ $orden->fecha }}">
                </div>
            </div>


            <div class="row mt-5">
                <div class="col-12 mb-3">
                    <label for="detalles" class="form-label">Detalles del Trabajo Realizado</label>
                    <textarea id="detalles" name="detalles" class="form-control" rows="10" placeholder="Describa todo el trabajo realizado en la unidad..." required>{{ $orden->detalles ?? '' }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Guardar</button>
            <a href="{{ url('/orden/' . $orden->id_orden_trabajo . '/completar-registro') }}"
                class="btn btn-warning mt-3" title="Resumen / Imprimir">Resumen / Imprimir</a>
            <a type="button" class="btn btn-default mt-3" href="/mantenimiento" title="Cancelar">Cancelar</a>
        </fieldset>
    </form>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.select2-dispositivo').select2({
        placeholder: "Buscar dispositivo...",
        allowClear: true,
        width: 'resolve'
    });

    var unidadSelect = document.getElementById('unidad');
    
    function updateDetails() {
        var selected = unidadSelect.options[unidadSelect.selectedIndex];
        if(!selected || selected.disabled) return;
        document.getElementById('folio').textContent = selected.getAttribute('data-tbid') || '----';
        document.getElementById('codigo').textContent = selected.getAttribute('data-tipo') || '----';
        document.getElementById('ubicacion').textContent = selected.getAttribute('data-fecha') || '----';
    }

    if(unidadSelect) {
        $(unidadSelect).on('change', updateDetails);
        // Run once on load to populate existing selection
        updateDetails();
    }
});
</script>

@endsection
