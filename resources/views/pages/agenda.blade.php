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
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitulo">Programar Mantenimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <button type="button" id="deleteBtn" class="btn btn-danger ms-2" style="display: none;"><i class="fa-regular fa-trash-can"></i></button>
                </div>
                <div class="modal-body">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" class="form-control" placeholder="Escriba un Titulo">

                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" class="form-control" placeholder="Escriba una descripción..."></textarea>

                    <label for="fecha_inicio">Fecha Inicio</label>
                    <input type="datetime-local" id="fecha_inicio" class="form-control">

                    <label for="fecha_fin">Fecha Fin</label>
                    <input type="datetime-local" id="fecha_fin" class="form-control">

                    <label for="unidades">Unidad</label>
                    <select id="unidades" class="form-control" multiple="multiple" placeholder="Elija una unidad">
                    </select>

                    <label class="mt-2" for="adjunto">Archivo Adjunto (Opcional)</label>
                    <input type="file" id="adjunto" class="form-control" accept="image/*,.pdf">
                    <div id="adjunto_container" class="mt-2" style="display: none;">
                        <a href="#" id="adjunto_link" target="_blank" class="btn btn-sm btn-info mb-0">Ver Archivo Adjunto</a>
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
                    document.getElementById('adjunto_container').style.display = 'none';



                    $('#unidades').val(null).trigger('change');
                    document.getElementById('saveBtn').setAttribute('data-id', '');

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

                    document.getElementById('modalTitulo').textContent = 'Editar Mantenimiento';
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

                    if (evento.extendedProps.adjunto) {
                        document.getElementById('adjunto_container').style.display = 'block';
                        document.getElementById('adjunto_link').href = evento.extendedProps.adjunto;
                    } else {
                        document.getElementById('adjunto_container').style.display = 'none';
                        document.getElementById('adjunto_link').href = '#';
                    }

                    // Mostrar el botón de eliminación solo cuando se edite un mantenimiento
                    document.getElementById('deleteBtn').style.display = 'inline-block';

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

                let adjunto = document.getElementById('adjunto').files[0];
                if (adjunto) {
                    formData.append('adjunto', adjunto);
                }

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
    </script>
@endpush
