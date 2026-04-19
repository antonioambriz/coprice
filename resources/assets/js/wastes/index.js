'use strict';

/**
 * ARCHIVO: index.js
 * PROYECTO: Coprice
 * DESCRIPCIÓN: Gestión de DataTable para Catálogo de Residuos (Homologado con Sinapsis)
 */

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
    toastElement.classList.remove('animate__tada');
    void toastElement.offsetWidth;
    toastElement.classList.add('animate__tada');
    bsToast.show();
  };

  // --- 🔴 EVENTO: ELIMINAR RESIDUO ---
  $(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    const deleteUrl = $(this).data('url');
    const name = $(this).data('name');

    Swal.fire({
      title: '¿Estás seguro?',
      text: `Estás a punto de eliminar el residuo: '${name}'.`,
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
            showToast('El residuo ha sido eliminado exitosamente.');
          },
          error: function (xhr) {
            let message = xhr.responseJSON?.message || 'Error al eliminar el residuo.';
            Swal.fire({ title: '¡Error!', text: message, icon: 'error' });
          }
        });
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', function (e) {
  const dt_filter_table = document.querySelector('.dt-column-search'),
    wasteAdd = baseUrl + 'wastes/create';

  if (dt_filter_table) {
    dt_filter = new DataTable(dt_filter_table, {
      ajax: baseUrl + 'wastes/get-data',
      order: [[1, 'asc']], // Ordenar por Descripción
      pageLength: 25,
      processing: true,
      columns: [
        { data: 'id', width: '2%', className: 'text-center' },
        { data: 'description', width: '30%' },
        { data: 'waste_code', width: '15%' },
        { data: 'unit', width: '10%', className: 'text-center' },
        { data: 'default_price', width: '10%', className: 'text-center' },
        { data: 'is_hazardous', width: '10%', className: 'text-center' },
        { data: null, width: '10%', className: 'text-center', orderable: false, searchable: false }
      ],
      columnDefs: [
        // ID con borde lateral (Homologado)
        {
          targets: 0,
          render: function (data, type, full) {
            const borderClass = full['activo'] ? 'border-status-active' : 'border-status-inactive';
            return `
                <div class="d-flex align-items-center ps-2 ${borderClass}">
                    <span class="text-muted fw-bold" style="font-size: 0.8rem;">#${data}</span>
                </div>`;
          }
        },
        // Descripción en Negrita
        {
          targets: 1,
          render: function (data) {
            return `<span class="text-body fw-medium">${data}</span>`;
          }
        },
        // Badge para Peligroso
        {
          targets: 5,
          render: function (data) {
            const state = data ? 'bg-label-danger' : 'bg-label-success';
            const text = data ? 'PELIGROSO' : 'ESTÁNDAR';
            return `<span class="badge ${state}">${text}</span>`;
          }
        },
        // Acciones con Tooltips
        {
          targets: -1,
          render: function (data, type, full) {
            const $id = full['id'];
            const $name = full['description'];
            const $urlBase = `${baseUrl}wastes/${$id}`;

            return `
              <div class="d-flex align-items-center justify-content-center">
                <a href="${$urlBase}/edit" class="btn btn-sm btn-icon btn-outline-secondary waves-effect me-1" data-bs-toggle="tooltip" title="Editar">
                  <span class="icon-base ti tabler-edit icon-18px"></span>
                </a>
                <button type="button" class="btn btn-sm btn-icon btn-outline-danger waves-effect btn-delete" data-url="${$urlBase}" data-name="${$name}" data-bs-toggle="tooltip" title="Eliminar">
                  <span class="icon-base ti tabler-trash icon-18px"></span>
                </button>
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
          rowClass: 'row mx-3 my-0 justify-content-between pt-4',
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
              // --- 🔵 AQUÍ ESTÁ EL BOTÓN DE AÑADIR ---
              buttons: [
                {
                  text: '<i class="ti tabler-plus me-md-1"></i><span class="d-md-inline-block d-none">Añadir Residuo</span>',
                  className: 'add-new btn btn-primary waves-effect waves-light',
                  action: function (e, dt, node, config) {
                    window.location.href = wasteAdd;
                  }
                }
              ],
              search: { className: 'me-5 ms-n4 pe-5 mb-n6 mb-md-0', text: '_INPUT_', placeholder: 'Buscar:' }
            }
          ]
        },
        bottomStart: { rowClass: 'row mx-3 justify-content-between pb-4', features: ['info'] },
        bottomEnd: { rowClass: 'row mx-3 justify-content-between pb-4', features: ['paging'] }
      }
    });

    // --- 🔍 FILTROS POR COLUMNA (HEADER) ---
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
          thead.querySelectorAll('tr:nth-child(2) input').forEach(input => (input.value = ''));
          dt_filter.columns().search('').draw();
        });
      } else {
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control form-control-sm';
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

  // --- 🛠️ AJUSTES FINALES DE ESTILO ---
  setTimeout(() => {
    document.querySelectorAll('.dt-buttons .btn').forEach(el => el.classList.remove('btn-secondary'));
    document.querySelectorAll('.dt-layout-full').forEach(el => el.classList.add('table-responsive'));
  }, 100);
});
