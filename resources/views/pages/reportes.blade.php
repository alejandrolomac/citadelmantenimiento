@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <style>
        .nav-pills .nav-link.active, 
        .nav-pills .nav-link.active i {
            color: #ffffff !important;
        }
    </style>
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard de Reportes'])

    <div class="container-fluid py-4">
        
        <!-- Pestañas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1 bg-white border-radius-lg" id="reportes-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 active" id="general-tab" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                                <i class="fa-solid fa-chart-pie me-2"></i> General
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1" id="incidencias-tab" data-bs-toggle="tab" href="#incidencias" role="tab" aria-controls="incidencias" aria-selected="false">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i> Incidencias
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1" id="dispositivos-tab" data-bs-toggle="tab" href="#dispositivos" role="tab" aria-controls="dispositivos" aria-selected="false">
                                <i class="fa-solid fa-server me-2"></i> Dispositivos
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="tab-content" id="reportes-content">
            
            <!-- ========================================== -->
            <!-- 1. PESTAÑA GENERAL -->
            <!-- ========================================== -->
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <!-- KPIs -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Dispositivos Activos</p>
                                            <h5 class="font-weight-bolder" id="kpi-dispositivos">0</h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                            <i class="fa-solid fa-server text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Incidencias Abiertas</p>
                                            <h5 class="font-weight-bolder text-danger" id="kpi-inc-abiertas">0</h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                            <i class="fa-solid fa-triangle-exclamation text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Incidencias Resueltas</p>
                                            <h5 class="font-weight-bolder text-success" id="kpi-inc-resueltas">0</h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                            <i class="fa-solid fa-check-double text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Mantenimientos (Mes)</p>
                                            <h5 class="font-weight-bolder text-info" id="kpi-mantenimientos">0 Prev / 0 Corr</h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                            <i class="fa-solid fa-screwdriver-wrench text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráficas Principales -->
                <div class="row">
                    <div class="col-lg-8 mb-lg-0 mb-4">
                        <div class="card z-index-2 h-100">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Tendencia de Mantenimientos (Año Actual)</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-mantenimientos-line" class="chart-canvas" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card z-index-2 h-100">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Distribución de Mantenimientos (Mes Actual)</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-tipos-doughnut" class="chart-canvas" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 2. PESTAÑA INCIDENCIAS -->
            <!-- ========================================== -->
            <div class="tab-pane fade" id="incidencias" role="tabpanel" aria-labelledby="incidencias-tab">
                <div class="row mb-4">
                    <div class="col-lg-8 mb-lg-0 mb-4">
                        <div class="card z-index-2 h-100">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Incidencias Reportadas vs Resueltas (Año Actual)</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-incidencias-bar" class="chart-canvas" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card z-index-2 h-100">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Estatus de Incidencias (Mes Actual)</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-incidencias-doughnut" class="chart-canvas" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize text-danger">Top 5 Dispositivos con Más Incidencias (Mes Actual)</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0" id="table-top-dispositivos">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dispositivo</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total de Incidencias</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Llenado por JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comparación Incidencias vs Mantenimiento -->
                <div class="row mt-4 mb-3 align-items-center">
                    <div class="col-md-3">
                        <label>Mes:</label>
                        <select id="incidencias-mes" class="form-control form-control-sm" onchange="loadIncidenciasComparacion()">
                            <option value="all">Todos los meses</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Año:</label>
                        <select id="incidencias-anio" class="form-control form-control-sm" onchange="loadIncidenciasComparacion()">
                            <option value="all">Todos los años</option>
                            @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2 mt-4">
                        <button class="btn btn-outline-primary btn-sm w-100" onclick="loadIncidenciasComparacion()">Filtrar</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Resolución de Incidencias vs Mantenimientos</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0" id="table-comparacion-incidencias">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Incidencia ID</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mantenimiento Asignado</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dispositivo</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha de Incidencia</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha de Mantenimiento</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-info">Tiempo de Resolución</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Llenado por JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- ========================================== -->
            <!-- 4. PESTAÑA DISPOSITIVOS -->
            <!-- ========================================== -->
            <div class="tab-pane fade" id="dispositivos" role="tabpanel" aria-labelledby="dispositivos-tab">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-3">
                        <label>Mes:</label>
                        <select id="disp-mes" class="form-control form-control-sm">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Año:</label>
                        <select id="disp-anio" class="form-control form-control-sm">
                            @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2 mt-4">
                        <button class="btn btn-outline-primary btn-sm w-100" onclick="loadDispositivosData()">Filtrar</button>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Resumen Global por Dispositivo</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0" id="table-resumen-disp">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dispositivo</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TB ID</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-info">Total Preventivos</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-warning">Total Correctivos</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-danger">Total Incidencias</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Llenado por JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <script src="./assets/js/plugins/chartjs.min.js"></script>
    <script>
        // Variables globales de gráficas para destruirlas antes de recargar
        let chartMantLine, chartTiposDoughnut, chartIncBar, chartIncDoughnut, chartTecnicosBar;

        document.addEventListener("DOMContentLoaded", function() {
            // Cargar datos iniciales
            loadKPIs();
            loadGeneralCharts();
            loadIncidenciasCharts();
            loadIncidenciasComparacion();
            loadDispositivosData();
        });

        // ==========================================
        // 1. GENERAL
        // ==========================================
        function loadKPIs() {
            fetch('/reportes/kpis')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('kpi-dispositivos').textContent = data.dispositivos;
                    document.getElementById('kpi-inc-abiertas').textContent = data.incidencias_abiertas;
                    document.getElementById('kpi-inc-resueltas').textContent = data.incidencias_resueltas;
                    document.getElementById('kpi-mantenimientos').textContent = `${data.mant_preventivos} Prev / ${data.mant_correctivos} Corr`;
                });
        }

        function loadGeneralCharts() {
            // Gráfica de líneas (Mantenimientos)
            fetch('/reportes/mantenimientos-mensuales')
                .then(res => res.json())
                .then(data => {
                    if (chartMantLine) chartMantLine.destroy();
                    var ctx1 = document.getElementById("chart-mantenimientos-line").getContext("2d");
                    chartMantLine = new Chart(ctx1, {
                        type: "line",
                        data: {
                            labels: data.meses,
                            datasets: [{
                                label: "Preventivo",
                                tension: 0.4,
                                borderWidth: 3,
                                borderColor: "#17c1e8", // Info color
                                backgroundColor: "rgba(23, 193, 232, 0.2)",
                                fill: true,
                                data: data.preventivos,
                            }, {
                                label: "Correctivo",
                                tension: 0.4,
                                borderWidth: 3,
                                borderColor: "#fbcf33", // Warning color
                                backgroundColor: "rgba(251, 207, 51, 0.2)",
                                fill: true,
                                data: data.correctivos,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: true } },
                            interaction: { intersect: false, mode: 'index' },
                        }
                    });
                });

            // Gráfica de Dona (Tipos)
            fetch('/reportes/tipos-mantenimiento')
                .then(res => res.json())
                .then(data => {
                    if (chartTiposDoughnut) chartTiposDoughnut.destroy();
                    var ctx2 = document.getElementById("chart-tipos-doughnut").getContext("2d");
                    chartTiposDoughnut = new Chart(ctx2, {
                        type: "doughnut",
                        data: {
                            labels: ["Preventivo", "Correctivo"],
                            datasets: [{
                                data: [data.Preventivo, data.Correctivo],
                                backgroundColor: ["#17c1e8", "#fbcf33"], // Info y Warning
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                        }
                    });
                });
        }

        // ==========================================
        // 2. INCIDENCIAS
        // ==========================================
        function loadIncidenciasCharts() {
            // Barras (Mensuales)
            fetch('/reportes/incidencias-mensuales')
                .then(res => res.json())
                .then(data => {
                    if (chartIncBar) chartIncBar.destroy();
                    var ctx3 = document.getElementById("chart-incidencias-bar").getContext("2d");
                    chartIncBar = new Chart(ctx3, {
                        type: "bar",
                        data: {
                            labels: data.meses,
                            datasets: [{
                                label: "Reportadas",
                                data: data.reportadas,
                                backgroundColor: "#ea0606", // Danger
                                borderRadius: 4,
                            }, {
                                label: "Resueltas",
                                data: data.resueltas,
                                backgroundColor: "#82d616", // Success
                                borderRadius: 4,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                        }
                    });
                });

            // Dona (Estatus)
            fetch('/reportes/incidencias-estatus')
                .then(res => res.json())
                .then(data => {
                    if (chartIncDoughnut) chartIncDoughnut.destroy();
                    var ctx4 = document.getElementById("chart-incidencias-doughnut").getContext("2d");
                    chartIncDoughnut = new Chart(ctx4, {
                        type: "doughnut",
                        data: {
                            labels: ["Abiertas", "Resueltas"],
                            datasets: [{
                                data: [data.Abiertas, data.Resueltas],
                                backgroundColor: ["#ea0606", "#82d616"], // Danger y Success
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                        }
                    });
                });

            // Top Dispositivos
            fetch('/reportes/incidencias-top-dispositivos')
                .then(res => res.json())
                .then(data => {
                    const tbody = document.querySelector('#table-top-dispositivos tbody');
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="2" class="text-center text-sm">Sin datos para mostrar.</td></tr>';
                    }
                    data.forEach(item => {
                        tbody.innerHTML += `
                            <tr>
                                <td><div class="d-flex px-3 py-1"><h6 class="mb-0 text-sm">${item.dispositivo}</h6></div></td>
                                <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">${item.total}</span></td>
                            </tr>
                        `;
                    });
                });
        }

        function loadIncidenciasComparacion() {
            const mes = document.getElementById('incidencias-mes').value;
            const anio = document.getElementById('incidencias-anio').value;

            fetch(`/reportes/incidencias-comparacion?mes=${mes}&anio=${anio}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.querySelector('#table-comparacion-incidencias tbody');
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-sm">Sin datos para mostrar en este periodo.</td></tr>';
                    }
                    data.forEach(item => {
                        tbody.innerHTML += `
                            <tr>
                                <td>
                                    <a href="/incidencias?show_incidencia=${item.incidencia_id}" class="text-primary font-weight-bold text-sm text-decoration-underline" target="_blank">
                                        INC-${item.incidencia_id}
                                    </a>
                                </td>
                                <td>
                                    <a href="/orden/${item.orden_id}/completar-registro" class="text-primary font-weight-bold text-sm text-decoration-underline" target="_blank">
                                        ${item.no_orden}
                                    </a>
                                </td>
                                <td><span class="text-secondary text-sm font-weight-bold">${item.dispositivo}</span></td>
                                <td><span class="text-secondary text-sm">${item.fecha_incidencia}</span></td>
                                <td><span class="text-secondary text-sm">${item.fecha_orden}</span></td>
                                <td><span class="badge badge-sm bg-gradient-info"><i class="fa-regular fa-clock me-1"></i>${item.tiempo_resolucion}</span></td>
                            </tr>
                        `;
                    });
                });
        }



        // ==========================================
        // 4. DISPOSITIVOS
        // ==========================================
        function loadDispositivosData() {
            const mes = document.getElementById('disp-mes').value;
            const anio = document.getElementById('disp-anio').value;

            fetch(`/reportes/resumen-dispositivos?mes=${mes}&anio=${anio}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.querySelector('#table-resumen-disp tbody');
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-sm">Sin datos para mostrar.</td></tr>';
                    }
                    data.forEach(item => {
                        tbody.innerHTML += `
                            <tr>
                                <td><div class="d-flex px-3 py-1"><h6 class="mb-0 text-sm">${item.dispositivo}</h6></div></td>
                                <td><p class="text-xs font-weight-bold mb-0">${item.tb_id}</p></td>
                                <td class="align-middle text-center"><span class="badge badge-sm bg-gradient-info">${item.total_preventivo}</span></td>
                                <td class="align-middle text-center"><span class="badge badge-sm bg-gradient-warning">${item.total_correctivo}</span></td>
                                <td class="align-middle text-center"><span class="badge badge-sm bg-gradient-danger">${item.total_incidencias}</span></td>
                            </tr>
                        `;
                    });
                });
        }
    </script>
@endpush
