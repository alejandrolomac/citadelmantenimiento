@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Reportes'])
    <style>
        #tendenciaMant {
          height: 300px !important;
        }
      
        #eficienciaTecnicos {
          height: 250px !important;
        }
      </style>


    <div class="container-fluid py-4">
        <div class="row">

            <div class="nav-wrapper position-relative end-0">

                <div class="card">
                    <ul class="nav nav-pills nav-fill p-1" id="estadistica-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 active" id="general-tab" data-bs-toggle="tab"
                                data-bs-target="#general" type="button" role="tab" aria-controls="general"
                                aria-selected="true">
                                General
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1" id="suspension-tab" data-bs-toggle="tab"
                                data-bs-target="#suspension" type="button" role="tab" aria-controls="suspension"
                                aria-selected="true">
                                Mantenimiento
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1" id="frenos-tab" data-bs-toggle="tab" data-bs-target="#frenos"
                                type="button" role="tab" aria-controls="frenos" aria-selected="true">
                                Técnicos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1" id="diferencial-tab" data-bs-toggle="tab"
                                data-bs-target="#diferencial" type="button" role="tab" aria-controls="diferencial"
                                aria-selected="true">
                               Unidad
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="tab-content" id="estadistica-content">

            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="row mt-4">
                    <div class="col-lg-7 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Mantenimiento Preventivo</h6>
                                <p id="variacion-mant" class="text-sm mb-0">
                                    <i class="fa fa-minus text-secondary"></i>
                                    <span class="font-weight-bold">Sin cambios</span> respecto al mes anterior
                                </p>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--
                                                    
                                                Tabla de Unidades que mas mantenimiento correctivo
                                                han recibido en el mes (Preventivo)
                                                
                                        -->
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Unidades con más mantenimiento <strong>preventivo</strong> en el
                                    mes
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <div class="row mb-3">
                                        <!-- Select para el Mes -->
                                        <div class="col-md-6">
                                            <label for="mesTablePrev">Seleccione un mes:</label>
                                            <select id="mesTablePrev" class="form-control">
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}"
                                                        {{ $i == date('m') ? 'selected' : '' }}>
                                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>

                                        <!-- Select para el Año -->
                                        <div class="col-md-6">
                                            <label for="anioTablePrev">Seleccione un año:</label>
                                            <select id="anioTablePrev" class="form-control">
                                                @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                                    <!-- Últimos 5 años -->
                                                    <option value="{{ $i }}"
                                                        {{ $i == date('Y') ? 'selected' : '' }}>
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="table-responsive p-0">
                                        <table id="tableMantUnidadPrev"
                                            class="table table-bordered table-hover align-items-center">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Unidad</th>
                                                    <th>Cantidad</th>
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


                </div>





                <div class="row mt-4">
                    <div class="col-lg-7 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Mantenimiento Correctivo</h6>
                                <p id="variacion-Correc" class="text-sm mb-0">
                                    <i class="fa fa-minus text-secondary"></i>
                                    <span class="font-weight-bold">Sin cambios</span> respecto al mes anterior
                                </p>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-line2" class="chart-canvas" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--

                                                Tabla de Unidades que mas mantenimiento correctivo
                                                han recibido en el mes (Correctivo)
                                                
                                            -->
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Unidades con más mantenimiento <strong>correctivo</strong> en
                                    el mes</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row mb-3">
                                    <!-- Select para el Mes -->
                                    <div class="col-md-6">
                                        <label for="mesTableCorrect">Seleccione un mes:</label>
                                        <select id="mesTableCorrect" class="form-control">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}"
                                                    {{ $i == date('m') ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <!-- Select para el Año -->
                                    <div class="col-md-6">
                                        <label for="anioTableCorrect">Seleccione un año:</label>
                                        <select id="anioTableCorrect" class="form-control">
                                            @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                                <!-- Últimos 5 años -->
                                                <option value="{{ $i }}"
                                                    {{ $i == date('Y') ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive p-0">
                                    <table id="tableMantUnidadCorrec"
                                        class="table table-bordered table-hover align-items-center">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Unidad</th>
                                                <th>Cantidad</th>
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





            </div>


            <div class="tab-pane fade" id="suspension" role="tabpanel" aria-labelledby="suspension-tab">
                <div class="row mt-4">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Tipos de Mantenimientos</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <div id="tiposMantenimientos" style="width: 100%; height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    


                </div>

                
            </div>


            <div class="tab-pane fade" id="frenos" role="tabpanel" aria-labelledby="frenos-tab">
                <div class="row mt-4">
                    <div class="col-lg-12 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Frecuencia de mantenimiento (preventivo y correctivo) para cada unidad a lo largo del tiempo</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <div id="tendenciaMant"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="row mt-4">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="card ">
                            <div class="card-header pb-0 p-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-2">Técnicos Preventivo</h6>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="mesP">Mes:</label>
                                        <select id="mesP" class="form-control form-control-sm">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}"
                                                    {{ $i == date('m') ? 'selected' : '' }}>
                                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="anioP">Año:</label>
                                        <select id="anioP" class="form-control form-control-sm">
                                            @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
                                                <option value="{{ $i }}"
                                                    {{ $i == date('Y') ? 'selected' : '' }}>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>

                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a type="button" onclick="tecnicosPreventivos()"
                                        class="btn btn-outline-secondary btn-sm mt-2">Buscar</a>
                                </div>



                                <div id="chartTecnicos" style="width: 100%; height: 400px;"></div>

                            </div>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header pb-0 p-3">
                                <h6 class="mb-0">Técnicos Correctivo</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row mb-3">
                                    <div class="col-md-6">

                                        <label for="mesC">Mes:</label>
                                        <select id="mesC" class="form-control form-control-sm">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}"
                                                    {{ $i == date('m') ? 'selected' : '' }}>
                                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="anioC">Año:</label>
                                        <select id="anioC" class="form-control form-control-sm">
                                            @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
                                                <option value="{{ $i }}"
                                                    {{ $i == date('Y') ? 'selected' : '' }}>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a type="button" onclick="tecnicosCorrectivos()"
                                        class="btn btn-outline-secondary btn-sm mt-2">Buscar</a>
                                </div>


                                <div id="chartTecnicosC" style="width: 100%; height: 400px;"></div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="tab-pane fade" id="diferencial" role="tabpanel" aria-labelledby="diferencial-tab">
                <div class="row mt-4">
                    <div class="col-lg-12 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Resumen por unidad</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive p-0">
                                    <table id="summaryTable" class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Unidad</th>
                                                <th>Total Preventivo</th>
                                                <th>Total Correctivo</th>
                                                                                                                                                <th>Técnico</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>



            <div class="tab-pane fade" id="electrico" role="tabpanel" aria-labelledby="electrico-tab">
                <div class="row mt-4">
                    <div class="col-lg-7 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Mantenimiento Preventivo</h6>
                                <p class="text-sm mb-0">
                                    <i class="fa fa-arrow-up text-success"></i>
                                    <span class="font-weight-bold">aumento 4%</span> en 2025
                                </p>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Mantenimiento Preventivo</h6>
                                <p class="text-sm mb-0">
                                    <i class="fa fa-arrow-up text-success"></i>
                                    <span class="font-weight-bold">aumento 4%</span> en 2025
                                </p>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-line2" class="chart-canvas" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="row mt-4">
                    <div class="col-lg-7 mb-lg-0 mb-4">
                        <div class="card ">
                            <div class="card-header pb-0 p-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-2">Sales by Country</h6>
                                </div>
                            </div>
                            <div class="card-body p-3">


                            </div>
                        </div>
                    </div>


                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header pb-0 p-3">
                                <h6 class="mb-0">Unidades</h6>
                            </div>
                            <div class="card-body p-3">


                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="tab-pane fade" id="kilometraje" role="tabpanel" aria-labelledby="kilometraje-tab">
                <div class="row mt-4">
                    <div class="col-lg-7 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Mantenimiento Preventivo</h6>
                                <p class="text-sm mb-0">
                                    <i class="fa fa-arrow-up text-success"></i>
                                    <span class="font-weight-bold">aumento 4%</span> en 2025
                                </p>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header pb-0 pt-3 bg-transparent">
                                <h6 class="text-capitalize">Mantenimiento Preventivo</h6>
                                <p class="text-sm mb-0">
                                    <i class="fa fa-arrow-up text-success"></i>
                                    <span class="font-weight-bold">aumento 4%</span> en 2025
                                </p>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-line2" class="chart-canvas" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="row mt-4">
                    <div class="col-lg-7 mb-lg-0 mb-4">
                        <div class="card ">
                            <div class="card-header pb-0 p-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-2">Sales by Country</h6>
                                </div>
                            </div>
                            <div class="card-body p-3">


                            </div>
                        </div>
                    </div>


                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header pb-0 p-3">
                                <h6 class="mb-0">Unidades</h6>
                            </div>
                            <div class="card-body p-3">


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
        document.addEventListener("DOMContentLoaded", function() {





            cantMantPreventivo();
            cantMantCorrectivo();
            tecnicosPreventivos();
            tecnicosCorrectivos();
            tableUnidadesPre();
            tableUnidadesCorrec();
            tiposMantenimiento();
            
            
            tendenciaMant();
            
            tableUnidadMant();

        });


        function cantMantPreventivo() {
            fetch("/reportes/prev/chart-data")
                .then(response => response.json())
                .then(responseData => {
                    const data = responseData.data;
                    const variacion = responseData.variacion;

                    var ctx1 = document.getElementById("chart-line").getContext("2d");

                    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
                    gradientStroke1.addColorStop(1, 'rgba(251, 99, 64, 0.2)');
                    gradientStroke1.addColorStop(0.2, 'rgba(251, 99, 64, 0.0)');
                    gradientStroke1.addColorStop(0, 'rgba(251, 99, 64, 0)');

                    new Chart(ctx1, {
                        type: "line",
                        data: {
                            labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep",
                                "Oct", "Nov", "Dic"
                            ],
                            datasets: [{
                                label: "Mantenimientos Preventivos",
                                tension: 0.4,
                                borderWidth: 3,
                                pointRadius: 5,
                                borderColor: "#fb6340",
                                backgroundColor: gradientStroke1,
                                fill: true,
                                data: data,
                                maxBarThickness: 6
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                            scales: {
                                y: {
                                    grid: {
                                        drawBorder: false,
                                        display: true,
                                        drawOnChartArea: true,
                                        drawTicks: false,
                                        borderDash: [5, 5]
                                    },
                                    ticks: {
                                        padding: 10,
                                        color: '#fbfbfb',
                                        font: {
                                            size: 11,
                                            family: "Open Sans"
                                        }
                                    },
                                },
                                x: {
                                    grid: {
                                        drawBorder: false,
                                        display: false,
                                        drawOnChartArea: false,
                                        drawTicks: false,
                                        borderDash: [5, 5]
                                    },
                                    ticks: {
                                        color: '#ccc',
                                        padding: 20,
                                        font: {
                                            size: 11,
                                            family: "Open Sans"
                                        }
                                    },
                                },
                            },
                        },
                    });

                    // Actualizar el texto del porcentaje de variación
                    const indicador = document.getElementById("variacion-mant");
                    if (variacion > 0) {
                        indicador.innerHTML =
                            `<i class="fa fa-arrow-up text-success"></i>
                                       <span class="font-weight-bold">Aumento ${variacion}%</span> respecto al mes anterior`;
                    } else if (variacion < 0) {
                        indicador.innerHTML =
                            `<i class="fa fa-arrow-down text-danger"></i>
                                       <span class="font-weight-bold">Disminución ${Math.abs(variacion)}%</span> respecto al mes anterior`;
                    } else {
                        indicador.innerHTML = `<i class="fa fa-minus text-secondary"></i>
                                       <span class="font-weight-bold">Sin cambios</span> respecto al mes anterior`;
                    }
                })
                .catch(error => console.error("Error al obtener los datos del gráfico:", error));
        }

        function tableUnidadesPre() {
            let table = $('#tableMantUnidadPrev').DataTable({
                scrollY: '50vh',
                scrollCollapse: true,
                paging: false,
                searching: false,
                ordering: true,
                language: {
                    "url": "assets/js/plugins/es-ES.json"
                },
                order: [
                    [2, 'desc']
                ], // Ordenar por cantidad
                columnDefs: [{
                        orderable: false,
                        targets: [0, 1]
                    }, // Deshabilitar orden en # y Unidad
                ],
                ajax: {
                    url: "{{ url('/reporte/mantTablePrev/data') }}",
                    dataSrc: "", // Permite que DataTables procese el array directamente
                    data: function(d) {
                        d.mes = $('#mesTablePrev').val();
                        d.anio = $('#anioTablePrev').val();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la petición AJAX:", error);
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'unidad'
                    },
                    {
                        data: 'cantidad'
                    }
                ]
            });

            // Actualizar la tabla cuando cambian el mes o el año
            $('#mesTablePrev, #anioTablePrev').change(function() {
                console.log("Mes:", $('#mesTablePrev').val(), "Año:", $('#anioTablePrev').val());
                table.ajax.reload();
            });

        }

        function tableUnidadesCorrec() {
            let table = $('#tableMantUnidadCorrec').DataTable({
                scrollY: '50vh',
                scrollCollapse: true,
                paging: false,
                searching: false,
                ordering: true,
                language: {
                    "url": "assets/js/plugins/es-ES.json"
                },
                order: [
                    [2, 'desc']
                ], // Ordenar por cantidad
                columnDefs: [{
                        orderable: false,
                        targets: [0, 1]
                    }, // Deshabilitar orden en # y Unidad
                ],
                ajax: {
                    url: "{{ url('/reporte/mantTableCorrec/data') }}",
                    dataSrc: "", // Permite que DataTables procese el array directamente
                    data: function(d) {
                        d.mes = $('#mesTableCorrect').val();
                        d.anio = $('#anioTableCorrect').val();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la petición AJAX:", error);
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'unidad'
                    },
                    {
                        data: 'cantidad'
                    }
                ]
            });

            // Actualizar la tabla cuando cambian el mes o el año
            $('#mesTableCorrect, #anioTableCorrect').change(function() {
                console.log("Mes:", $('#mesTableCorrect').val(), "Año:", $('#anioTableCorrect').val());
                table.ajax.reload();
            });
        }

        function cantMantCorrectivo() {
            fetch("/reportes/chart-data")
                .then(response => response.json())
                .then(responseData => {
                    const data = responseData.data;
                    const variacion = responseData.variacion;

                    var ctx1 = document.getElementById("chart-line2").getContext("2d");

                    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
                    gradientStroke1.addColorStop(1, 'rgba(70, 130, 180, 0.2)'); // Azul acero con opacidad 20%
                    gradientStroke1.addColorStop(0.2, 'rgba(70, 130, 180, 0.0)'); // Azul acero con opacidad 0%
                    gradientStroke1.addColorStop(0, 'rgba(70, 130, 180, 0)'); // Azul acero con opacidad 0%

                    new Chart(ctx1, {
                        type: "line",
                        data: {
                            labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep",
                                "Oct", "Nov", "Dic"
                            ],
                            datasets: [{
                                label: "Mantenimientos Preventivos",
                                tension: 0.4,
                                borderWidth: 3,
                                pointRadius: 5,
                                borderColor: "#4682B4",
                                backgroundColor: gradientStroke1,
                                fill: true,
                                data: data,
                                maxBarThickness: 6
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                            scales: {
                                y: {
                                    grid: {
                                        drawBorder: false,
                                        display: true,
                                        drawOnChartArea: true,
                                        drawTicks: false,
                                        borderDash: [5, 5]
                                    },
                                    ticks: {
                                        padding: 10,
                                        color: '#fbfbfb',
                                        font: {
                                            size: 11,
                                            family: "Open Sans"
                                        }
                                    },
                                },
                                x: {
                                    grid: {
                                        drawBorder: false,
                                        display: false,
                                        drawOnChartArea: false,
                                        drawTicks: false,
                                        borderDash: [5, 5]
                                    },
                                    ticks: {
                                        color: '#ccc',
                                        padding: 20,
                                        font: {
                                            size: 11,
                                            family: "Open Sans"
                                        }
                                    },
                                },
                            },
                        },
                    });

                    // Actualizar el texto del porcentaje de variación
                    const indicador = document.getElementById("variacion-Correc");
                    if (variacion > 0) {
                        indicador.innerHTML =
                            `<i class="fa fa-arrow-up text-success"></i>
                                       <span class="font-weight-bold">Aumento ${variacion}%</span> respecto al mes anterior`;
                    } else if (variacion < 0) {
                        indicador.innerHTML =
                            `<i class="fa fa-arrow-down text-danger"></i>
                                       <span class="font-weight-bold">Disminución ${Math.abs(variacion)}%</span> respecto al mes anterior`;
                    } else {
                        indicador.innerHTML = `<i class="fa fa-minus text-secondary"></i>
                                       <span class="font-weight-bold">Sin cambios</span> respecto al mes anterior`;
                    }
                })
                .catch(error => console.error("Error al obtener los datos del gráfico:", error));

        }



        function tecnicosPreventivos() {
            let mes = document.getElementById('mesP').value;
            let anio = document.getElementById('anioP').value;

            fetch(`/reportes/tecnicosPreventivos?mes=${mes}&anio=${anio}`)
                .then(response => response.json())
                .then(data => {
                    let categorias = data.map(item => item.tecnico);
                    let valores = data.map(item => item.cantidad);

                    let options = {
                        series: [{
                            data: valores
                        }],
                        chart: {
                            type: 'bar',
                            height: 400
                        },
                        plotOptions: {
                            bar: {
                                barHeight: '100%',
                                distributed: true,
                                horizontal: true,
                                dataLabels: {
                                    position: 'bottom'
                                }
                            }
                        },
                        colors: ['#33b2df', '#546E7A', '#d4526e', '#13d8aa', '#A5978B', '#2b908f', '#f9a3a4',
                            '#90ee7e', '#f48024', '#69d2e7'
                        ],
                        dataLabels: {
                            enabled: true,
                            textAnchor: 'start',
                            style: {
                                colors: ['#fff']
                            },
                            formatter: function(val, opt) {
                                return opt.w.globals.labels[opt.dataPointIndex] + ": " + val;
                            },
                            offsetX: 0,
                            dropShadow: {
                                enabled: true
                            }
                        },
                        stroke: {
                            width: 1,
                            colors: ['#fff']
                        },
                        xaxis: {
                            categories: categorias
                        },
                        yaxis: {
                            labels: {
                                show: false
                            }
                        },
                        title: {
                            text: 'Mantenimientos Preventivos por técnico',
                            align: 'center',
                            floating: true
                        },
                        tooltip: {
                            theme: 'dark',
                            x: {
                                show: false
                            },
                            y: {
                                title: {
                                    formatter: function() {
                                        return ''
                                    }
                                }
                            }
                        }
                    };

                    document.getElementById("chartTecnicos").innerHTML = "";
                    let chart = new ApexCharts(document.querySelector("#chartTecnicos"), options);
                    chart.render();
                })
                .catch(error => console.error("Error al obtener datos:", error));
        }


        function tecnicosCorrectivos() {
            let mes = document.getElementById('mesC').value;
            let anio = document.getElementById('anioC').value;

            fetch(`/reportes/tecnicosCorrectivos?mes=${mes}&anio=${anio}`)
                .then(response => response.json())
                .then(data => {
                    let categorias = data.map(item => item.tecnico);
                    let valores = data.map(item => item.cantidad);

                    let options = {
                        series: [{
                            data: valores
                        }],
                        chart: {
                            type: 'bar',
                            height: 400
                        },
                        plotOptions: {
                            bar: {
                                barHeight: '100%',
                                distributed: true,
                                horizontal: true,
                                dataLabels: {
                                    position: 'bottom'
                                }
                            }
                        },
                        colors: ['#33b2df', '#546E7A', '#d4526e', '#13d8aa', '#A5978B', '#2b908f', '#f9a3a4',
                            '#90ee7e', '#f48024', '#69d2e7'
                        ],
                        dataLabels: {
                            enabled: true,
                            textAnchor: 'start',
                            style: {
                                colors: ['#fff']
                            },
                            formatter: function(val, opt) {
                                return opt.w.globals.labels[opt.dataPointIndex] + ": " + val;
                            },
                            offsetX: 0,
                            dropShadow: {
                                enabled: true
                            }
                        },
                        stroke: {
                            width: 1,
                            colors: ['#fff']
                        },
                        xaxis: {
                            categories: categorias
                        },
                        yaxis: {
                            labels: {
                                show: false
                            }
                        },
                        title: {
                            text: 'Mantenimientos Correctivos por técnico',
                            align: 'center',
                            floating: true
                        },
                        tooltip: {
                            theme: 'dark',
                            x: {
                                show: false
                            },
                            y: {
                                title: {
                                    formatter: function() {
                                        return ''
                                    }
                                }
                            }
                        }
                    };

                    document.getElementById("chartTecnicosC").innerHTML = "";
                    let chart = new ApexCharts(document.querySelector("#chartTecnicosC"), options);
                    chart.render();
                })
                .catch(error => console.error("Error al obtener datos:", error));
        }

        async function tiposMantenimiento() {
            const response = await fetch('/reportes/tipos-mantenimiento');
            const data = await response.json();

            var options = {
                series: [data.preventivo, data.correctivo],
                chart: {
                    type: 'pie',
                    toolbar: {
                        show: true, // Habilita el menú de exportación
                        tools: {
                            download: true, // Permite descargar PNG, SVG y CSV
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    }
                },
                labels: ['Preventivo', 'Correctivo'],
                colors: ['#fb6340', '#4682B4'],
                title: {
                    text: "Proporción de Mantenimientos",
                    align: "center"
                }
            };

            var chart = new ApexCharts(document.querySelector("#tiposMantenimientos"), options);
            chart.render();
        }

        async function kilometrajeMantenimiento() {
            const response = await fetch('/reportes/kilometraje-mantenimiento');
            const data = await response.json();

            // Extraer datos
            const vehiculos = data.map(item => `Unidad ${item.nombre}`);
            const kilometraje = data.map(item => parseInt(item.kilometraje_unidad));
            const mantenimientosPreventivos = data.map(item => item.total_preventivo);
            const mantenimientosCorrectivos = data.map(item => item.total_correctivo);

            // Configuración del gráfico
            const ctx = document.getElementById('kilometrajeMantenimientos').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: vehiculos,
                    datasets: [{
                            label: 'Mantenimiento Preventivo',
                            data: mantenimientosPreventivos,
                            backgroundColor: 'rgba(40, 167, 69, 0.7)', // Verde
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Mantenimiento Correctivo',
                            data: mantenimientosCorrectivos,
                            backgroundColor: 'rgba(220, 53, 69, 0.7)', // Rojo
                            borderColor: 'rgba(220, 53, 69, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Kilometraje',
                            data: kilometraje,
                            type: 'line',
                            borderColor: 'rgba(0, 123, 255, 1)', // Azul
                            borderWidth: 2,
                            fill: false,
                            yAxisID: 'kilometrajeAxis'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantidad de Mantenimientos'
                            }
                        },
                        kilometrajeAxis: {
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Kilometraje'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        }


        function tiempoInactividad() {
            fetch('/reportes/informe-inactividad')
                .then(response => response.json())
                .then(data => {
                    const categories = data.map(item => item.unidad);
                    const seriesData = data.map(item => item.total_inactividad);

                    var options = {
                        series: [{
                            name: 'Tiempo de Inactividad (minutos)',
                            data: seriesData
                        }],
                        chart: {
                            type: 'bar'
                        },
                        xaxis: {
                            categories: categories
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#tiempoInactividad"), options);
                    chart.render();
                })
                .catch(error => console.error('Error fetching data:', error));
        }


        function tendenciaMant() {
            fetch('/reportes/tendencia-mantenimiento')
                .then(response => response.json())
                .then(data => {
                    // Preprocesar los datos para el gráfico
                    const unidades = [...new Set(data.map(item => item.unidad))];
                    const fechas = [...new Set(data.map(item => item.fecha))];
                    const series = unidades.map(unidad => {
                        return {
                            name: unidad,
                            data: fechas.map(fecha => {
                                const record = data.find(item => item.unidad === unidad && item
                                    .fecha === fecha);
                                return record ? record.cantidad : 0;
                            })
                        };
                    });

                    var options = {
                        series: series,
                        chart: {
                            type: 'line'
                        },
                        xaxis: {
                            categories: fechas
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#tendenciaMant"), options);
                    chart.render();
                })
                .catch(error => console.error('Error fetching data:', error));
        }


        function eficienciaTecnicos() {
            fetch('/reportes/eficiencia-tecnico')
                .then(response => response.json())
                .then(data => {
                    const categories = data.map(item => item.tecnico);
                    const seriesData = data.map(item => item.tiempo_promedio);

                    var options = {
                        series: [{
                            name: 'Tiempo Promedio (minutos)',
                            data: seriesData
                        }],
                        chart: {
                            type: 'bar'
                        },
                        xaxis: {
                            categories: categories
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#eficienciaTecnicos"), options);
                    chart.render();
                })
                .catch(error => console.error('Error fetching data:', error));
        }


        function tableUnidadMant() {
            $('#summaryTable').DataTable({
                scrollY: '50vh',
                scrollCollapse: true,
                paging: false,
                responsive: true,
                processing: true,
                serverSide: false,
                language: {
                    "url": "assets/js/plugins/es-ES.json"
                },
                ajax: {
                    url: '/reportes/resumen-unidad',
                    dataSrc: ''
                },
                columns: [
                    { data: 'unidad' },
                    { data: 'total_preventivo' },
                    { data: 'total_correctivo' },
                                                            { data: 'tecnico' }
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        }
    </script>
@endpush
