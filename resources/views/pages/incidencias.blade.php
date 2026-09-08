@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Incidencias'])
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
/>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<style>
    #tableIncidencias {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 16px;
        text-align: left;
    }

    #tableIncidencias th,
    #tableIncidencias td {
        padding: 12px 15px;
    }

    #tableIncidencias thead tr {
        background-color: #ffffff;
        color: #000000;
        text-align: left;
        font-weight: bold;
    }

    #tableIncidencias tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    #tableIncidencias tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    #tableIncidencias tbody tr:last-of-type {
        border-bottom: 2px solid #000000;
    }

    .center-image {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .center-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        /* Ajusta según tus necesidades */
    }

    .logo-image {
        width: 100px; /* Tamaño deseado para pantallas grandes */
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
        box-sizing: border-box;
    }

    /* Media query para pantallas más pequeñas, como dispositivos móviles */
    @media (max-width: 768px) {
        .logo-image {
            width: 100%; /* Tamaño deseado para pantallas más pequeñas */
        }
    }
</style>

<!-- Modal para Crear/Editar Roles -->
<div class="modal fade" id="incidenciaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" style="color: #ffffff" id="modalTitle">Incidencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="incidenciaForm">
                    <input type="hidden" id="incidenciaId">
                    <div class="mb-3">
                        <label for="incidenciaDescripcion" class="form-label">Describa la Incidencia</label>
                        <textarea type="text" class="form-control" id="incidenciaDescripcion" placeholder="Describa la incidencia..."
                            rows="10"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="incidenciaUnidad" class="form-label">Seleccione la Unidad</label>
                        <select class="form-control" id="incidenciaUnidad" required></select>
                    </div>

                    <div class="mb-3">
                        <label for="incidenciaUsuario" class="form-label">Escriba el nombre del usuario que reportó</label>
                        <input type="text" class="form-control" id="incidenciaUsuario" placeholder="Nombre de quien reporta" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="incidenciaStatus" class="form-label">Estatus</label>
                            <select class="form-control" id="incidenciaStatus" required>
                                <option value="Abierta">Abierta</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Cerrada">Cerrada</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="incidenciaNivel" class="form-label">Nivel de Importancia</label>
                            <select class="form-control" id="incidenciaNivel" required>
                                <option value="Baja">Baja</option>
                                <option value="Normal" selected>Normal</option>
                                <option value="Urgente">Urgente</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" id="actionButton" onclick="handleRolAction()">Guardar</button>
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
  .table-fixed {
    table-layout: fixed;
    width: 100%;
  }
  #celIncidencia, 
  .table-fixed th, 
  .table-fixed td {
    white-space: normal;
    word-break: break-word;
  }
</style>


