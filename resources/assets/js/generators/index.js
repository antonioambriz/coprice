'use strict';

/**
 * ARCHIVO: index.js
 * PROYECTO: Coprice
 * DESCRIPCIÓN: Gestión de DataTable para Catálogo de Generadores (Clientes)
 */

let dt_filter;

$(document).ready(function () {
  // Configuración global de AJAX para CSRF
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // --- 🟢 FUNCIÓN GLOBAL: TOASTS DE NOTIFICACIÓN ---
  window.showToast = function (message, icon = 'success') {
    const toastElement = document.getElementById('globalToast');
    const toastBody = document.getElementById('globalToastBody');
    const toastHeaderIcon = toastElement ? toastElement.querySelector('.toast-header i') : null;

    if (!toastElement) return;
    toastBody.textContent = message;

    if (icon === 'error') {
      if (toastHeaderIcon) toastHeaderIcon.className = 'ti tabler-ban text-danger me-2';
    } else {
      if (toastHeaderIcon) toastHeaderIcon.className = 'ti tabler-check text-success me-2';
    }

    const bsToast = new bootstrap.Toast(toastElement, { delay: 3500 });
    toastElement.classList.remove('animate__tada');
    void toastElement.offsetWidth;
    toastElement.classList.add('animate__tada');
    bsToast.show();
  };

  // --- 🔴 EVENTO: ELIMINAR GENERADOR ---
  $(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    const deleteUrl = $(this).data('url');
    const name = $(this).data('name');

    Swal.fire({
      title: '¿Estás seguro?',
      text: `Estás a punto de eliminar al generador: '${name}'.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, ¡Eliminar!',
      cancelButtonText: 'Cancelar',
      customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-label-secondary' },
      buttonsStyling: false
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          url: deleteUrl,
          type: 'POST',
          data: { _method: 'DELETE' },
          success: function () {
            if (dt_filter) dt_filter.ajax.reload(null, false);
            showToast('El generador ha sido eliminado exitosamente.');
          },
          error: function (xhr) {
            let message = xhr.responseJSON?.message || 'Error al eliminar el registro.';
            Swal.fire({ title: '¡Error!', text: message, icon: 'error' });
          }
        });
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', function (e) {
  const dt_filter_table = document.querySelector('.dt-column-search');

  if (dt_filter_table) {
    dt_filter = new DataTable(dt_filter_table, {
      ajax: '/generators', // Ruta del recurso en Laravel
      order: [[1, 'asc']], // Ordenar por nombre de empresa
      pageLength: 25,
      processing: true,
      language: {
        emptyTable:     'No hay datos disponibles',
        info:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
        infoEmpty:      'Sin registros para mostrar',
        infoFiltered:   '(filtrado)',
        lengthMenu:     'Mostrar _MENU_ registros',
        loadingRecords: 'Cargando...',
        processing:     'Procesando...',
        search:         'Buscar:',
        zeroRecords:    'No se encontraron registros',
        paginate:       { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
      },
      columns: [
        { data: 'id', width: '5%', className: 'text-center' },
        { data: 'company_name', width: '30%' },
        { data: 'rfc', width: '15%' },
        { data: 'address', width: '30%' },
        { data: 'status', width: '10%', className: 'text-center' },
        { data: null, width: '10%', className: 'text-center', orderable: false, searchable: false }
      ],
      columnDefs: [
        // ID con estilo
        {
          targets: 0,
          render: function (data) {
            return `<span class="text-muted fw-bold">#${data}</span>`;
          }
        },
        // Empresa / Razón Social
        {
          targets: 1,
          render: function (data) {
            return `<span class="text-body fw-medium">${data}</span>`;
          }
        },
        // Estatus (Badge Booleano)
        {
          targets: 4,
          render: function (data) {
            const badgeClass = data ? 'bg-label-success' : 'bg-label-danger';
            const label = data ? 'ACTIVO' : 'INACTIVO';
            return `<span class="badge ${badgeClass}">${label}</span>`;
          }
        },
        // Acciones adaptadas
        {
          targets: -1,
          render: function (data, type, full) {
            const $id = full['id'];
            const $name = full['company_name'];
            const $urlBase = `/generators/${$id}`;

            return `
              <div class="d-flex align-items-center justify-content-center">
                <a href="${$urlBase}/edit" class="btn btn-sm btn-icon btn-outline-secondary waves-effect me-1" data-bs-toggle="tooltip" title="Editar">
                  <span class="icon-base ti tabler-edit icon-18px"></span>
                </a>
                <div class="d-inline-block">
                  <button type="button" class="btn btn-sm btn-icon btn-outline-secondary waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <span class="icon-base ti tabler-dots-vertical icon-18px"></span>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end m-0">
                    <a href="javascript:;" class="dropdown-item text-danger btn-delete" data-url="${$urlBase}" data-name="${$name}">
                      <i class="ti tabler-trash me-2"></i>Borrar
                    </a>
                  </div>
                </div>
              </div>`;
          }
        }
      ],
      drawCallback: function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      },
      layout: {
        topStart: {
          rowClass: 'row mx-3 my-0 justify-content-between',
          features: [
            {
              pageLength: { menu: [10, 25, 50, 100], text: 'Mostrar _MENU_' }
            }
          ]
        },
        topEnd: {
          rowClass: 'row m-3 my-0 justify-content-between',
          features: [
            {
              buttons: [
                {
                  extend: 'collection',
                  className: 'btn btn-label-secondary dropdown-toggle',
                  text: 'Exportar',
                  buttons: [
                    {
                      extend: 'print',
                      text: 'Imprimir',
                      className: 'dropdown-item',
                      exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                      extend: 'excel',
                      text: 'Excel',
                      className: 'dropdown-item',
                      exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    { extend: 'pdf', text: 'Pdf', className: 'dropdown-item', exportOptions: { columns: [0, 1, 2, 3] } }
                  ]
                }
              ],
              search: { className: 'me-5 ms-n4 pe-5 mb-n6 mb-md-0', text: '_INPUT_', placeholder: 'Buscar:' }
            }
          ]
        },
        bottomStart: { rowClass: 'row mx-3 justify-content-between', features: ['info'] },
        bottomEnd: 'paging'
      }
    });

    // --- 🔍 FILTROS POR COLUMNA (HEADER CLONE) ---
    const thead = document.querySelector('.dt-column-search thead');
    const cloneRow = thead.querySelector('tr').cloneNode(true);
    thead.appendChild(cloneRow);

    const secondRowCells = thead.querySelectorAll('tr:nth-child(2) th');

    secondRowCells.forEach((th, i) => {
      const title = th.textContent;
      if (i === secondRowCells.length - 1) {
        th.innerHTML = `
          <div class="text-center">
            <button type="button" class="btn btn-sm btn-label-secondary btn-icon waves-effect" id="btn-reset-filters" title="Limpiar filtros">
              <i class="ti tabler-filter-off"></i>
            </button>
          </div>`;
        th.querySelector('#btn-reset-filters').addEventListener('click', function (e) {
          e.stopPropagation();
          const inputs = thead.querySelectorAll('tr:nth-child(2) input');
          inputs.forEach(input => (input.value = ''));
          dt_filter.columns().search('').draw();
        });
      } else {
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control';
        input.placeholder = title;
        th.innerHTML = '';
        th.appendChild(input);
        input.addEventListener('click', e => e.stopPropagation());
        input.addEventListener('keyup', function (e) {
          e.stopPropagation();
          if (dt_filter.column(i).search() !== this.value) {
            dt_filter.column(i).search(this.value).draw();
          }
        });
      }
    });
  }

  // Ajustes de estilo para botones de exportación
  setTimeout(() => {
    document.querySelectorAll('.dt-buttons .btn').forEach(el => el.classList.remove('btn-secondary'));
  }, 100);
});
