@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Tipos de Dispositivos'])
    
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success text-white">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between">
                        <h6>Catálogo de Tipos de Dispositivos</h6>
                        <a href="{{ route('tipos.create') }}" class="btn btn-sm btn-primary">Nuevo Tipo</a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Descripción</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Archivos (Documentación)</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Cant. de Dispositivos</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tipos as $tipo)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <h6 class="mb-0 text-sm">{{ $tipo->nombre }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ Str::limit($tipo->descripcion, 50) }}</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-info">{{ $tipo->archivos->count() }} archivos</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-success">{{ $tipo->unidades_count }} disp.</span>
                                        </td>
                                        <td class="align-middle text-end pe-4">
                                            <a href="{{ route('tipos.show', $tipo->id) }}" class="text-secondary font-weight-bold text-xs me-2" data-toggle="tooltip" data-original-title="Ver detalle">
                                                Ver
                                            </a>
                                            <a href="{{ route('tipos.edit', $tipo->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Editar tipo">
                                                Editar
                                            </a>
                                            <form action="{{ route('tipos.destroy', $tipo->id) }}" method="POST" class="d-inline-block ms-2" onsubmit="return confirm('¿Estás seguro de eliminar este tipo de dispositivo y todos sus archivos?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger font-weight-bold text-xs border-0 bg-transparent p-0">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if($tipos->isEmpty())
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-sm text-secondary">No hay tipos de dispositivos registrados.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
