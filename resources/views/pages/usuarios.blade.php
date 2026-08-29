@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Usuarios'])
    <style>
        #tableUsuarios {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 16px;
            text-align: left;
        }

        #tableUsuarios th,
        #tableUsuarios td {
            padding: 12px 15px;
        }

        #tableUsuarios thead tr {
            background-color: #ffffff;
            color: #000000;
            text-align: left;
            font-weight: bold;
        }

        #tableUsuarios tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        #tableUsuarios tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        #tableUsuarios tbody tr:last-of-type {
            border-bottom: 2px solid #000000;
        }
    </style>

    <!-- Modal para Editar Usuario -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" style="color: #ffffff" id="modalTitle">Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="userForm" enctype="multipart/form-data">
                        <input type="hidden" id="userId">

                        <!-- Contenedor de la imagen -->
                        <div class="text-center mb-3">
                            <div class="avatar avatar-xxl position-relative">
                                <img id="userPhotoPreview" src="/storage/users/user.png" class="border-radius-md"
                                    alt="Foto del Usuario">
                                <a href="javascript:;"
                                    class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2"
                                    onclick="document.getElementById('userPhoto').click()">
                                    <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Edit Image" aria-hidden="true" data-bs-original-title="Edit Image"
                                        aria-label="Edit Image"></i>
                                    <span class="sr-only">Edit Image</span>
                                </a>
                            </div>
                        </div>
                        <!-- Campo de selección de imagen oculto -->
                        <input type="file" class="form-control-file d-none" id="userPhoto" accept="image/*"
                            onchange="previewImage(event)">

                        <div class="mb-3">
                            <label for="userName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="userName" placeholder="Nombre del Usuario"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="userEmail" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="userRol" class="form-label">Rol</label>
                            <select class="form-control" id="userRol" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">Contraseña</label>
                            <div class="alert alert-danger mb-1" role="alert" id="password-error-alert"
                                style="color: white; display: none;">
                                <span id="password-error" style="color: white;"></span>
                            </div>
                            <div class="position-relative">
                                <input type="password" id="userPassword" class="form-control form-control-lg pe-5"
                                    aria-label="Password" placeholder="Contraseña" oninput="validatePassword(this.value)">

                                <button class="position-absolute top-50 end-0 translate-middle-y me-2 p-0 border-0"
                                    type="button" onclick="togglePassword('userPassword', this)"
                                    style="all: unset; cursor: pointer; display: flex; align-items: center;">
                                    <span class="eye-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M12 8.25C9.92893 8.25 8.25 9.92893 8.25 12C8.25 14.0711 9.92893 15.75 12 15.75C14.0711 15.75 15.75 14.0711 15.75 12C15.75 9.92893 14.0711 8.25 12 8.25ZM9.75 12C9.75 10.7574 10.7574 9.75 12 9.75C13.2426 9.75 14.25 10.7574 14.25 12C14.25 13.2426 13.2426 14.25 12 14.25C10.7574 14.25 9.75 13.2426 9.75 12Z"
                                                fill="#1C274C" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M12 3.25C7.48587 3.25 4.44529 5.9542 2.68057 8.24686L2.64874 8.2882C2.24964 8.80653 1.88206 9.28392 1.63269 9.8484C1.36564 10.4529 1.25 11.1117 1.25 12C1.25 12.8883 1.36564 13.5471 1.63269 14.1516C1.88206 14.7161 2.24964 15.1935 2.64875 15.7118L2.68057 15.7531C4.44529 18.0458 7.48587 20.75 12 20.75C16.5141 20.75 19.5547 18.0458 21.3194 15.7531L21.3512 15.7118C21.7504 15.1935 22.1179 14.7161 22.3673 14.1516C22.6344 13.5471 22.75 12.8883 22.75 12C22.75 11.1117 22.6344 10.4529 22.3673 9.8484C22.1179 9.28391 21.7504 8.80652 21.3512 8.28818L21.3194 8.24686C19.5547 5.9542 16.5141 3.25 12 3.25ZM3.86922 9.1618C5.49864 7.04492 8.15036 4.75 12 4.75C15.8496 4.75 18.5014 7.04492 20.1308 9.1618C20.5694 9.73159 20.8263 10.0721 20.9952 10.4545C21.1532 10.812 21.25 11.2489 21.25 12C21.25 12.7511 21.1532 13.188 20.9952 13.5455C20.8263 13.9279 20.5694 14.2684 20.1308 14.8382C18.5014 16.9551 15.8496 19.25 12 19.25C8.15036 19.25 5.49864 16.9551 3.86922 14.8382C3.43064 14.2684 3.17374 13.9279 3.00476 13.5455C2.84684 13.188 2.75 12.7511 2.75 12C2.75 11.2489 2.84684 10.812 3.00476 10.4545C3.17374 10.0721 3.43063 9.73159 3.86922 9.1618Z"
                                                fill="#1C274C" />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" id="actionButton" onclick="handleUserAction()">Guardar</button>
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main de usuarios -->
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Usuarios</h6>
                    @can('Crear Usuarios')
                        <button type="button" class="btn bg-gradient-success" onclick="showCreateUserModal()">
                            <i class="fa-solid fa-user-plus"></i>&nbsp;&nbsp;Nuevo
                        </button>
                    @endcan
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table id="tableUsuarios" class="table align-items-center mb-0 display stripe hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
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
            $('#tableUsuarios').DataTable({
                responsive: true,
                processing: true,
                serverSide: false, // Lo dejamos en falso porque estamos obteniendo todos los datos sin paginación en el backend
                ajax: '{{ url('usuarios/data') }}',
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'rol.name'
                    },
                    {
                        data: 'status',
                        defaultContent: 1,
                        render: function(data) {
                            return (data === 1 || data === undefined) ?
                                '<span class="badge bg-gradient-success">Activo</span>' :
                                '<span class="badge bg-gradient-secondary">Bloqueado</span>';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                            @can('Editar Usuarios')
                            <button class="btn btn-warning" onclick="showEditUserModal(${row.id})" alt="Editar"><i class="fa-regular fa-pen-to-square"></i></button>
                            @endcan
                            @can('Eliminar Usuarios')
                            <button class="btn btn-danger" onclick="deleteUser(${row.id})" alt="Eliminar"><i class="fa-regular fa-trash-can"></i></button>
                            @endcan
                        `;
                        }
                    }
                ],
                language: {
                    "url": "assets/js/plugins/es-ES.json"
                }
            });
        });

        function showCreateUserModal() {
            $('#userModal').modal('show');
            document.querySelector('.modal-header').classList.replace("bg-warning", "bg-success");
            $('#modalTitle').text('Crear Usuario');
            $('#actionButton').text('Guardar').removeClass('bg-gradient-warning').addClass('bg-gradient-success');
            $('#userForm')[0].reset();
            $('#actionButton').attr('onclick', 'createUser()');

            // Mostrar la imagen por defecto para nuevos usuarios
            $('#userPhotoPreview').attr('src', '/storage/users/user.png');

            // Cargar roles en el select
            $.get('/rol-usuario', function(roles) {
                let select = $('#userRol');
                select.empty();
                roles.forEach(function(rol) {
                    select.append(`<option value="${rol.id}">${rol.name}</option>`);
                });
            });
        }

        function showEditUserModal(id) {
            $.get('/usuario/' + id, function(usuario) {
                $('#userModal').modal('show');
                document.querySelector('.modal-header').classList.replace("bg-success", "bg-warning");
                $('#modalTitle').text('Actualizar Usuario');
                $('#actionButton').text('Actualizar').removeClass('bg-gradient-success').addClass(
                    'bg-gradient-warning');
                $('#userId').val(usuario.id);
                $('#userName').val(usuario.name);
                $('#userEmail').val(usuario.email);
                $('#actionButton').attr('onclick', 'updateUser()');

                // Mostrar la foto del usuario o la imagen por defecto
                let photoUrl = usuario.foto ? `/storage/users/${usuario.foto}` : '/storage/users/user.png';
                $('#userPhotoPreview').attr('src', photoUrl);

                // Cargar roles en el select
                $.get('/rol-usuario', function(roles) {
                    let select = $('#userRol');
                    select.empty();
                    roles.forEach(function(rol) {
                        let selected = usuario.id_rol === rol.id ? 'selected' : '';
                        select.append(
                            `<option value="${rol.id}" ${selected}>${rol.name}</option>`
                        );
                    });
                });
            });
        }

        function deleteUser(id) {
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
                    fetch(`/orden/delete/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {

                            //console.log(data);
                            Swal.fire('¡Eliminado!', data.message, 'success');
                            $('#tableUsuarios').DataTable().ajax.reload();
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        }


        function createUser() {
            let userName = $('#userName').val();
            let userEmail = $('#userEmail').val();
            let userPassword = $('#userPassword').val();
            let userPhoto = $('#userPhoto')[0].files[0];

            if (!userName) {
                Swal.fire('Error', 'El campo de nombre no puede estar vacío', 'error');
                return; // Detener la ejecución si los campos obligatorios están vacíos
            }

            if (!userEmail) {
                Swal.fire('Error', 'El campo de correo electrónico no puede estar vacío', 'error');
                return; // Detener la ejecución si los campos obligatorios están vacíos
            }

            if (!userPassword) {
                Swal.fire('Error', 'El campo de contraseña no puede estar vacío', 'error');
                return; // Detener la ejecución si los campos obligatorios están vacíos
            }

            let formData = new FormData();
            formData.append('name', userName);
            formData.append('email', userEmail);
            formData.append('password', userPassword);
            formData.append('id_rol', $('#userRol').val());
            if (userPhoto) {
                formData.append('foto', userPhoto);
            }



            fetch('/usuario', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        $('#userModal').modal('hide', function() {
                            limpiarCamposModal();
                        });
                        $('#tableUsuarios').DataTable().ajax.reload(null,
                            false); // Recarga la tabla sin recargar la página
                        Swal.fire('Éxito', 'Usuario creado correctamente', 'success');
                    } else {
                        Swal.fire('Error', 'No se pudo crear el usuario', 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
        }


        function updateUser() {
            let userName = $('#userName').val().trim();
            let userEmail = $('#userEmail').val().trim();
            let userPhoto = $('#userPhoto')[0].files[0];

            //console.log(userName, userEmail, userPhoto);

            if (!userName) {
                Swal.fire('Error', 'El campo de nombre no puede estar vacío', 'error');
                return;
            }

            if (!userEmail) {
                Swal.fire('Error', 'El campo de correo electrónico no puede estar vacío', 'error');
                return;
            }

            let id = $('#userId').val();
            let formData = new FormData();
            formData.append('_method', 'PUT'); // ⚠️ Necesario porque `fetch` usa `POST`
            formData.append('name', userName);
            formData.append('email', userEmail);
            formData.append('id_rol', $('#userRol').val() || '');

            if (userPhoto) {
                formData.append('foto', userPhoto);
            }

            let password = $('#userPassword').val().trim();
            if (password !== '') {
                formData.append('password', password);
            }

            fetch('/usuario/' + id, {
                    method: 'POST', // Laravel espera `POST` con `_method: PUT`
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        //console.log("Respuesta del servidor:", data);
                        $('#userModal').modal('hide', function() {
                            limpiarCamposModal();
                        });
                        $('#tableUsuarios').DataTable().ajax.reload(null, false);
                        Swal.fire('Éxito', data.message, 'success');
                    } else {
                        Swal.fire('Error', 'No se pudo actualizar el usuario', 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
        }


        function limpiarCamposModal() {
            $('#userForm')[0].reset();
            $('#userRol').empty();
        }

        // Asociar la función al evento de ocultar el modal
        $('#userModal').on('hidden.bs.modal', function() {
            limpiarCamposModal();
        });

        function validateEmail(email) {
            const emailRegex = /^[^\s&=_'\-<>+,]+(\.[^\s&=_'\-<>+,]+)*@[^\s&=_'\-<>+,]+\.[^\s&=_'\-<>+,]+$/;
            if (!emailRegex.test(email)) {
                displayError("email",
                    "No se permiten espacios ni caracteres especiales (&, =, _, ', -, <>, +, ,, ..)");
                return false;
            }
            clearError("email");
            return true;
        }

        function validatePassword(password) {
            const passwordRegex = /^[A-Za-z0-9!"#$%&'()*+,\-.\/:;<=>?@\[\\\]^_`{|}~]+$/;
        if (!passwordRegex.test(password) || /\s/.test(password)) {
            displayError("password",
                "Solo se permiten caracteres alfanuméricos y los caracteres especiales permitidos."
            );
            return false;
        }
        clearError("password");
        return true;
    }

    // Función para mostrar el error
    function displayError(field, message) {
        const errorElement = document.getElementById(`${field}-error-alert`);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = "block";
        }
    }

    // Función para limpiar el error
    function clearError(field) {
        const errorElement = document.getElementById(`${field}-error-alert`);
        if (errorElement) {
            errorElement.textContent = "";
            errorElement.style.display = "none";
        }
    }


    function togglePassword(inputId, button) {
        let passwordInput = document.getElementById(inputId);
        let iconContainer = button.querySelector(".eye-icon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            iconContainer.innerHTML = `
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M22.2954 6.31083C22.6761 6.474 22.8524 6.91491 22.6893 7.29563L21.9999 7.00019C22.6893 7.29563 22.6894 7.29546 22.6893 7.29563L22.6886 7.29731L22.6875 7.2998L22.6843 7.30716L22.6736 7.33123C22.6646 7.35137 22.6518 7.37958 22.6352 7.41527C22.6019 7.48662 22.5533 7.58794 22.4888 7.71435C22.3599 7.967 22.1675 8.32087 21.9084 8.73666C21.4828 9.4197 20.8724 10.2778 20.0619 11.1304L21.0303 12.0987C21.3231 12.3916 21.3231 12.8665 21.0303 13.1594C20.7374 13.4523 20.2625 13.4523 19.9696 13.1594L18.969 12.1588C18.3093 12.7115 17.5528 13.2302 16.695 13.6564L17.6286 15.0912C17.8545 15.4383 17.7562 15.9029 17.409 16.1288C17.0618 16.3547 16.5972 16.2564 16.3713 15.9092L15.2821 14.2353C14.5028 14.4898 13.659 14.6628 12.7499 14.7248V16.5002C12.7499 16.9144 12.4141 17.2502 11.9999 17.2502C11.5857 17.2502 11.2499 16.9144 11.2499 16.5002V14.7248C10.3689 14.6647 9.54909 14.5004 8.78982 14.2586L7.71575 15.9093C7.48984 16.2565 7.02526 16.3548 6.67807 16.1289C6.33089 15.903 6.23257 15.4384 6.45847 15.0912L7.37089 13.689C6.5065 13.2668 5.74381 12.7504 5.07842 12.1984L4.11744 13.1594C3.82455 13.4523 3.34968 13.4523 3.05678 13.1594C2.76389 12.8665 2.76389 12.3917 3.05678 12.0988L3.98055 11.175C3.15599 10.3153 2.53525 9.44675 2.10277 8.75486C1.83984 8.33423 1.6446 7.97584 1.51388 7.71988C1.44848 7.59182 1.3991 7.48914 1.36537 7.41683C1.3485 7.38067 1.33553 7.35207 1.32641 7.33167L1.31562 7.30729L1.31238 7.29984L1.31129 7.29733L1.31088 7.29638C1.31081 7.2962 1.31056 7.29563 1.99992 7.00019L1.31088 7.29638C1.14772 6.91565 1.32376 6.474 1.70448 6.31083C2.08489 6.1478 2.52539 6.32374 2.68888 6.70381C2.68882 6.70368 2.68894 6.70394 2.68888 6.70381L2.68983 6.706L2.69591 6.71972C2.7018 6.73291 2.7114 6.7541 2.72472 6.78267C2.75139 6.83983 2.79296 6.92644 2.84976 7.03767C2.96345 7.26029 3.13762 7.58046 3.37472 7.95979C3.85033 8.72067 4.57157 9.70728 5.55561 10.6218C6.42151 11.4265 7.48259 12.1678 8.75165 12.656C9.70614 13.0232 10.7854 13.2502 11.9999 13.2502C13.2416 13.2502 14.342 13.013 15.3124 12.631C16.5738 12.1345 17.6277 11.3884 18.4866 10.5822C19.4562 9.67216 20.1668 8.69535 20.6354 7.9434C20.869 7.5685 21.0405 7.25246 21.1525 7.03286C21.2085 6.92315 21.2494 6.83776 21.2757 6.78144C21.2888 6.75328 21.2983 6.73242 21.3041 6.71943L21.31 6.70595L21.3106 6.70475C21.3105 6.70485 21.3106 6.70466 21.3106 6.70475M22.2954 6.31083C21.9147 6.14771 21.4738 6.32423 21.3106 6.70475L22.2954 6.31083ZM2.68888 6.70381C2.68882 6.70368 2.68894 6.70394 2.68888 6.70381V6.70381Z" fill="#1C274C"/>
                                                                    </svg>`;
        } else {
            passwordInput.type = "password";
            iconContainer.innerHTML = `
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 8.25C9.92893 8.25 8.25 9.92893 8.25 12C8.25 14.0711 9.92893 15.75 12 15.75C14.0711 15.75 15.75 14.0711 15.75 12C15.75 9.92893 14.0711 8.25 12 8.25ZM9.75 12C9.75 10.7574 10.7574 9.75 12 9.75C13.2426 9.75 14.25 10.7574 14.25 12C14.25 13.2426 13.2426 14.25 12 14.25C10.7574 14.25 9.75 13.2426 9.75 12Z" fill="#1C274C"/>
                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.25C7.48587 3.25 4.44529 5.9542 2.68057 8.24686L2.64874 8.2882C2.24964 8.80653 1.88206 9.28392 1.63269 9.8484C1.36564 10.4529 1.25 11.1117 1.25 12C1.25 12.8883 1.36564 13.5471 1.63269 14.1516C1.88206 14.7161 2.24964 15.1935 2.64875 15.7118L2.68057 15.7531C4.44529 18.0458 7.48587 20.75 12 20.75C16.5141 20.75 19.5547 18.0458 21.3194 15.7531L21.3512 15.7118C21.7504 15.1935 22.1179 14.7161 22.3673 14.1516C22.6344 13.5471 22.75 12.8883 22.75 12C22.75 11.1117 22.6344 10.4529 22.3673 9.8484C22.1179 9.28391 21.7504 8.80652 21.3512 8.28818L21.3194 8.24686C19.5547 5.9542 16.5141 3.25 12 3.25ZM3.86922 9.1618C5.49864 7.04492 8.15036 4.75 12 4.75C15.8496 4.75 18.5014 7.04492 20.1308 9.1618C20.5694 9.73159 20.8263 10.0721 20.9952 10.4545C21.1532 10.812 21.25 11.2489 21.25 12C21.25 12.7511 21.1532 13.188 20.9952 13.5455C20.8263 13.9279 20.5694 14.2684 20.1308 14.8382C18.5014 16.9551 15.8496 19.25 12 19.25C8.15036 19.25 5.49864 16.9551 3.86922 14.8382C3.43064 14.2684 3.17374 13.9279 3.00476 13.5455C2.84684 13.188 2.75 12.7511 2.75 12C2.75 11.2489 2.84684 10.812 3.00476 10.4545C3.17374 10.0721 3.43063 9.73159 3.86922 9.1618Z" fill="#1C274C"/>
                                                                    </svg>`;
            }
        }

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('userPhotoPreview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
