<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 text-center" href="{{ route('dashboard') }}" target="_blank">
            <img src="{{ asset('img/logo.png') }}" class="navbar-brand-img" alt="main_logo" style="max-height: 5rem; width: auto;">
        </a>
    </div>
    <hr class="horizontal dark mt-0">

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item mt-3 align-items-center">
                <h6 class="ms-3 text-uppercase text-xs font-weight-bolder opacity-6 mb-0">Mantenimiento</h6>
            </li>
            @can('Editar Mantenimiento')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('mantenimiento') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-settings text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Mantenimiento</span>
                </a>
            </li>
            @endcan
            @can('Ver Dispositivos')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('unidades') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-microchip text-sm opacity-10" style="color: #8B8113;"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dispositivos</span>
                </a>
            </li>
            @endcan
            @can('Ver Tipos Dispositivos')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('tipos.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-layer-group text-sm opacity-10" style="color: #e67e22;"></i>
                    </div>
                    <span class="nav-link-text ms-1">Tipos de Dispositivos</span>
                </a>
            </li>
            @endcan
            @can('Ver Agenda')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('agenda') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-calendar-days text-sm opacity-10" style="color: #6495ED;"></i>
                    </div>
                    <span class="nav-link-text ms-1">Agenda</span>
                </a>
            </li>
            @endcan
            @can('Ver Reportes')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reportes') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-chart-bar-32 text-sm opacity-10" style="color: #B22222;"></i>
                    </div>
                    <span class="nav-link-text ms-1">Reportes</span>
                </a>
            </li>
            @endcan
            @can('Ver Incidencias')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('incidencias') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-triangle-exclamation text-sm opacity-10" style="color: #22b26a;"></i>
                    </div>
                    <span class="nav-link-text ms-1">Incidencias</span>
                </a>
            </li>
            @endcan
            
            @can('Ver Usuarios')
            <li class="nav-item mt-3 d-flex align-items-center">
                <h6 class="ms-3 text-uppercase text-xs font-weight-bolder opacity-6 mb-0">Seguridad</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('perfil') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Perfil</span>
                </a>
            </li>
            @endcan
            @can('Ver Usuarios')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('usuarios') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-users text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Usuarios</span>
                </a>
            </li>
            @endcan
            @can('Ver Roles')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('roles') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-users-gear text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Roles</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
</aside>
