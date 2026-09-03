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
            <input type="hidden" id="incidencia_id" name="incidencia_id" value="{{ request('incidencia_id') }}" />

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
                            <option value="{{ $unidad->id_unidad }}" data-tbid="{{ $unidad->tb_id }}" data-tipo="{{ $unidad->type }}" data-fecha="{{ $unidad->fecha }}" {{ request('unidad_id') == $unidad->id_unidad ? 'selected' : '' }}>{{ $unidad->nombre }} {{ $unidad->type }} - {{ $unidad->tb_id }}</option>
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
                    <textarea id="detalles" name="detalles" class="form-control" rows="10" placeholder="Describa todo el trabajo realizado en la unidad..." required>{{ request('detalles') }}</textarea>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label" for="adjuntos_input">Archivos Adjuntos / Fotos (Opcional)</label>
                    <input type="file" id="adjuntos_input" class="form-control" accept="image/*,.pdf" multiple>
                    <input type="file" id="adjuntos" name="adjuntos[]" multiple style="display: none;">
                    <div id="preview-container" class="d-flex flex-wrap mt-3" style="gap: 10px;"></div>
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
            if(selected) {
                document.getElementById('folio').textContent = selected.getAttribute('data-tbid') || '----';
                document.getElementById('codigo').textContent = selected.getAttribute('data-tipo') || '----';
                document.getElementById('ubicacion').textContent = selected.getAttribute('data-fecha') || '----';
            }
        });
        
        // Disparar el evento change si hay un valor pre-seleccionado
        if(unidadSelect.value) {
            $(unidadSelect).trigger('change');
        }
    }

    const adjuntosInput = document.getElementById('adjuntos_input');
    const hiddenAdjuntos = document.getElementById('adjuntos');
    const previewContainer = document.getElementById('preview-container');
    let selectedFiles = [];

    if(adjuntosInput) {
        adjuntosInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            files.forEach(file => {
                selectedFiles.push(file);
                
                const previewEl = document.createElement('div');
                previewEl.className = 'position-relative border p-1 rounded d-flex flex-column align-items-center justify-content-center';
                previewEl.style.width = '100px';
                previewEl.style.height = '100px';
                previewEl.style.overflow = 'hidden';
                
                const isImage = file.type.startsWith('image/');
                
                if (isImage) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewEl.innerHTML = `
                            <img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;" class="rounded">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 p-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; line-height: 1;" onclick="removeFile('${file.name}', this)">
                                &times;
                            </button>
                            <div class="text-truncate w-100 text-center mt-1" style="font-size: 10px; position: absolute; bottom: 0; background: rgba(255,255,255,0.8);" title="${file.name}">${file.name}</div>
                        `;
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewEl.innerHTML = `
                        <div class="d-flex align-items-center justify-content-center h-100 w-100 bg-light rounded"><span style="font-size: 24px;">📄</span></div>
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 p-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; line-height: 1;" onclick="removeFile('${file.name}', this)">
                            &times;
                        </button>
                        <div class="text-truncate w-100 text-center mt-1" style="font-size: 10px; position: absolute; bottom: 0; background: rgba(255,255,255,0.8);" title="${file.name}">${file.name}</div>
                    `;
                }
                
                previewContainer.appendChild(previewEl);
            });
            
            updateHiddenInput();
            adjuntosInput.value = ''; // allow selecting same files again
        });
    }
    
    window.removeFile = function(fileName, btn) {
        selectedFiles = selectedFiles.filter(file => file.name !== fileName);
        btn.parentElement.remove();
        updateHiddenInput();
    }
    
    function updateHiddenInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        hiddenAdjuntos.files = dataTransfer.files;
    }
});
</script>
@endsection
