@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Nueva Orden'])

<style>
    .descripcion
    {
        display: none;
        border: 2px solid #8392ab !important;
        border-radius: 5px !important;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container" id="wrap-form">
    <form id="orden" method="POST" action="{{ route('guardar.orden') }}" enctype="multipart/form-data">
        @csrf
        <fieldset>
            <input type="hidden" id="formulario_json" name="formulario_json" value="" />

            <div class="row">
                <div class="col-12 mb-3 mt-3">
                    <label for="tipoMantenimiento" class="form-label">Tipo de Mantenimiento</label>
                    <select id="tipoMantenimiento" name="tipoMantenimiento" class="form-select form-select-lg mb-0" aria-label="Large select example" required>
                        <option value="Preventivo" selected>Mantenimiento Preventivo</option>
                        <option value="Correctivo">Mantenimiento Correctivo</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 mb-3 mt-3">
                    <h5>Información del Dispositivo</h5>

                    <label for="unidad" class="form-label">Dispositivo</label>
                    <select id="unidad" name="unidad" class="form-select select2-dispositivo mb-0" required style="width: 100%;">
                        <option value="" disabled selected hidden>Seleccione Dispositivo</option>
                        @foreach ($unidades as $unidad)
                            <option value="{{ $unidad->id_unidad }}" data-tbid="{{ $unidad->tb_id }}" data-tipo="{{ $unidad->type }}" data-fecha="{{ $unidad->fecha }}">{{ $unidad->nombre }} {{ $unidad->type }} - {{ $unidad->tb_id }}</option>
                        @endforeach
                    </select>

                    

                    <p class="mt-4"><strong>TB ID</strong>: <span id="folio">----</span></p>
                    <p><strong>Tipo</strong>: <span id="codigo">----</span></p>
                    <p><strong>Fecha</strong>: <span id="ubicacion">----</span></p>
                </div>


                <div class="col-12 col-md-6 mb-3 mt-3">
                    <h5>Técnico Encargado</h5>
                    <label for="tecnico" class="form-label">Técnico encargado</label>
                    <input type="text" id="tecnico" name="tecnico" class="form-control" placeholder="Nombre Técnico" required>

                    <label for="fechaOrden" class="form-label">Fecha</label>
                    <input type="date" id="fechaOrden" name="fechaOrden" class="form-control" required>

                    

                    
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12 mb-3">
                    <label for="detalles" class="form-label">Detalles del Trabajo Realizado</label>
                    <textarea id="detalles" name="detalles" class="form-control" rows="10" placeholder="Describa todo el trabajo realizado en la unidad..." required></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-secondary mt-3">Completar</button>
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
    if(unidadSelect) {
        $(unidadSelect).on('change', function() {
            var selected = this.options[this.selectedIndex];
            document.getElementById('folio').textContent = selected.getAttribute('data-tbid') || '----';
            document.getElementById('codigo').textContent = selected.getAttribute('data-tipo') || '----';
            document.getElementById('ubicacion').textContent = selected.getAttribute('data-fecha') || '----';
        });
    }
});
</script>
@endsection
