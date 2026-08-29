@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Roles'])
    <style>
        #tableRoles {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 16px;
            text-align: left;
        }

        #tableRoles th,
        #tableRoles td {
            padding: 12px 15px;
        }

        #tableRoles thead tr {
            background-color: #ffffff;
            color: #000000;
            text-align: left;
            font-weight: bold;
        }

        #tableRoles tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        #tableRoles tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        #tableRoles tbody tr:last-of-type {
            border-bottom: 2px solid #000000;
        }

        .btn-permisos {
    color: #ffffff;
    background-color: #343a40;
    border-color: #343a40;
}

.btn-permisos:hover {
  color: #ffffff;
  background-color: #373e44;
  border-color: #373e44;
}
    </style>

    <!-- Modal para Crear/Editar Roles -->
    <div class="modal fade" id="rolModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" style="color: #ffffff" id="modalTitle">Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="rolForm">
                        <input type="hidden" id="rolId">
                        <div class="mb-3">
                            <label for="rolName" class="form-label">Rol</label>
                            <input type="text" class="form-control" id="rolName" placeholder="Nombre del Rol" required>
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

    <!-- Modal para Crear/Editar Permisos -->
    <div class="modal fade" id="permissionsModalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #343a40">
                    <h5 class="modal-title" style="color: #ffffff" id="modalTitle">Permisos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="permissionsModalForm">
                        <input type="hidden" id="roleId">
                        <div class="form-group">
                            <div id="permissionsList">
                                <!-- Aquí se cargarán los permisos dinámicamente -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-permisos" id="savePermissions">Guardar</button>
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Roles</h6>
                    @can('Crear Roles')
                    <button type="button" class="btn bg-gradient-success" onclick="showCreateRolModal()">
                        <i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Nuevo
                    </button>
                    @endcan
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table id="tableRoles" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>#</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tableRoles').DataTable({
                responsive: true,
                processing: true,
                serverSide: false,
                ajax: '{{ url('roles/data') }}',
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
                        data: 'name'
                    }, // Nombre del rol
                    /*{
                        data: 'permissions',
                        render: function(data) {
                            return data.map(p => `<span class="badge bg-info">${p.name}</span>`)
                                .join(' ');
                        }
                    },*/
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                            @can('Editar Roles')
                    <button class="btn btn-permisos btn-assign-permissions" onclick="openPermissionsModal(${row.id})">
    <i class="fa-solid fa-key"></i>
</button>

                    <button class="btn btn-warning" onclick="showEditRolModal(${row.id})">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </button>
@endcan
                            @can('Eliminar Roles')
                    <button class="btn btn-danger" onclick="deleteRol(${row.id})">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
