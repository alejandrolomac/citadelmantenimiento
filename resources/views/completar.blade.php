@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Nueva Orden'])
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        h2,
        h3 {
            text-align: center;
        }

        .tecnico {
            margin-top: 30px;
            text-align: right;
        }

        .signature {
            margin-top: 40px;
            text-align: center;
        }

        .qr-container {
            width: 100px;
            height: 100px;
            float: left;
            margin-right: 10px;
        }

        .navbar-fixed-bottom {
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1050;
            /* Coloca este valor para que el menú esté siempre encima */
            background-color: #f8f9fa;
            border-top: 1px solid #ddd;
        }

        .navbar-fixed-bottom .btn {
            display: inline-block;
            /* Asegura que los botones estén en línea */
            margin: 0 10px;
            /* Agrega espacio entre los botones */
        }

        .text-center {
            text-align: center;
            /* Centra el contenido horizontalmente */
        }


        .btn-round {
            border-radius: 50px;
            /* Bordes redondeados */
            padding: 10px 20px;
        }

        .text-right {
            text-align: right;
            /* Alineación a la derecha */
        }

        .btn-export {
            color: #ffffff;
            background-color: #343a40;
            border-color: #343a40;
        }

        .btn-export:hover {
            color: #ffffff;
            background-color: #373e44;
            border-color: #373e44;
        }
    </style>
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body px-0 pt-0 pb-2">

                        <table>
                            <tr>
                                <td>

                                    <div class="table-responsive p-2">
                                        <h2>Orden de Trabajo: {{ $no_orden }}</h2>
                                        <table class="mb-3">
                                            <tr>
                                                <td><strong>Técnico:</strong> {{ $tecnico }}</td>
                                                <td><strong>Hora de Inicio:</strong> {{ $hora_inicio }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</td>
                                                <td><strong>Hora de Finalización:</strong> {{ $hora_final }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><strong>Ubicación:</strong> {{ $unidad }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <h3>Información de la Unidad</h3>
                                    <div class="table-responsive p-2">
                                        <table class="mb-3">
                                            <tr>
                                                <td><strong>Unidad:</strong> {{ $type }}</td>
                                                <td><strong>Conductor:</strong> {{ $conductor }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Nombre de Máquina/Unidad:</strong> {{ $nombre }}</td>
                                                <td><strong>TB ID:</strong> {{ $tb_id }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha:</strong> {{ $fecha }}</td>
                                                <td> {{ $kilometraje }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <h3>Trabajo Realizado</h3>
                                    <div class="table-responsive p-2">
                                        <table class="mb-3">
                                            @foreach ($trabajos_realizados as $trabajo)
                                            <tr>
                                                <td>{{ $trabajo['nombre'] }}</td>
                                                <td>Realizado</td>
                                            </tr>
                                            @if (!empty($trabajo['descripcion']))
                                            <tr>
                                                <td colspan="2"><strong>Descripción:</strong> {{ $trabajo['descripcion'] }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </table>
                                    </div>


                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12 btn-group" role="group" >
                    <a type="button" class="btn btn-warning" href="/orden/{{ $id_orden_trabajo }}/editar" title="Editar">
                        Editar
                    </a>
                    <button type="button" class="btn bg-gradient-success" onclick="guardarFirmas({{ $id_orden_trabajo }})" title="Guardar">
                        Guardar
                    </button>
                    <button type="button" class="btn btn-export btn-fill" onclick="mantImprimir({{ $id_orden_trabajo }})" title="Imprimir">
                        Imprimir
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <script>
        function guardarFirmas(ordenId) {
            Swal.fire({
                title: 'Exito',
                text: "Orden Guardada Exitosamente",
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Crear Nueva Orden',
                cancelButtonText: 'Regresar a Mantenimiento'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/orden';
                } else {
                    window.location.href = '/mantenimiento';
                }
            });
        }



        function mantImprimir(id_orden_trabajo) {
            fetch(`/orden/${id_orden_trabajo}/exportar-pdf?view=true`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error al generar el PDF");
                    }
                    return response.blob();
                })
                .then(blob => {
                    var fileURL = URL.createObjectURL(blob);
                    var printWindow = window.open(fileURL, '_blank');
                    if (printWindow) {
                        printWindow.onload = function() {
                            printWindow.print();
                        };
                    } else {
                        alert("No se pudo abrir la ventana de impresión. Habilita los pop-ups.");
                    }
                })
                .catch(error => console.error('Error al generar el PDF:', error));
        }
    </script>
@endsection
