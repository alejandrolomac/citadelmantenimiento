@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detalle del Tipo de Dispositivo'])
    
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header pb-0 d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <p class="mb-0 text-uppercase font-weight-bolder">Detalle: {{ $tipo->nombre }}</p>
                        </div>
                        <div>
                            <a href="{{ route('tipos.edit', $tipo->id) }}" class="btn btn-sm btn-info mb-0">Editar</a>
                            <a href="{{ route('tipos.index') }}" class="btn btn-sm btn-secondary mb-0">Volver</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Nombre</h6>
                                <p class="text-sm font-weight-bold mb-4">{{ $tipo->nombre }}</p>
                            </div>
                            <div class="col-md-12">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Descripción</h6>
                                <p class="text-sm font-weight-bold mb-4">{{ $tipo->descripcion ?? 'Sin descripción' }}</p>
                            </div>
                            <div class="col-md-12">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Usuarios Asignados</h6>
                                @if($tipo->users && $tipo->users->count() > 0)
                                    <ul class="list-group mb-4">
                                        @foreach($tipo->users as $user)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="text-sm font-weight-bold">{{ $user->name }}</span>
                                                <span class="badge bg-secondary badge-sm">{{ optional($user->rol)->name }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-secondary mb-4">Ningún usuario asignado.</p>
                                @endif
                            </div>
                        </div>

                        <hr class="horizontal dark">
                        <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Documentación y Archivos ({{ $tipo->archivos->count() }})</h6>
                        
                        @if($tipo->archivos->count() > 0)
                            <div class="row">
                                <div class="col-12">
                                    <ul class="list-group">
                                        @foreach($tipo->archivos as $archivo)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    @if(in_array(strtolower($archivo->tipo_archivo), ['jpg', 'jpeg', 'png', 'gif']))
                                                        <i class="fa-solid fa-image text-info me-3 fa-2x"></i>
                                                    @elseif(strtolower($archivo->tipo_archivo) == 'pdf')
                                                        <i class="fa-solid fa-file-pdf text-danger me-3 fa-2x"></i>
                                                    @else
                                                        <i class="fa-solid fa-file text-secondary me-3 fa-2x"></i>
                                                    @endif
                                                    <span class="text-sm font-weight-bold text-dark">{{ $archivo->nombre_original }}</span>
                                                </div>
                                                <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-0">Ver / Descargar</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-secondary">No hay archivos registrados para este tipo de dispositivo.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
