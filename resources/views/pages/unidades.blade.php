@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Unidades'])
    <style>
        #tableUnidades {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 16px;
            text-align: left;
        }

        #tableUnidades th,
        #tableUnidades td {
            padding: 12px 15px;
        }

        #tableUnidades thead tr {
            background-color: #ffffff;
            color: #000000;
            text-align: left;
            font-weight: bold;
        }

        #tableUnidades tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        #tableUnidades tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        #tableUnidades tbody tr:last-of-type {
            border-bottom: 2px solid #000000;
        }
    </style>

    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Unidades</h6>
                    <a href="/unidad" class="btn bg-gradient-success"><i class="fa-solid fa-plus"></i> Nueva Unidad</a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table id="tableUnidades" class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>#</th>
                                    <th>Unidad</th>
                                    <th>Fecha</th>
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
            $('#tableUnidades').DataTable({
                responsive: true,
                processing: true,
                serverSide: false,
                ajax: '{{ url('unidades/data') }}',
                columns: [{
                        data: 'id_unidad',
                        visible: false // Oculta la columna de ID
                    },
                    {
                        data: null, // Índice automático
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Índice secuencial
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let tipoNombre = row.tipo_dispositivo ? row.tipo_dispositivo.nombre : 'Sin tipo';
                            return row.nombre + ' - ' + tipoNombre;
                        }
                    },
                    {
                        data: 'fecha'
                    },
                    {
                        data: 'estado',
                        render: function(data) {
                            return data == 1 ?
                                '<span class="badge bg-gradient-success">Activo</span>' :
                                '<span class="badge bg-gradient-secondary">Inactivo</span>';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                        <a href="/detalle_unidad/${row.id_unidad}" class="btn btn-primary" alt="Ver">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                        <a href="/unidad/${row.id_unidad}/editar" class="btn btn-warning" alt="Editar">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <button class="btn btn-danger" onclick="deleteOrder(${row.id_unidad})" alt="Eliminar"><i class="fa-regular fa-trash-can"></i></button>
                    `;
                        }
                    }
                ],
                language: {
                    "url": "assets/js/plugins/es-ES.json"
                }
            });
        });
    </script>
@endsection
