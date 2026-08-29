@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => isset($tipo) ? 'Editar Tipo de Dispositivo' : 'Nuevo Tipo de Dispositivo'])
    
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">{{ isset($tipo) ? 'Editar Tipo de Dispositivo' : 'Crear Tipo de Dispositivo' }}</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ isset($tipo) ? route('tipos.update', $tipo->id) : route('tipos.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if(isset($tipo))
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nombre" class="form-control-label">Nombre del Tipo</label>
                                        <input class="form-control @error('nombre') is-invalid @enderror" type="text" name="nombre" value="{{ old('nombre', $tipo->nombre ?? '') }}" required>
                                        @error('nombre')
                                            <span class="text-danger text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="descripcion" class="form-control-label">Descripción</label>
                                        <textarea class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" rows="3">{{ old('descripcion', $tipo->descripcion ?? '') }}</textarea>
                                        @error('descripcion')
                                            <span class="text-danger text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="users" class="form-control-label">Asignar a Usuarios</label>
                                        <select class="form-control @error('users') is-invalid @enderror" name="users[]" multiple size="5">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" 
                                                    @if(isset($tipo) && $tipo->users->contains($user->id)) selected @endif>
                                                    {{ $user->name }} ({{ optional($user->rol)->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Mantén presionada la tecla Ctrl (o Cmd en Mac) para seleccionar varios usuarios.</small>
                                        @error('users')
                                            <span class="text-danger text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="archivos" class="form-control-label">Documentación (Manuales en PDF, Imágenes)</label>
                                        <input class="form-control" type="file" name="archivos[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">Puedes seleccionar varios archivos a la vez.</small>
                                        @error('archivos.*')
                                            <span class="text-danger text-xs d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if(isset($tipo) && $tipo->archivos->count() > 0)
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Archivos Adjuntos</p>
                            <div class="row">
                                <div class="col-12">
                                    <ul class="list-group">
                                        @foreach($tipo->archivos as $archivo)
                                            <li class="list-group-item d-flex justify-content-between align-items-center" id="archivo-{{ $archivo->id }}">
                                                <a href="{{ asset('storage/' . $archivo->ruta_archivo) }}" target="_blank" class="text-info">{{ $archivo->nombre_original }}</a>
                                                <button type="button" class="btn btn-sm btn-outline-danger mb-0 btn-delete-archivo" data-id="{{ $archivo->id }}">Eliminar</button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                            <hr class="horizontal dark">
                            <div class="text-end">
                                <a href="{{ route('tipos.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">{{ isset($tipo) ? 'Actualizar' : 'Guardar' }}</button>
                            </div>
                        </form>
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
    const btns = document.querySelectorAll('.btn-delete-archivo');
    btns.forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm('¿Seguro que deseas eliminar este archivo?')) {
                const id = this.getAttribute('data-id');
                fetch(`/tipos-archivo/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        document.getElementById(`archivo-${id}`).remove();
                    }
                })
                .catch(err => console.error(err));
            }
        });
    });
});
</script>
@endpush
