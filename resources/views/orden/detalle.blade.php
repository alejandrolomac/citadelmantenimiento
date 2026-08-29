<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/img/faviconc.png">
    <title>
        {{ $ordenTrabajo->no_orden }}
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
    
    <script src="
    https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js
    "></script>

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    


    <!-- CSS Files -->
    <link id="pagestyle" href="/assets/css/argon-dashboard.css" rel="stylesheet" />


    <style>
        .table-fixed {
            table-layout: fixed;
            width: 100%;
        } 
        .table-fixed th, 
        .table-fixed td {
            white-space: normal;
            word-break: break-word;
        }
    </style>
</head>

<body class="{{ $class ?? '' }}">
    <main class="main-content border-radius-lg">
        <div class="container-fluid py-4">
            <!-- Navbar -->
        <div class="row mt-0">
            <div class="col-lg-12 mb-lg-0 mb-1">
            <div class="card z-index-2 h-100 text-center" style="color: #ffffff; background-color: #343a40; border-color: #343a40;">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center">
                        <h5 class="font-weight-bolder text-white">Orden de Trabajo: {{ $ordenTrabajo->no_orden }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
            <!-- End Navbar -->

            <div class="row mt-4">
<!-- Tabla de Información General -->
                <div class="col-lg-12 mb-lg-0 mb-4">
                    <div class="card z-index-2 h-100">
                        <div class="card-header d-flex justify-content-center align-items-center" style="background-color: #E1DDC1; color: #000000; padding: 0.5rem;">
                            Información General
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive p-0">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Técnico Encargado</th>
                                    <td>{{ $ordenTrabajo->tecnico }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha</th>
                                    <td>{{ $ordenTrabajo->fecha }}</td>
                                </tr>
                                <tr>
                                    <th>Unidad</th>
                                    <td>{{ $unidad->type }}</td>
                                </tr>
                            </table>

                        </div>
                        </div>
                    </div>
                </div>
            </div>

                <div class="row mt-4">
<!-- Tabla de Información de la Unidad -->
                <div class="col-lg-12 mb-lg-0 mb-4">
                    <div class="card z-index-2 h-100">
                        <div class="card-header d-flex justify-content-center align-items-center" style="background-color: #E1DDC1; color: #000000; padding: 0.5rem;">
                            Información de la Unidad
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive p-0">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nombre de Máquina/Unidad</th>
                                    <td>{{ $unidad->nombre }}</td>
                                </tr>
                                <tr>
                                    <th>TB ID</th>
                                    <td>{{ $unidad->tb_id }}</td>
                                </tr>
                                <tr>
                                    <th>Técnico</th>
                                    <td>{{ $ordenTrabajo->conductor }}</td>
                                </tr>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

                <div class="row mt-4">
<!-- Tabla de Trabajos Realizados -->
                <div class="col-lg-12 mb-lg-0 mb-4">
                    <div class="card z-index-2 h-100">
                        <div class="card-header d-flex justify-content-center align-items-center" style="background-color: #E1DDC1; color: #000000; padding: 0.5rem;">
                            Trabajos Realizados
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive p-3">
                                <p style="white-space: pre-line;">{{ $ordenTrabajo->detalles ?? 'Sin detalles.' }}</p>
                            </div>
                        </div>

                        
                        <a class="btn bg-gradient-light" href="/mantenimiento">Regresar</a>
                    </div>
                </div>

            </div>


            
            @include('layouts.footers.auth.footer')


    </div>
    </main>

    <!-- Lightbox Modal -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-labelledby="lightboxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
        <div class="modal-body p-0">
            <img src="" id="lightboxImage" class="img-fluid rounded" alt="Vista ampliada">
        </div>
        </div>
    </div>
    </div>



    <!--   Core JS Files   -->

    <script src="/assets/js/core/popper.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <script src="/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/smooth-scrollbar.min.js"></script>
    
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>



<script>
    $(document).ready(function() {
        // Inicializar Fancybox
        Fancybox.bind('[data-fancybox="single"]', {
  groupAttr: false,
});
    });
    </script>



    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="/assets/js/argon-dashboard.js"></script>

    <script>
  $(function(){
    // Cambia el cursor para indicar que es clickeable
    $('.img-thumbnail').css('cursor','pointer');
    
    // Al hacer click en cualquier miniatura:
    $('.img-thumbnail').on('click', function(){
      // Tomamos su src
      var src = $(this).attr('src');
      // Lo ponemos en el <img> del modal
      $('#lightboxImage').attr('src', src);
      // Y abrimos el modal
      $('#lightboxModal').modal('show');
    });
  });
</script>

    
    @stack('js');
</body>

</html>