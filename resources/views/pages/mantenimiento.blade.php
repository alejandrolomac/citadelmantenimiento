@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Mantenimiento'])
    <style>
        #tableMantenimiento {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 16px;
            text-align: left;
        }

        #tableMantenimiento th,
        #tableMantenimiento td {
            padding: 12px 15px;
        }

        #tableMantenimiento thead tr {
            background-color: #ffffff;
            color: #000000;
            text-align: left;
            font-weight: bold;
        }

        #tableMantenimiento tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        #tableMantenimiento tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        #tableMantenimiento tbody tr:last-of-type {
            border-bottom: 2px solid #000000;
        }

        #exportModal .modal-header,
        #exportModal .modal-footer {
            background-color: #40484f;
            /* Un tono oscuro para un buen contraste */
            color: #ffffff;
            /* Texto blanco para mejor legibilidad */
        }

        #exportModal .modal-body {
            display: flex;
            flex-direction: row;
            padding: 0;
        }

        #exportModal .modal-body button {
            flex: 1;
            margin: 0;
            height: 100px;
        }

        #exportModal .modal-footer {
            justify-content: center;
            padding: 1px;
        }

        #exportModal .modal-footer button {
            width: auto;
            background-color: #40484f;
            color: #000000;
        }

        #imprimir {
            background-color: #ffc107;
            color: #1f2d3d;
        }

        #imprimir2 {
            background-color: #ffc107;
            color: #1f2d3d;
        }

        .btn-export {
            color: #ffffff;
            background-color: #343a40;
            border-color: #343a40;
        }

        .btn-export:hover {
            color: #ffffff;
            background-color: #373e44;
            border-color: #373e44;
        }

        .btn-view {
            color: #ffffff;
            background-color: #18B3E9;
            border-color: #18B3E9;
        }

        .btn-view:hover {
            color: #ffffff;
            background-color: #18B3E3;
            border-color: #18B3E3;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>



    <!-- Modal para exportar mantenimientos -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: #ffff" id="modalTitle">Exportar Orden de Trabajo</h5>
                    <button type="button" class="btn-close bg-" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-warning p-2 border-0 rounded-0" id="imprimir" alt="Imprimir">
                        <i class="fa-solid fa-print"></i> Imprimir
                    </button>
                    <button class="btn p-2 border-0 rounded-0" id="pdf" alt="PDF"
                        style="background: #A52A2A; color:#ffffff;">
                        <i class="fa-solid fa-file-pdf"></i> PDF
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver mantenimientos -->
    <div class="modal fade" id="showOrdenModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">

                    <div class="container-fluid py-4">
                        <div class="row mt-0">
                            <div class="col-lg-12 mb-lg-0 mb-1">
                                <div class="card-body z-index-2 h-100 text-center">
                                    <div class="pb-0">
                                        <h3 class="font-weight-bolder" style="color: #343a40" id="ordenTitulo">Orden de
                                            Trabajo: </h3>
                                        <!--p class="mb-0">Enter your email and password to sign in</p-->
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <!-- Tabla de Información General -->
                            <div class="col-lg-12 mb-lg-0">
                                <div class="card z-index-2 h-100">
                                    <div class="card-header d-flex justify-content-center align-items-center"
                                        style="background-color: #E1DDC1; color: #000000; padding: 0.5rem;">
                                        Información General
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="table-responsive p-0">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Técnico</th>
                                                    <td id="tecnico"></td>
                                                </tr>
                                                <tr>
                                                    <th>Hora de Inicio</th>
                                                    <td id="horaInicio"></td>
                                                </tr>
                                                <tr>
                                                    <th>Hora de Finalización</th>
                                                    <td id="horaFinalizacion"></td>
                                                </tr>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <td id="fecha"></td>
                                                </tr>
                                                <tr>
                                                    <th>Unidad</th>
                                                    <td id="tipo"></td>
                                                </tr>
                                                <tr>
                                                    <th>Ubicación</th>
                                                    <td id="ubicacion"></td>
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
                                    <div class="card-header d-flex justify-content-center align-items-center"
                                        style="background-color: #E1DDC1; color: #000000; padding: 0.5rem;">
                                        Información de la Unidad
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="table-responsive p-0">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Código de Unidad</th>
                                                    <td id="codigo"></td>
                                                </tr>
                                                <tr>
                                                    <th>Folio</th>
                                                    <td id="folio"></td>
                                                </tr>
                                                <tr>
                                                    <th>VIN</th>
                                                    <td id="vin"></td>
                                                </tr>
                                                <tr>
                                                    <th>Kilometraje</th>
                                                    <td id="kilometraje"></td>
                                                </tr>
                                                <tr>
                                                    <th>Conductor</th>
                                                    <td id="conductor"></td>
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
                                    <div class="card-header d-flex justify-content-center align-items-center"
                                        style="background-color: #E1DDC1; color: #000000; padding: 0.5rem;">
                                        Trabajos Realizados
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="table-responsive p-0">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Trabajo</th>
                                                        <th>Descripción</th>
                                                        <th>Imágenes</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="trabajosRealizados">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>



                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="imprimirModal">Imprimir</button>
                    <button type="button" class="btn" id="pdfModal"
                        style="background: #A52A2A; color:#ffffff;">PDF</button>
                    <button type="button" class="btn bg-gradient-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Mantenimiento</h6>
                    <a href="/orden" class="btn bg-gradient-success"><i class="fa-solid fa-plus"></i> Nueva Orden</a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table id="tableMantenimiento" class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>#</th>
                                    <th>Unidad</th>
                                    <th>No. Orden</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
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


    <!-- Lightbox Modal -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
        <div class="modal-body p-0">
            <img src="" id="lightboxImage" class="img-fluid rounded" alt="Vista ampliada">
        </div>
        </div>
    </div>
    </div>


    <script>
        $(document).ready(function() {
            let table = $('#tableMantenimiento').DataTable({
                responsive: true,
                processing: true,
                serverSide: false,
                ajax: '{{ url('ordenes/data') }}',
                columns: [{
                        data: 'id_orden_trabajo',
                        visible: false // Oculta la columna de ID
                    },
                    {
                        data: null, // Índice automático
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Índice secuencial
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.nombre;
                        }
                    },
                    {
                        data: 'no_orden'
                    },
                    {
                        data: 'fecha'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                    <a class="btn btn-view" href="/orden/${row.id_orden_trabajo}/detalle" alt="Ver Orden"><i class="fa-regular fa-eye"></i></a>
                        <button class="btn btn-export" onclick="showExportModal(${row.id_orden_trabajo})" alt="Exportar"><i class="fa-solid fa-file-export"></i></button>
                        <a href="/orden/${row.id_orden_trabajo}/editar" class="btn btn-warning" alt="Editar">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <button class="btn btn-danger" onclick="deleteOrden(${row.id_orden_trabajo})" alt="Eliminar"><i class="fa-regular fa-trash-can"></i></button>
                    `;
                        }
                    }
                ],
                language: {
                    "url": "assets/js/plugins/es-ES.json"
                }
            });
        });


        function deleteOrden(id) {
            Swal.fire({
                title: '¿Deseas eliminar esta orden?',
                text: 'Esta acción eliminará la orden seleccionada.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            }).then((firstResult) => {
                if (firstResult.isConfirmed) {
                    Swal.fire({
                        title: '¿Está completamente seguro?',
                        text: 'Una vez eliminada, no podrá recuperar esta orden.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e3342f',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar definitivamente',
                        cancelButtonText: 'Cancelar'
                    }).then((finalResult) => {
                        if (finalResult.isConfirmed) {
                            fetch(`/orden/delete/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Swal.fire('¡Eliminado!', data.message, 'success');
                                    $('#tableMantenimiento').DataTable().ajax.reload();
                                })
                                .catch(error => {
                                    Swal.fire('Error', 'Ocurrió un error al eliminar la orden.',
                                        'error');
                                    console.error('Error:', error);
                                });
                        }
                    });
                }
            });
        }


        function showExportModal(id_orden_trabajo) {
            $('#exportModal').modal('show');
            document.querySelector('.modal-header').classList.replace("bg-warning", "bg-primary");
            $('#modalTitle').text('Exportar Orden de Trabajo');
            $('#actionButton').text('Exportar').removeClass('bg-gradient-warning').addClass('bg-gradient-primary');

            // Obtén los botones de imprimir y PDF
            const btnImprimir = document.getElementById('imprimir');
            const btnPDF = document.getElementById('pdf');

            // Remueve cualquier evento previo para evitar duplicaciones
            btnImprimir.replaceWith(btnImprimir.cloneNode(true));
            btnPDF.replaceWith(btnPDF.cloneNode(true));

            // Asigna nuevamente los botones clonados
            const newBtnImprimir = document.getElementById('imprimir');
            const newBtnPDF = document.getElementById('pdf');

            // Agrega el id_orden_trabajo como atributo data a los botones
            newBtnImprimir.dataset.id = id_orden_trabajo;
            newBtnPDF.dataset.id = id_orden_trabajo;

            // Agrega event listeners a los botones
            newBtnImprimir.addEventListener('click', mantImprimir);
            newBtnPDF.addEventListener('click', mantPDF);
        }

        function mantPDF(event) {
            event.preventDefault(); // Evita que la página se recargue
            const id_orden_trabajo = event.target.dataset.id;
            let link = document.createElement("a");
            link.href = `/orden/${id_orden_trabajo}/exportar-pdf`;
            link.download = "orden_trabajo.pdf";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function mantImprimir(event) {
            event.preventDefault(); // Evita la recarga de la página
            const id_orden_trabajo = event.target.dataset.id;
            fetch(`/orden/${id_orden_trabajo}/exportar-pdf?view=true`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error al generar el PDF");
                    }
                    return response.blob();
                })
                .then(blob => {
                    var fileURL = URL.createObjectURL(blob);
                    var printWindow = window.open(fileURL, '_blank');
                    if (printWindow) {
                        printWindow.onload = function() {
                            printWindow.print();
                        };
                    } else {
                        alert("No se pudo abrir la ventana de impresión. Habilita los pop-ups.");
                    }
                })
                .catch(error => console.error('Error al generar el PDF:', error));
        }

        function showOrden(idOrden) {
  // 1) Mostrar modal principal
  const showModalEl = document.getElementById('showOrdenModal');
  if (!showModalEl) {
    console.error('No existe #showOrdenModal');
    return;
  }
  const showModal = new bootstrap.Modal(showModalEl);
  showModal.show();

  // 2) Ajustar header y botón de acción
  const headerEl = showModalEl.querySelector('.modal-header');
  if (headerEl) {
    headerEl.classList.replace('bg-warning', 'bg-primary');
  }
  const titleEl = document.getElementById('modalTitle2');
  if (titleEl) titleEl.textContent = 'Ver Orden de Trabajo';

  const actionBtn = document.getElementById('actionButton');
  if (actionBtn) {
    actionBtn.textContent = 'Exportar';
    actionBtn.classList.remove('bg-gradient-warning');
    actionBtn.classList.add('bg-gradient-primary');
  }

  // 3) Reemplazar listeners de Imprimir y PDF
  ['imprimirModal', 'pdfModal'].forEach(btnId => {
    const oldBtn = document.getElementById(btnId);
    if (!oldBtn) return;
    const newBtn = oldBtn.cloneNode(true);
    oldBtn.parentNode.replaceChild(newBtn, oldBtn);
    newBtn.dataset.id = idOrden;
    newBtn.addEventListener('click', btnId === 'imprimirModal' ? mantImprimir : mantPDF);
  });

  // 4) Petición para datos de la orden
  fetch(`/orden/${idOrden}/detalle-modal`)
    .then(res => {
      if (!res.ok) throw new Error('Error en la respuesta');
      return res.json();
    })
    .then(data => {
      // 4.1) Rellenar datos generales
      document.getElementById('ordenTitulo').innerHTML =
        `Orden de Trabajo: ${data.ordenTrabajo.no_orden || '—'}`;
      document.getElementById('tecnico').textContent =
        data.ordenTrabajo.tecnico || '—';
      document.getElementById('horaInicio').textContent =
        data.ordenTrabajo.hora_inicio || '—';
      document.getElementById('horaFinalizacion').textContent =
        data.ordenTrabajo.hora_final || '—';
      document.getElementById('fecha').textContent =
        data.ordenTrabajo.fecha || '—';
      document.getElementById('tipo').textContent =
        data.unidad.type || '—';
      document.getElementById('ubicacion').textContent =
        data.unidad.ubicacion_unidad || '—';
      document.getElementById('codigo').textContent =
        data.unidad.nombre || '—';
      document.getElementById('folio').textContent =
        data.unidad.tb_id || '—';
      document.getElementById('vin').textContent =
        data.unidad.vin || '—';
      document.getElementById('kilometraje').textContent =
        data.ordenTrabajo.kilometraje || '—';
      document.getElementById('conductor').textContent =
        data.ordenTrabajo.conductor || '—';

      // 4.2) Rellenar tabla de Trabajos Realizados
      const tbody = document.getElementById('trabajosRealizados');
      if (!tbody) {
        console.error('No existe <tbody id="trabajosRealizados">');
        return;
      }
      tbody.innerHTML = '';

      if (Array.isArray(data.trabajosRealizados) && data.trabajosRealizados.length) {
        data.trabajosRealizados.forEach(trabajo => {
          const tr = document.createElement('tr');

          // Columna: Trabajo
          const tdName = document.createElement('td');
          tdName.textContent = trabajo.nombre;
          tr.appendChild(tdName);

          // Columna: Descripción
          const tdDesc = document.createElement('td');
          tdDesc.textContent = trabajo.descripcion || '—';
          tr.appendChild(tdDesc);

          // Columna: Imágenes
          const tdImgs = document.createElement('td');
          if (Array.isArray(trabajo.imagenes) && trabajo.imagenes.length) {
            trabajo.imagenes.forEach(url => {
              // Crear etiqueta <a> para Fancybox
              const a = document.createElement('a');
              a.href = url;
              a.setAttribute('data-fancybox', 'galeriaTrabajos');
              a.setAttribute('data-caption', trabajo.nombre);

              // Crear miniatura
              const img = document.createElement('img');
              img.src = url;
              img.alt = trabajo.nombre;
              img.className = 'img-thumbnail';
              img.style.width = '100px';
              img.style.marginRight = '5px';
              img.style.cursor = 'pointer';

              a.appendChild(img);
              tdImgs.appendChild(a);
            });
          } else {
            tdImgs.textContent = 'Sin imágenes';
          }
          tr.appendChild(tdImgs);

          tbody.appendChild(tr);
        });

        // 4.3) Inicializar Fancybox para la galería dinámica
        Fancybox.bind('[data-fancybox="galeriaTrabajos"]', {
          groupAttr: false
        });

      } else {
        // Mensaje si no hay trabajos
        const trEmpty = document.createElement('tr');
        const tdEmpty = document.createElement('td');
        tdEmpty.colSpan = 3;
        tdEmpty.className = 'text-center';
        tdEmpty.textContent = 'No hay trabajos realizados';
        trEmpty.appendChild(tdEmpty);
        tbody.appendChild(trEmpty);
      }
    })
    .catch(err => {
      console.error(err);
      alert('Error al obtener los datos para el modal.');
    });
}



    </script>
@endsection