<!-- Modal para Ver las incidencias -->
<div class="modal fade" id="VerIncidencia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary2">
                <h5 class="modal-title" style="color: #ffffff" id="modalTitleVer">Incidencia</h5>
                <div>
                    <button type="button" id="resolveIncidenciaBtn" class="btn btn-success btn-sm mb-0 me-2" title="Resolver con Mantenimiento"><i class="fa-solid fa-wrench"></i> Resolver</button>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 1;"></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-fixed">
                        <tbody>
                            <tr>
                            <th class="text-center" colspan="3">Información de la Unidad:</th>
                            </tr>
                            <tr class="text-center">
                            <th>Nombre del Dispositivo:</th>
                            <th>Tipo:</th>
                            <th>TB ID:</th>
                            </tr>
                            <tr class="text-center">
                            <td id="celCodigo">N/A</td>
                            <td id="celVehiculo">N/A</td>
                            <td id="celFolio">N/A</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered table-fixed">
                        <tbody>
                            <tr class="text-center">
                                <th class="text-center">Usuario que reportó:</th>
                                <td id="celConductor">N/A</td>
                                <td id="celImg">N/A</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered table-fixed">
                        <tbody>
                            <tr class="text-center">
                                <th colspan="1">Decripción de la Incidencia:</th>
                            </tr>
                            <tr class="text-center">
                                <td id="celIncidencia">N/A</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>Incidencias</h6>
                <div class="d-flex align-items-center">
                    @can('Crear Incidencias')
                        <button type="button" class="btn bg-gradient-success mb-0" onclick="showCreateIncidenciaModal()"
                            title="Crear Incidencia">
                            <i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Nueva Incidencia
                        </button>
                    @endcan
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table id="tableIncidencias" class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>#</th>
                                <th>Unidad</th>
                                <th>Nivel</th>
                                <th>Estatus</th>
                                <th>Usuario que reportó</th>
                                <th>Fecha de Registro</th>
                                <th>Última Actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    
                    <!-- Contenedor oculto del filtro que será movido por JavaScript -->
                    <span id="customStatusFilter" style="display: none; margin-left: 15px;" class="text-sm font-weight-normal">
                        Estatus: 
                        <select id="filtroEstatus" class="form-select form-select-sm d-inline-block" style="width: auto;">
                            <option value="">Todos</option>
                            <option value="Abierta">Abierta</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Cerrada">Cerrada</option>
                        </select>
                    </span>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const userLogged = {
        id: "{{ Auth::user()->id }}",
        rol: "{{ Auth::user()->roles->first()->name ?? 'Sin Rol' }}"
    };

    $(document).ready(function() {
        $('#tableIncidencias').DataTable({
            responsive: true,
            processing: true,
            serverSide: false,
            ajax: '{{ url('incidencias/data') }}', // Nueva ruta para obtener incidencias
            columns: [{
                    data: 'id',
                    visible: false
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return row.unidad.nombre + ' - ' + row.unidad.type;
                    }
                }, // Nombre de la unidad
                {
                    data: null,
                    render: function(data, type, row) {
                        let nivel = row.nivel_importancia || 'Normal';
                        let badge = 'bg-info';
                        if (nivel === 'Baja') badge = 'bg-secondary';
                        if (nivel === 'Normal') badge = 'bg-info';
                        if (nivel === 'Urgente') badge = 'bg-danger';
                        return `<span class="badge ${badge}">${nivel}</span>`;
                    }
                }, // Nivel de Importancia
                {
                    data: null,
                    render: function(data, type, row) {
                        let status = row.status || 'Abierta';
                        let badge = status === 'Abierta' ? 'bg-danger' : (status === 'Cerrada' ? 'bg-success' : 'bg-warning');
                        return `<span class="badge ${badge}">${status}</span>`;
                    }
                }, // Estatus
                {
                    data: null,
                    render: function(data, type, row) {
                        return row.reportado_por || (row.usuario ? row.usuario.name : 'Desconocido');
                    }
                }, // Nombre del usuario
                {
                    data: 'created_at',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleString();
                    }
                }, // Fecha de creación
                {
                    data: 'updated_at',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleString();
                    }
                }, // Fecha de actualización
                {
                    data: null,
                    render: function(data, type, row) {
                        // Verifica si el usuario es administrador
                        if (userLogged.rol === "Administrador") {
                            return `
                            <button class="btn btn-primary" onclick="showViewIncidencia(${row.id})" title="Ver Incidencia">
                            <i class="fa-regular fa-eye"></i>
                        </button>

                        <button class="btn btn-warning" onclick="showEditIncidenciaModal(${row.id})" title="Editar Incidencia">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>

                        <button class="btn btn-danger" onclick="deleteIncidencia(${row.id})" title="Eliminar Incidencia">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    `;
                        } else {
                            return `
                            <button class="btn btn-primary" onclick="showViewIncidencia(${row.id})" title="Ver Incidencia">
                            <i class="fa-regular fa-eye"></i>
                        </button>`; // Si no es administrador, solo muestra el botón de ver incidencia
                        }
                    }
                }
            ],
            language: {
                "url": "assets/js/plugins/es-ES.json"
            },
            initComplete: function() {
                // Forzar la inyección del filtro mediante setInterval por si la plantilla reconstruye el DOM
                let checkExist = setInterval(function() {
                    let container = $('.dataTables_length, .dataTable-dropdown, .dt-length').first();
                    if (container.length) {
                        $('#customStatusFilter').appendTo(container).show();
                        container.css({'display': 'flex', 'align-items': 'center'});
                        clearInterval(checkExist);
                    }
                }, 200);

                $('#filtroEstatus').on('change', function() {
                    let val = $(this).val();
                    $('#tableIncidencias').DataTable().column(4).search(val ? '^' + val + '$' : '', true, false).draw();
                });

                // Auto-abrir incidencia si viene en la URL
                const urlParams = new URLSearchParams(window.location.search);
                const showIncidenciaId = urlParams.get('show_incidencia');
                if (showIncidenciaId) {
                    showViewIncidencia(showIncidenciaId);
                }
            }
        });
    });

    function showViewIncidencia(id) {

        fetch(`/incidencia/${id}`, {
                method: 'get',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .then(response => response.json())
            .then(data => {

                console.log(data);
                let photoUrl = (data.usuario && data.usuario.foto) ? `/storage/users/${data.usuario.foto}` : '/storage/users/user.png';
                
                let unidadNombre = data.unidad ? data.unidad.nombre : 'N/A';
                let unidadTipo = data.unidad ? data.unidad.type : 'N/A';
                let unidadFolio = data.unidad ? data.unidad.tb_id : 'N/A';

                document.querySelector("#celCodigo").innerHTML = unidadNombre;
                document.querySelector("#celVehiculo").innerHTML = unidadTipo;
                document.querySelector("#celFolio").innerHTML = unidadFolio;
                document.querySelector("#celIncidencia").innerHTML = '<p>' + data.descripcion + '</p>';
                document.querySelector("#celConductor").innerHTML = data.reportado_por || (data.usuario ? data.usuario.name : 'Desconocido');
                if(data.usuario && data.usuario.foto) {
                    document.querySelector("#celImg").innerHTML = '<a href="' + photoUrl + '" data-fancybox="single" data-caption="' + data.usuario.name+ '"><img class="logo-image" src="' + photoUrl + '"></img> </a>';
                } else {
                    document.querySelector("#celImg").innerHTML = '';
                }
                // Inicializar Fancybox
                Fancybox.bind('[data-fancybox="single"]', {
                    groupAttr: false,
                });
                
                // Configurar botón Resolver
                document.getElementById('resolveIncidenciaBtn').onclick = function() {
                    let base_url = '/orden';
                    let desc = encodeURIComponent('Resolución de Incidencia #' + data.id + ':\n' + data.descripcion);
                    let params = '?incidencia_id=' + data.id + '&detalles=' + desc;
                    if (data.unidad_id) {
                        params += '&unidad_id=' + data.unidad_id;
                    }
                    window.location.href = base_url + params;
                };

                $('#VerIncidencia').modal('show');
            })
            .catch(error => console.error('Error:', error));
    }

    // Crear incidencia
    function showCreateIncidenciaModal() {
        $('#incidenciaModal').modal('show');
        document.querySelector('.modal-header').classList.replace("bg-warning", "bg-success");
        $('#modalTitle').text('Crear Incidencia');
        $('#actionButton').text('Guardar').removeClass('bg-gradient-warning').addClass('bg-gradient-success');
        $('#incidenciaForm')[0].reset();
        $('#actionButton').attr('onclick', 'createIncidencia()');

        // Cargar unidades en el select
        $.get('/incidencias-unidad', function(unidades) {
            let select = $('#incidenciaUnidad');
            select.empty();
            unidades.forEach(function(unidad) {
                select.append(
                    `<option value="${unidad.id_unidad}">${unidad.nombre} - ${unidad.type} - ${unidad.tb_id}</option>`
                );
            });
        });

        // if userLogged.rol is not Administrator we can prefill their name
        if (userLogged.rol !== 'Administrador') {
            $('#incidenciaUsuario').val("{{ Auth::user()->name }}");
        }
    }

    function createIncidencia() {
        let descripcion = $('#incidenciaDescripcion').val();
        let unidad_id = $('#incidenciaUnidad').val();
        let reportado_por = $('#incidenciaUsuario').val();
        let status = $('#incidenciaStatus').val();
        let nivel_importancia = $('#incidenciaNivel').val();

        if (!descripcion || !unidad_id || !reportado_por) {
            Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
            return;
        }

        let datos = {
            descripcion,
            unidad_id,
            reportado_por,
            status,
            nivel_importancia
        };
        
        fetch('/incidencia', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(datos)
        })
        .then(response => response.json())
        .then(data => {
            console.log("asdads" + data);
                $('#incidenciaModal').modal('hide');
                $('#tableIncidencias').DataTable().ajax.reload(null, false);
                Swal.fire('Éxito', 'Incidencia creada correctamente', 'success');
            })
            .catch(error => console.error('Error:', error));
    }


    // Editar incidencia
    function showEditIncidenciaModal(id) {
        $.get('/incidencias/' + id, function(incidencia) {
            $('#incidenciaModal').modal('show');
            document.querySelector('.modal-header').classList.replace("bg-success", "bg-warning");
            $('#modalTitle').text('Actualizar Incidencia');
            $('#actionButton').text('Actualizar').removeClass('bg-gradient-success').addClass(
                'bg-gradient-warning');
            $('#incidenciaId').val(incidencia.id);
            $('#incidenciaDescripcion').val(incidencia.descripcion);
            $('#incidenciaStatus').val(incidencia.status || 'Abierta');
            $('#incidenciaNivel').val(incidencia.nivel_importancia || 'Normal');
            $('#incidenciaUsuario').val(incidencia.reportado_por || (incidencia.usuario ? incidencia.usuario.name : ''));

            // Reutilizar función de cargar unidades
            $.get('/incidencias-unidad', function(unidades) {
                let select = $('#incidenciaUnidad');
                select.empty();
                unidades.forEach(function(unidad) {
                    select.append(
                        `<option value="${unidad.id_unidad}" ${unidad.id_unidad === incidencia.unidad_id ? 'selected' : ''}>
                    ${unidad.nombre} - ${unidad.type} - ${unidad.tb_id}
                </option>`
                    );
                });
            });

            $('#actionButton').attr('onclick', 'updateIncidencia()');
        });
    }


    function updateIncidencia() {
        let id = $('#incidenciaId').val();
        let descripcion = $('#incidenciaDescripcion').val();
        let unidad_id = $('#incidenciaUnidad').val();
        let reportado_por = $('#incidenciaUsuario').val();
        let status = $('#incidenciaStatus').val();
        let nivel_importancia = $('#incidenciaNivel').val();

        if (!descripcion || !unidad_id || !reportado_por) {
            Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
            return;
        }

        let datos = {
            descripcion,
            unidad_id,
            reportado_por,
            status,
            nivel_importancia
        };

        fetch('/incidencia/' + id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                $('#incidenciaModal').modal('hide');
                $('#tableIncidencias').DataTable().ajax.reload(null, false);
                Swal.fire('Éxito', 'Incidencia actualizada correctamente', 'success');
            })
            .catch(error => console.error('Error:', error));
    }

    // Eliminar incidencia
    function deleteIncidencia(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/incidencia/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire('¡Eliminado!', data.message, 'success');
                        $('#tableIncidencias').DataTable().ajax.reload();
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    }

    // Limpiar modal al cerrarlo
    $('#incidenciaModal').on('hidden.bs.modal', function() {
        $('#incidenciaForm')[0].reset();
    });
</script>
@endsection
