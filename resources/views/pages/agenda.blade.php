@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Agenda'])
    <style>
        #calendar {
            width: 100%;
            height: 605px;
        }

        @media (max-width: 768px) {
            #calendar {
                height: 550px;
            }
        }

        .fc-toolbar-title {
            color: #343a40;
            font-size: 1.5rem;
            text-transform: capitalize;
        }
    </style>

    <div class="modal fade" id="mantenimientoModal" tabindex="-1" role="dialog" aria-labelledby="modalTitulo"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitulo">Programar Mantenimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <button type="button" id="deleteBtn" class="btn btn-danger ms-2" style="display: none;" title="Eliminar Mantenimiento Programado"><i class="fa-regular fa-trash-can"></i></button>
                    <button type="button" id="doMaintenanceBtn" class="btn btn-success ms-2" style="display: none;" title="Hacer Mantenimiento"><i class="fa-solid fa-wrench"></i> Hacer Mantenimiento</button>
                    <span id="statusRealizado" class="text-success ms-2 fw-bold" style="display: none; align-self: center;"><i class="fa-solid fa-check-circle"></i> Realizado</span>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titulo">Título</label>
                        <input type="text" id="titulo" class="form-control" placeholder="Escriba un Titulo">
                    </div>

                    <div class="mb-3">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" class="form-control" placeholder="Escriba una descripción..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_inicio">Fecha Inicio</label>
                        <input type="datetime-local" id="fecha_inicio" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="fecha_fin">Fecha Fin</label>
                        <input type="datetime-local" id="fecha_fin" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="unidades">Unidad</label>
                        <select id="unidades" class="form-control" multiple="multiple" placeholder="Elija una unidad" style="width: 100%;">
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="adjunto">Archivos Adjuntos (Opcional)</label>
                        <input type="file" id="adjunto" name="adjuntos[]" class="form-control" accept="image/*,.pdf" multiple>
                        <div id="adjunto_container" class="mt-2 d-flex flex-wrap gap-3" style="display: none !important;">
                            <!-- Las miniaturas se inyectarán aquí por JS -->
                        </div>
                        <div id="eliminar_archivos_container"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="saveBtn">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>



    <div class="container-fluid py-4">
        <div class="row mt-1">
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="card z-index-2 h-100">
                    <div class="card-body p-3">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'dayGridMonth',
                editable: true,
                selectable: true,
                events: '/agenda/listar',
                contentHeight: 'auto',
                aspectRatio: 1.6,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listYear'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Año'
                },
                titleFormat: {
                    year: 'numeric',
                    month: 'long'
                },

                dateClick: function(info) {
                    let now = new Date().toISOString().slice(0, 16);
                    let today = new Date().toISOString().split('T')[0];

                    if (info.dateStr < today) {

                        Toast.fire({
                            icon: "error",
                            title: "No se pueden programar mantenimientos en fechas pasadas."
                        });
                        return;
                    }

                    // Mostrar el botón de eliminación solo cuando se edite un mantenimiento
                    document.getElementById('deleteBtn').style.display = 'none';

                    document.getElementById('modalTitulo').textContent = 'Programar Mantenimiento';
                    document.getElementById('titulo').value = '';
                    document.getElementById('descripcion').value = '';
                    document.getElementById('fecha_inicio').value = `${info.dateStr}T08:00`;
                    document.getElementById('fecha_inicio').min = now;
                    document.getElementById('fecha_fin').value = '';
                    document.getElementById('fecha_fin').min = now;
                    document.getElementById('adjunto').value = '';
                    document.getElementById('adjunto_container').innerHTML = '';
                    document.getElementById('adjunto_container').style.setProperty('display', 'none', 'important');
                    document.getElementById('eliminar_archivos_container').innerHTML = '';



                    $('#unidades').val(null).trigger('change');
                    document.getElementById('saveBtn').setAttribute('data-id', '');
                    document.getElementById('deleteBtn').style.display = 'none';
                    document.getElementById('doMaintenanceBtn').style.display = 'none';
                    document.getElementById('statusRealizado').style.display = 'none';

                    // Cargar unidades desde el servidor
                    $.ajax({
                        url: '/obtener-unidades',
                        method: 'GET',
                        success: function(data) {
                            $('#unidades').empty();
                            data.forEach(function(unidad) {
                                $('#unidades').append(new Option(unidad
                                    .nombre, unidad.id_unidad));
                            });
                            $('#unidades').select2({
                                placeholder: "Seleccione una unidad",
                                theme: "classic"
                            });
                        }
                    });

                    let myModal = new bootstrap.Modal(document.getElementById('mantenimientoModal'));
                    myModal.show();
                },

                eventClick: function(info) {
                    let evento = info.event;
                    let now = new Date().toISOString().slice(0, 16);

                    document.getElementById('modalTitulo').textContent = 'Mantenimiento Agendado';
                    document.getElementById('titulo').value = evento.title;
                    document.getElementById('descripcion').value = evento.extendedProps.description ||
                        '';
                    document.getElementById('fecha_inicio').value = evento.start.toLocaleString('sv-SE')
                        .replace(' ', 'T');
                    document.getElementById('fecha_fin').value = evento.end ? evento.end.toLocaleString(
                        'sv-SE').replace(' ', 'T') : '';
                    document.getElementById('fecha_inicio').min = now;
                    document.getElementById('fecha_fin').min = now;
                    document.getElementById('saveBtn').setAttribute('data-id', evento.id);
                    document.getElementById('adjunto').value = '';
                    document.getElementById('adjunto_container').innerHTML = '';
                    document.getElementById('eliminar_archivos_container').innerHTML = '';

                    if (evento.extendedProps.adjuntos && evento.extendedProps.adjuntos.length > 0) {
                        document.getElementById('adjunto_container').style.setProperty('display', 'flex', 'important');
                        
                        evento.extendedProps.adjuntos.forEach(function(archivo, index) {
                            let extension = archivo.split('.').pop().toLowerCase();
                            let isImage = ['jpeg', 'jpg', 'gif', 'png'].includes(extension);
                            
                            let archivoUrl = '/storage/' + archivo;
                            let html = `
                                <div class="position-relative d-inline-block" id="adjunto_item_${index}">
                                    <a href="${archivoUrl}" target="_blank" style="text-decoration: none;">
                                        ${isImage 
                                            ? `<img src="${archivoUrl}" class="rounded img-thumbnail" style="height: 120px; width: 120px; object-fit: cover;">`
                                            : `<div class="d-flex align-items-center justify-content-center bg-light rounded img-thumbnail" style="height: 120px; width: 120px;"><span style="font-size: 40px;">📄</span></div>`
                                        }
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm position-absolute rounded-circle p-1" style="top: -10px; right: -10px; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; z-index: 10;" 
                                        onclick="eliminarArchivo(event, '${archivo}', 'adjunto_item_${index}')" title="Eliminar">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            `;
                            document.getElementById('adjunto_container').insertAdjacentHTML('beforeend', html);
                        });
                    } else {
                        document.getElementById('adjunto_container').style.setProperty('display', 'none', 'important');
                    }

                    if (evento.extendedProps.estado === 0 || evento.extendedProps.estado === '0' || evento.extendedProps.estado === false) {
                        // Cambiar el título a 'Mantenimiento Realizado'
                        document.getElementById('modalTitulo').innerText = 'Mantenimiento Realizado';
                        document.getElementById('deleteBtn').style.display = 'none';
                        document.getElementById('doMaintenanceBtn').style.display = 'none';
                        document.getElementById('statusRealizado').style.display = 'inline-block';
                        document.getElementById('saveBtn').style.display = 'inline-block';
                    } else {
                        document.getElementById('deleteBtn').style.display = 'inline-block';
                        document.getElementById('doMaintenanceBtn').style.display = 'inline-block';
                        document.getElementById('statusRealizado').style.display = 'none';
                        document.getElementById('saveBtn').style.display = 'inline-block';
                    }
                    
                    document.getElementById('doMaintenanceBtn').onclick = function() {
                        let base_url = '/orden';
                        let desc = encodeURIComponent(evento.title + '\n' + (evento.extendedProps.description || ''));
                        // Si hay unidades seleccionadas, pasamos la primera para pre-seleccionar
                        let params = '?detalles=' + desc + '&agenda_id=' + encodeURIComponent(evento.id);
                        if (evento.extendedProps.unidades && evento.extendedProps.unidades.length > 0) {
                            params += '&unidad_id=' + evento.extendedProps.unidades[0];
                        }
                        window.location.href = base_url + params;
                    };

                    // Cargar unidades desde el servidor y seleccionarlas en el select
                    $.ajax({
                        url: '/obtener-unidades',
                        method: 'GET',
                        success: function(data) {
                            $('#unidades').empty();
                            data.forEach(function(unidad) {
                                $('#unidades').append(new Option(unidad
                                    .nombre, unidad.id_unidad));
                            });
                            $('#unidades').val(evento.extendedProps.unidades).trigger(
                                'change');
                            $('#unidades').select2({
                                placeholder: "Seleccione una unidad",
                                theme: "classic"
                            });
                        }
                    });

                    let myModal = new bootstrap.Modal(document.getElementById('mantenimientoModal'));
                    myModal.show();
                }
            });

            calendar.render();

            // Validación antes de guardar
            document.getElementById('saveBtn').addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                let titulo = document.getElementById('titulo').value;
                let descripcion = document.getElementById('descripcion').value;
                let fecha_inicio = document.getElementById('fecha_inicio').value;
                let fecha_fin = document.getElementById('fecha_fin').value;
                let unidades = $('#unidades').val();
                let now = new Date().toISOString().slice(0, 16);

                if (!titulo || !fecha_inicio || !unidades.length) {
                    //alert('Todos los campos obligatorios deben ser llenados.');
                    Toast.fire({
                        icon: "warning",
                        title: "Todos los campos obligatorios deben ser llenados."
                    });
                    return;
                }

                // Validación de fechas y horas
                if (fecha_fin && fecha_fin < fecha_inicio) {
                    //alert('La fecha/hora de fin no puede ser menor que la fecha/hora de inicio.');
                    Toast.fire({
                        icon: "warning",
                        title: "La fecha/hora de fin no puede ser menor que la fecha/hora de inicio."
                    });
                    return;
                }

                let url = id ? `/agendas/${id}` : '/agendas';
                let method = id ? 'POST' : 'POST'; // We use POST for both, with _method=PUT for updates because of FormData

                let formData = new FormData();
                formData.append('titulo', titulo);
                formData.append('descripcion', descripcion);
                formData.append('fecha_inicio', fecha_inicio);
                if (fecha_fin) formData.append('fecha_fin', fecha_fin);
                formData.append('unidades', unidades.join(',')); // Enviar como string separada por comas

                if (id) {
                    formData.append('_method', 'PUT');
                }

                let adjuntosFiles = document.getElementById('adjunto').files;
                if (adjuntosFiles.length > 0) {
                    for (let i = 0; i < adjuntosFiles.length; i++) {
                        formData.append('adjuntos[]', adjuntosFiles[i]);
                    }
                }

                // Agregar los archivos a eliminar si los hay
                let inputsEliminar = document.querySelectorAll('input[name="eliminar_archivos[]"]');
                inputsEliminar.forEach(input => {
                    formData.append('eliminar_archivos[]', input.value);
                });

                fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    }).then(response => response.json())
                    .then(data => {
                        // Mostrar la respuesta del servidor en el Toast
                        Toast.fire({
                            icon: "success",
                            title: data
                                .message // Aquí es donde se coloca el mensaje del servidor
                        });

                        let modal = bootstrap.Modal.getInstance(document.getElementById(
                            'mantenimientoModal'));
                        modal.hide();
                        calendar.refetchEvents();
                    }).catch(error => {
                        // En caso de error, mostrar mensaje de error en el Toast
                        Toast.fire({
                            icon: "error",
                            title: "Hubo un error al procesar la solicitud."
                        });
                    });
            });

            // Función para eliminar el mantenimiento
            document.getElementById('deleteBtn').addEventListener('click', function() {
                let id = document.getElementById('saveBtn').getAttribute('data-id');
                if (!id) {
                    Toast.fire({
                        icon: "warning",
                        title: "No se ha seleccionado un mantenimiento para eliminar."
                    });
                    return;
                }

                // Confirmación de eliminación
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Este mantenimiento será eliminado permanentemente!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/agendas/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }).then(response => response.json())
                            .then(data => {
                                Toast.fire({
                                    icon: "success",
                                    title: data.mensaje
                                });

                                let modal = bootstrap.Modal.getInstance(document.getElementById(
                                    'mantenimientoModal'));
                                modal.hide();
                                calendar.refetchEvents();
                            }).catch(error => {
                                Toast.fire({
                                    icon: "error",
                                    title: "Hubo un error al eliminar el mantenimiento."
                                });
                            });
                    }
                });
            });


        });

        function eliminarArchivo(event, filePath, elementId) {
            event.preventDefault();
            // Agregar el path al form hidden
            let container = document.getElementById('eliminar_archivos_container');
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'eliminar_archivos[]';
            input.value = filePath;
            container.appendChild(input);

            // Ocultar miniatura
            document.getElementById(elementId).style.display = 'none';
        }
    </script>
@endpush