@endcan
                `;
                        }
                    }
                ],
                language: {
                    "url": "assets/js/plugins/es-ES.json"
                }
            });



            // Guardar permisos asignados al rol
            document.getElementById("savePermissions").addEventListener("click", function() {
                let selectedPermissions = [];
                document.querySelectorAll("#permissionsList input[type='checkbox']:checked").forEach(
                    checkbox => {
                        selectedPermissions.push(checkbox.value);
                    });

                fetch(`/roles/${roleId}/assign-permissions`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                .getAttribute("content")
                        },
                        body: JSON.stringify({
                            permisos: selectedPermissions
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        $('#permissionsModalModal').modal('hide');
                        Swal.fire('Éxito', data.message, 'success');
                        $('#tableRoles').DataTable().ajax.reload(null,
                            false); // Recarga la tabla sin recargar la página
                    })
                    .catch(error => console.error("Error al asignar permisos:", error));
            });

            // Asignar la función al botón de abrir modal (ajústalo a tu botón específico)
            document.querySelectorAll(".btn-assign-permissions").forEach(button => {
                button.addEventListener("click", function() {
                    let roleId = this.dataset.roleId;
                    openPermissionsModal(roleId);
                });
            });
        });

        function showCreateRolModal() {
            $('#rolModal').modal('show');
            document.querySelector('.modal-header').classList.replace("bg-warning", "bg-success");
            $('#modalTitle').text('Crear Rol');
            $('#actionButton').text('Guardar').removeClass('bg-gradient-warning').addClass('bg-gradient-success');
            $('#rolForm')[0].reset();
            $('#actionButton').attr('onclick', 'createRol()');
        }

        function showEditRolModal(id) {
            //console.log(id);
            $.get('/rol/' + id, function(rol) {
                $('#rolModal').modal('show');
                document.querySelector('.modal-header').classList.replace("bg-success", "bg-warning");
                $('#modalTitle').text('Actualizar Rol');
                $('#actionButton').text('Actualizar').removeClass('bg-gradient-success').addClass(
                    'bg-gradient-warning');
                $('#rolId').val(rol.id);
                $('#rolName').val(rol.name);
                $('#actionButton').attr('onclick', 'updateRol()');
            });
        }

        function deleteRol(id) {
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

                    //console.log(id);
                    fetch(`/rol/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {

                            //console.log(data);
                            Swal.fire('¡Eliminado!', data.message, 'success');
                            $('#tableRoles').DataTable().ajax.reload();
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        }

        function createRol() {
            let rolName = $('#rolName').val();

            if (!rolName) {
                Swal.fire('Error', 'El campo de rol no puede estar vacío', 'error');
                return; // Detener la ejecución si el input está vacío
            }

            let datos = {
                nombre_rol: rolName
            };

            fetch('/rol', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify(datos)
                })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        //$('#rolModal').modal('hide');
                        $('#rolModal').modal('hide', function() {
                            limpiarCamposModal();
                        });
                        $('#tableRoles').DataTable().ajax.reload(null,
                            false); // Recarga la tabla sin recargar la página
                        Swal.fire('Éxito', 'Rol creado correctamente', 'success');
                    } else {
                        Swal.fire('Error', 'No se pudo crear el rol', 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
        }


        function updateRol() {
            let id = $('#rolId').val();

            let rolName = $('#rolName').val();

            if (!rolName) {
                Swal.fire('Error', 'El campo de rol no puede estar vacío', 'error');
                return; // Detener la ejecución si el input está vacío
            }

            let datos = {
                nombre_rol: rolName
            };

            fetch('/rol/' + id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify(datos)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        //console.log("data pero del response de la función updateRol:", data);
                        $('#rolModal').modal('hide', function() {
                            limpiarCamposModal();
                        });
                        $('#tableRoles').DataTable().ajax.reload(null,
                            false); // Recarga la tabla sin recargar la página
                        Swal.fire('Éxito', data.message, 'success');

                    } else {
                        Swal.fire('Error', 'No se pudo actualizar el rol', 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function limpiarCamposModal() {
            $('#rolForm')[0].reset();
            //$('#rolRol').empty();
        }

        // Asociar la función al evento de ocultar el modal
        $('#rolModal').on('hidden.bs.modal', function() {
            limpiarCamposModal();
        });



        let roleId = null;

        // Función para abrir el modal y cargar los permisos del rol
        function openPermissionsModal(id) {
            roleId = id;

            // Seleccionar todos los botones de permisos
            const allButtons = document.querySelectorAll('.btn-assign-permissions');

            // Deshabilitar todos los botones
            allButtons.forEach(button => button.disabled = true);

            // Cargar permisos asignados al rol
            fetch(`/role/${id}`)
                .then(response => response.json())
                .then(roleData => {
                    fetch("/roles/permissions") // Cargar todos los permisos disponibles
                        .then(response => response.json())
                        .then(allPermissions => {
                            renderPermissionsCheckboxes(allPermissions); // Renderiza los checkboxes
                            loadPermissions(roleData.permissions); // Marca los permisos del rol

                            // Volver a habilitar los botones
                            allButtons.forEach(button => button.disabled = false);

                            // Mostrar el modal
                            let modal = new bootstrap.Modal(document.getElementById("permissionsModalModal"));
                            modal.show();
                        })
                        .catch(error => {
                            console.error("Error al cargar permisos disponibles:", error);
                            allButtons.forEach(button => button.disabled =
                            false); // Habilitar botones en caso de error
                        });
                })
                .catch(error => {
                    console.error("Error al cargar permisos del rol:", error);
                    allButtons.forEach(button => button.disabled = false); // Habilitar botones en caso de error
                });
        }

        // Función para renderizar los permisos con checkboxes
        function renderPermissionsCheckboxes(permissions) {
    let container = document.getElementById("permissionsList");
    container.innerHTML = "";

    // Agrupar los permisos por pantalla
    let groupedPermissions = permissions.reduce((groups, permission) => {
        let screen = permission.screen || "Otros";
        if (!groups[screen]) {
            groups[screen] = [];
        }
        groups[screen].push(permission);
        return groups;
    }, {});

    // Crear un List Group para cada pantalla
    for (let screen in groupedPermissions) {
        let listGroup = document.createElement("div");
        listGroup.classList.add("list-group", "mb-3");

        // Agregar un título para cada grupo
        let screenTitle = document.createElement("h5");
        screenTitle.innerText = screen;
        container.appendChild(screenTitle);

        // Agregar los permisos al grupo
        groupedPermissions[screen].forEach(permission => {
            let item = document.createElement("div");
            item.classList.add("list-group-item");

            item.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permisos[]" value="${permission.name}" id="perm_${permission.id}">
                    <label class="form-check-label" for="perm_${permission.id}">${permission.name}</label>
                </div>
            `;
            listGroup.appendChild(item);
        });

        container.appendChild(listGroup);
    }
}


        /*function renderPermissionsCheckboxes(permissions) {
            let container = document.getElementById("permissionsList");
            container.innerHTML = "";
            permissions.forEach(permission => {
                let div = document.createElement("div");
                div.classList.add("form-check");
                div.innerHTML = `

                <input class="form-check-input" type="checkbox" name="permisos[]" value="${permission.name}" id="perm_${permission.id}">
                <label class="form-check-label" for="perm_${permission.id}">${permission.name}</label>

            `;
                container.appendChild(div);
            });
        }*/

        // Función para marcar los permisos asignados al rol
        function loadPermissions(rolePermissions) {
            rolePermissions.forEach(permission => {
                let checkbox = document.getElementById(`perm_${permission.id}`);
                if (checkbox) checkbox.checked = true;
            });
        }
    </script>
@endsection
