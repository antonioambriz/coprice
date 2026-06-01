/**
 * ARCHIVO: index.js
 * PROYECTO: Coprice
 * DESCRIPCIÓN: Gestión de DataTable para Catálogo de Transportistas
 */

'use strict';

let dt_filter;

$(document).ready(function () {
  // Configuración global de AJAX para el token CSRF
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
    bsToast.show();
  };

  // --- 🔴 EVENTO: ELIMINAR TRANSPORTISTA ---
  $(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    const deleteUrl = $(this).data('url');
    const name = $(this).data('name');

    Swal.fire({
      title: '¿Eliminar Transportista?',
      text: `Estás a punto de eliminar a: '${name}'. Esta acción no se puede deshacer.`,
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
            showToast('Transportista eliminado correctamente.');
          },
          error: function (xhr) {
            let message = xhr.responseJSON?.message || 'Error al eliminar.';
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
      ajax: baseUrl + 'transporters/get-data', // 👈 Ruta sincronizada
      order: [[1, 'asc']],
      pageLength: 25,
      processing: true,
      serverSide: true, // Recomendado para YajraDataTables
      columns: [
        { data: 'id',                   width: '3%',  className: 'text-center' },
        { data: 'company_name',         width: '28%' },
        { data: 'authorization_number', width: '15%' },
        { data: 'contact_person',       width: '18%' },
        { data: 'email_remissions',     width: '15%' },
        { data: 'status',               width: '9%',  className: 'text-center' },
        { data: 'action',               width: '12%', className: 'text-center', orderable: false, searchable: false }
      ],
      columnDefs: [
        {
          // Target 0: ID con Borde lateral según estatus
          targets: 0,
          render: function (data, type, full) {
            // Ajustado para tinyint (1 activo, 0 inactivo)
            const borderClass = full['status'] == 1 ? 'border-status-active' : 'border-status-inactive';
            return `<div class="d-flex align-items-center ps-2 ${borderClass}">
                      <span class="text-muted fw-bold" style="font-size: 0.8rem;">#${data}</span>
                    </div>`;
          }
        },
        {
          targets: 1,
          render: function (data) {
            return `<span class="text-body fw-medium">${data}</span>`;
          }
        },
        {
          // Target 5: Badge de Estatus (Lógica tinyint)
          targets: 5,
          render: function (data) {
            const state = data == 1 ? 'bg-label-success' : 'bg-label-secondary';
            const text = data == 1 ? 'ACTIVO' : 'INACTIVO';
            return `<span class="badge ${state}">${text}</span>`;
          }
        },
        {
          // Target -1: Botones de Acción
          targets: -1,
          render: function (data, type, full) {
            const $urlBase = `${baseUrl}transporters/${full['id']}`;
            return `
              <div class="d-flex align-items-center justify-content-center">
                <a href="${$urlBase}/edit" class="btn btn-sm btn-icon btn-outline-secondary waves-effect me-1" data-bs-toggle="tooltip" title="Editar">
                  <span class="icon-base ti tabler-edit icon-18px"></span>
                </a>
                <button type="button" class="btn btn-sm btn-icon btn-outline-danger waves-effect btn-delete" data-url="${$urlBase}" data-name="${full['company_name']}" data-bs-toggle="tooltip" title="Eliminar">
                  <span class="icon-base ti tabler-trash icon-18px"></span>
                </button>
              </div>`;
          }
        }
      ],
      drawCallback: function () {
        // Inicializar tooltips después de cada renderizado de la tabla
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      },
      layout: {
        topStart: {
          rowClass: 'row mx-3 my-0 justify-content-between pt-4',
          features: [{ pageLength: { menu: [10, 25, 50], text: 'Mostrar _MENU_' } }]
        },
        topEnd: {
          rowClass: 'row m-3 my-0 justify-content-between',
          features: [
            { search: { className: 'me-5 ms-n4 pe-5 mb-n6 mb-md-0', text: '_INPUT_', placeholder: 'Buscar:' } }
          ]
        },
        bottomStart: { rowClass: 'row mx-3 justify-content-between pb-4', features: ['info'] },
        bottomEnd: { rowClass: 'row mx-3 justify-content-between pb-4', features: ['paging'] }
      }
    });

    // --- 🔍 FILTROS POR COLUMNA (HEADER CLONADO) ---
    const thead = document.querySelector('.dt-column-search thead');
    const cloneRow = thead.querySelector('tr').cloneNode(true);
    thead.appendChild(cloneRow);

    thead.querySelectorAll('tr:nth-child(2) th').forEach((th, i) => {
      const title = th.textContent;
      if (i === 6) {
        // Columna de Acciones
        th.innerHTML = `<div class="text-center"><button type="button" class="btn btn-sm btn-label-secondary btn-icon" id="btn-reset-filters" title="Limpiar Filtros"><i class="ti tabler-filter-off"></i></button></div>`;
        th.querySelector('#btn-reset-filters').addEventListener('click', function () {
          thead.querySelectorAll('tr:nth-child(2) input').forEach(input => (input.value = ''));
          dt_filter.columns().search('').draw();
        });
      } else {
        const input = document.createElement('input');
        input.className = 'form-control form-control-sm';
        input.placeholder = `Buscar ${title}`;
        th.innerHTML = '';
        th.appendChild(input);
        input.addEventListener('keyup', function () {
          dt_filter.column(i).search(this.value).draw();
        });
      }
    });
  }
});
