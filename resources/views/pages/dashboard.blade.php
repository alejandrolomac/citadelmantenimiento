@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])

    <style>
        #calendar-container {
            width: 100%;
            height: 100%;
            min-height: 350px;
            overflow: hidden;
        }

        #calendar {
            width: 100%;
            height: 100%;
        }

        .fc .fc-toolbar-title {
            font-size: 1.2em;
        }

        .fc-toolbar-title {
            color: #343a40;
            font-size: 0.5rem;
            text-transform: capitalize;
        }

        .fc table {
            font-size: 0.85em;
        }
    </style>


    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Unidades</p>
                                    <h5 class="font-weight-bolder" id="unidades-count">
                                        0
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="fa-solid fa-truck-pickup text-sm opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Preventivo</p>
                                    <h5 class="font-weight-bolder" id="preventivos-count">
                                        0
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Correctivo</p>
                                    <h5 class="font-weight-bolder" id="correctivos-count">
                                        0
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row mt-4">
            <div class="col-lg-8 mb-lg-0 mb-4">
                <div class="card z-index-2 h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Total Mantenimientos</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-carousel overflow-hidden h-100 p-0">

                    <div class="card-body p-2">
                        <div id="calendar-container">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>



        <div class="row mt-4">
            <div class="col-lg-8 mb-lg-0 mb-4">
                <div class="card card-carousel overflow-hidden h-100 p-0">
                    <div class="card-body">
                        <h5 class="card-title text-center">Incidencias Urgentes (Por Reportes)</h5>
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unidad</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Descripción</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Reportes</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody id="urgent-incidencias">
                                    <!-- Aquí se listarán las incidencias -->
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a type="button" class="btn bg-gradient-danger text-white btn-sm mb-0" href="/incidencias">
                                Ver Todas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-carousel overflow-hidden h-100 p-0">
                    <div class="card-body">
                        <h5 class="card-title text-center">Próximos Mantenimientos</h5>
                        <ul id="next-maintenance" class="list-group">
                            <!-- Aquí se listarán los mantenimientos dinámicamente -->
                        </ul>

                        <div class="text-center mt-2">
                            <a type="button" class="btn bg-gradient-warning text-white" href="/agenda">
                                Ir a Agenda
                            </a>
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
    <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: '',
                    center: 'title',
                    right: ''
                },
                height: 'auto',
                contentHeight: 'auto',
                aspectRatio: 1.5,
                events: '/dashboard/events',
                eventDidMount: function(info) {
                    if (info.event.extendedProps.description) {
                        tippy(info.el, {
                            content: info.event.extendedProps.description,
                            placement: 'top',
                            animation: 'fade',
                        });
                    }
                }
            });
            calendar.render();



            displayEvents();
            mantPrevCorrec();
            cantUnidMant();
            displayUrgentIncidencias();

        });


        function mantPrevCorrec() {
            fetch("/dashboard/chart-data")
                .then(response => response.json())
                .then(data => {
                    var ctx = document.getElementById("chart-line").getContext("2d");

                    var gradientStroke1 = ctx.createLinearGradient(0, 230, 0, 50);
                    gradientStroke1.addColorStop(1, 'rgba(251, 99, 64, 0.2)');
                    gradientStroke1.addColorStop(0.2, 'rgba(251, 99, 64, 0.0)');
                    gradientStroke1.addColorStop(0, 'rgba(251, 99, 64, 0)');

                    var gradientStroke2 = ctx.createLinearGradient(0, 230, 0, 50);
                    gradientStroke2.addColorStop(1, 'rgba(54, 162, 235, 0.2)');
                    gradientStroke2.addColorStop(0.2, 'rgba(54, 162, 235, 0.0)');
                    gradientStroke2.addColorStop(0, 'rgba(54, 162, 235, 0)');

                    new Chart(ctx, {
                        type: "bar",
                        data: {
                            labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct",
                                "Nov", "Dic"
                            ],
                            datasets: [{
                                    label: "Mantenimientos Preventivos",
                                    tension: 0.4,
                                    borderWidth: 3,
                                    pointRadius: 5,
                                    borderColor: "#fb6340",
                                    backgroundColor: gradientStroke1,
                                    fill: true,
                                    data: data.preventivos,
                                    maxBarThickness: 6
                                },
                                {
                                    label: "Mantenimientos Correctivos",
                                    tension: 0.4,
                                    borderWidth: 3,
                                    pointRadius: 5,
                                    borderColor: "#36A2EB",
                                    backgroundColor: gradientStroke2,
                                    fill: true,
                                    data: data.correctivos,
                                    maxBarThickness: 6
                                }
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true
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
                                    categoryPercentage: 1, // Aumenta el tamaño de los grupos de barras
                                    barPercentage: 1, // Aumenta el grosor de cada barra
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
                })
                .catch(error => console.error("Error al obtener los datos del gráfico:", error));
        }

        function cantUnidMant() {
            fetch("/dashboard/stats")
                .then(response => response.json())
                .then(data => {
                    document.getElementById("unidades-count").innerText = data.unidades;
                    document.getElementById("preventivos-count").innerText = data.preventivos;
                    document.getElementById("correctivos-count").innerText = data.correctivos;
                })
                .catch(error => console.error("Error al obtener los datos del dashboard:", error));
        }

        function displayEvents() {
            fetch('/dashboard/next-events')
                .then(response => response.json())
                .then(data => {
                    const maintenanceList = document.getElementById('next-maintenance');
                    maintenanceList.classList.add('list-group');

                    data.forEach(item => {
                        const li = document.createElement('li');
                        li.textContent = `${item.titulo} - ${new Date(item.fecha_inicio).toLocaleString()}`;
                        li.classList.add('list-group-item', 'list-group-item-secondary');
                        maintenanceList.appendChild(li);
                    });
                })
                .catch(err => console.error('Error fetching next events:', err));
        }

        function displayUrgentIncidencias() {
            fetch('/dashboard/urgent-incidencias')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('urgent-incidencias');
                    tableBody.innerHTML = '';
                    
                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-sm">No hay incidencias pendientes.</td></tr>';
                        return;
                    }

                    data.forEach(item => {
                        const tr = document.createElement('tr');
                        const nombreUnidad = item.unidad ? (item.unidad.nombre + ' - ' + item.unidad.type) : 'Desconocida';
                        const badgeClass = item.status === 'Abierta' ? 'bg-danger' : (item.status === 'Cerrada' ? 'bg-success' : 'bg-warning');
                        
                        tr.innerHTML = `
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">${nombreUnidad}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">${item.descripcion.substring(0, 50)}${item.descripcion.length > 50 ? '...' : ''}</p>
                            </td>
                            <td class="align-middle text-center text-sm">
                                <span class="badge badge-sm bg-gradient-danger">${item.conteo}</span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="badge badge-sm ${badgeClass}">${item.status || 'Abierta'}</span>
                            </td>
                        `;
                        tableBody.appendChild(tr);
                    });
                })
                .catch(err => console.error('Error fetching urgent incidencias:', err));
        }
    </script>
@endpush
