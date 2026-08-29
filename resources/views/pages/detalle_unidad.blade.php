@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Detalle Unidad'])

@php
    use Illuminate\Support\Str;
@endphp

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

<style>
    .table-fixed 
    {
        table-layout: fixed;
        width: 100%;
    }

    #celIdescription,
    .table-fixed th,
    .table-fixed td 
    {
        white-space: normal;
        word-break: break-word;
    }
</style>

<div class="container py-4">
    {{-- Tarjeta de unidad --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <h2 class="mb-0">
                Unidad: {{ $unidad->nombre }} 
                <a href="{{ url('/unidad/'.$unidad->id_unidad.'/editar') }}" class="btn btn-warning" alt="Editar">
                    <i class="fa-regular fa-pen-to-square"></i>
                </a>
            </h2>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <p><strong>Tipo:</strong> {{ $unidad->tipoDispositivo->nombre ?? $unidad->type }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Fecha:</strong> {{ $unidad->fecha }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>TB ID:</strong> {{ $unidad->tb_id }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>IP:</strong> 
                        @if($unidad->ip)
                            <a href="http://{{ $unidad->ip }}" target="_blank" class="text-primary text-decoration-underline">{{ $unidad->ip }}</a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </p>
                </div>
            </div> 
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Registros</h4>
        </div>
        
        <div class="card-body">
            <ul class="nav nav-tabs mb-0" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-incidencia-tab" data-bs-toggle="pill" data-bs-target="#pills-incidencia" type="button" role="tab" aria-controls="pills-incidencia" aria-selected="true">Incidencias</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-mantenimiento-tab" data-bs-toggle="pill" data-bs-target="#pills-mantenimiento" type="button" role="tab" aria-controls="pills-mantenimiento" aria-selected="false">Mantenimientos</button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-incidencia" role="tabpanel" aria-labelledby="pills-incidencia-tab" tabindex="0">
                    {{-- Tabla de incidencias --}}
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="ps-2">#</th>
                                    <th class="ps-2">Descripción</th>
                                    <th class="ps-2">Estatus</th>
                                    <th class="ps-2">Fecha de Registro</th>
                                    <th class="ps-2">Última Actualización</th>
                                    <th class="ps-2">Usuario que reportó</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unidad->incidencias as $inc)
                                <tr class="text-start">
                                    <td>{{ $inc->id }}</td>
                                    <td id="celIdescription">{{ Str::limit($inc->descripcion, 150, '...') }}</td>
                                    <td>
                                        <span class="badge {{ $inc->status == 'Abierta' ? 'bg-danger' : ($inc->status == 'Cerrada' ? 'bg-success' : 'bg-warning') }}">
                                            {{ $inc->status ?? 'Abierta' }}
                                        </span>
                                    </td>
                                    <td>{{ $inc->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $inc->updated_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $inc->reportado_por ?? (optional($inc->usuario)->name ?? 'Desconocido') }}</td>
                                    <td>
                                        <button class="btn btn-primary" onclick="showViewIncidencia('{{$inc->id }}')" title="Ver Incidencia">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No hay incidencias para esta unidad.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--End of Incidencias-->

                <div class="tab-pane fade " id="pills-mantenimiento" role="tabpanel" aria-labelledby="pills-mantenimiento-tab" tabindex="0">
                    {{-- Tabla de Mantenimientos --}}
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="ps-2">Por</th>
                                    <th class="ps-2">Fecha y hora</th>
                                    <th class="ps-2">Tipo de mantenimiento</th>
                                    <th class="ps-2">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unidad->mantenimientos as $mant)
                                <tr class="text-start">
                                    <td>{{ optional($mant->usuario)->name ?? 'Desconocido' }}</td>
                                    <td>{{ $mant->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $mant->tipo_mantenimiento }}</td>
                                    <td>
                                        <a href="{{ url('/orden/'.$mant->id_orden_trabajo.'/detalle') }}" class="btn btn-primary" alt="Editar">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                        <a href="{{ url('/orden/'.$mant->id_orden_trabajo.'/editar') }}" class="btn btn-warning" alt="Editar">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No hay mantenimientos para esta unidad.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--End of Mantenimientos-->
            </div>
        </div>



    </div>
</div>



<!-- Modal para Ver las incidencias -->
<div class="modal fade" id="VerIncidencia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary2">
                <h5 class="modal-title" style="color: #ffffff" id="modalTitle">Incidencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-fixed">
                        <tbody>
                            <tr class="text-center">
                                <th class="text-center">Usuario que reportó:</th>
                                <td id="celConductor">N/A</td>
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

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
/>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

<script>
    const userLogged = {
        id: "{{ Auth::user()->id }}",
        rol: "{{ Auth::user()->roles->first()->name ?? 'Sin Rol' }}"
    };

    function showViewIncidencia(id) {

        fetch(`/incidencia/${id}`, {
                method: 'get',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        })
        .then(response => response.json())
        .then(data => {
            document.querySelector("#celIncidencia").innerHTML = '<p>' + data.descripcion + '</p>';
            document.querySelector("#celConductor").innerHTML = data.usuario.name;
                    // Inicializar Fancybox
            Fancybox.bind('[data-fancybox="single"]', {
                groupAttr: false,
            });
            $('#VerIncidencia').modal('show');
        })
        .catch(error => console.error('Error:', error));
    }
</script>

@endsection