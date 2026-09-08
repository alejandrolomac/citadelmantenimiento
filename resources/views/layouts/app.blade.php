<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/faviconc.png') }}">
    <title>
        Citadel Mantenimiento
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.1/css/buttons.bootstrap5.min.css" />
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.18/build/pdfmake.min.js"></script>

        

    <script src="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js
    "></script>
    <link href="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css
    " rel="stylesheet">

    <script src="
https://cdn.jsdelivr.net/gh/loadingio/ldloader@v1.0.0/dist/ldld.min.js
"></script>
<link href="
https://cdn.jsdelivr.net/gh/loadingio/ldloader@v1.0.0/dist/ldld.min.css
" rel="stylesheet">


    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@4.4.0/dist/apexcharts.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/apexcharts@4.4.0/dist/apexcharts.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- CSS Files -->
    <link id="pagestyle" href="/assets/css/argon-dashboard.css" rel="stylesheet" />

    <!-- Estilos personalizados: Cambio de color a #fc4b09 -->
    <style>
        :root {
            --bs-primary: #fc4b09;
            --bs-primary-rgb: 252, 75, 9;
        }
        .bg-primary, .bg-gradient-primary {
            background-color: #fc4b09 !important;
            background-image: none !important;
        }
        .btn-primary {
            background-color: #fc4b09 !important;
            border-color: #fc4b09 !important;
            color: #fff !important;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: #e34207 !important;
            border-color: #e34207 !important;
            color: #fff !important;
        }
        .text-primary {
            color: #fc4b09 !important;
        }
        .border-primary {
            border-color: #fc4b09 !important;
        }
        .navbar-vertical .navbar-nav > .nav-item .nav-link.active .icon,
        .nav-pills .nav-link.active,
        .page-item.active .page-link {
            background-color: #fc4b09 !important;
            border-color: #fc4b09 !important;
            background-image: none !important;
        }
    </style>

        <!-- Manifiesto PWA -->
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#fc4b09">
<link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
</head>

<body class="{{ $class ?? '' }}">
    @include('layouts.navbars.auth.sidenav')
    <main class="main-content border-radius-lg">
        @yield('content')
    </main>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Control Center for Soft Dashboard -->
    <script src="{{ asset('assets/js/argon-dashboard.js') }}"></script>

    <!-- Código para el logout -->
    <script>
        document.getElementById('logoutBtn').addEventListener('click', async function(e) {
            e.preventDefault();

            try {
                const response = await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    window.location.href = '/login';
                } else {
                    alert('No se pudo cerrar sesión.');
                }
            } catch (err) {
                console.error(err);
            }
        });
    </script>

    <!-- Registrar Service Worker -->
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register("{{ asset('serviceworker.js') }}")
            //.then(reg => console.log("Service Worker registrado", reg))
            .catch(err => console.log("Error registrando Service Worker", err));
    }
</script>
    @stack('js');
</body>

</html>