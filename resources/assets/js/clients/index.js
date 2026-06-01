'use strict';

let dt_filter;

$(document).ready(function () {
  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

  window.showToast = function (message, icon = 'success') {
    const toastElement = document.getElementById('globalToast');
    const toastBody   = document.getElementById('globalToastBody');
    const toastIcon   = toastElement?.querySelector('.toast-header i');
    if (!toastElement) return;
    toastBody.textContent = message;
    if (toastIcon) toastIcon.className = icon === 'error' ? 'ti tabler-ban text-danger me-2' : 'ti tabler-check text-success me-2';
    new bootstrap.Toast(toastElement, { delay: 3500 }).show();
  };

  $(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    const deleteUrl = $(this).data('url');
    const name      = $(this).data('name');

    Swal.fire({
      title: '¿Eliminar Cliente?',
      text: `Estás a punto de eliminar: '${name}'.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-label-secondary' },
      buttonsStyling: false
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          url: deleteUrl, type: 'POST', data: { _method: 'DELETE' },
          success: function () {
            dt_filter?.ajax.reload(null, false);
            showToast('Cliente eliminado correctamente.');
          },
          error: function (xhr) {
            Swal.fire({ title: '¡Error!', text: xhr.responseJSON?.message || 'Error al eliminar.', icon: 'error' });
          }
        });
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
  const table = document.querySelector('.dt-column-search');
  if (!table) return;

  dt_filter = new DataTable(table, {
    ajax: baseUrl + 'clients/get-data',
    order: [[1, 'asc']],
    pageLength: 25,
    processing: true,
    columns: [
      { data: 'id',           width: '3%',  className: 'text-center' },
      { data: 'company_name', width: '30%' },
      { data: 'rfc',          width: '12%' },
      { data: 'contact_person', width: '18%' },
      { data: 'email',        width: '18%' },
      { data: 'activo',       width: '8%',  className: 'text-center' },
      { data: null,           width: '11%', className: 'text-center', orderable: false, searchable: false },
    ],
    columnDefs: [
      {
        targets: 0,
        render: function (data, type, full) {
          const cls = full.activo ? 'border-status-active' : 'border-status-inactive';
          return `<div class="d-flex align-items-center ps-2 ${cls}"><span class="text-muted fw-bold" style="font-size:0.8rem;">#${data}</span></div>`;
        }
      },
      {
        targets: 1,
        render: data => `<span class="text-body fw-medium">${data}</span>`
      },
      {
        targets: 5,
        render: function (data) {
          return data
            ? '<span class="badge bg-label-success">ACTIVO</span>'
            : '<span class="badge bg-label-secondary">INACTIVO</span>';
        }
      },
      {
        targets: -1,
        render: function (data, type, full) {
          const base = `${baseUrl}clients/${full.id}`;
          return `
            <div class="d-flex align-items-center justify-content-center">
              <a href="${base}/edit" class="btn btn-sm btn-icon btn-outline-secondary waves-effect me-1" data-bs-toggle="tooltip" title="Editar">
                <span class="icon-base ti tabler-edit icon-18px"></span>
              </a>
              <button type="button" class="btn btn-sm btn-icon btn-outline-danger waves-effect btn-delete"
                data-url="${base}" data-name="${full.company_name}" data-bs-toggle="tooltip" title="Eliminar">
                <span class="icon-base ti tabler-trash icon-18px"></span>
              </button>
            </div>`;
        }
      }
    ],
    drawCallback: function () {
      document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    },
    layout: {
      topStart: { rowClass: 'row mx-3 my-0 justify-content-between pt-4', features: [{ pageLength: { menu: [10, 25, 50], text: 'Mostrar _MENU_' } }] },
      topEnd:   { rowClass: 'row m-3 my-0 justify-content-between', features: [{ search: { className: 'me-5 ms-n4 pe-5 mb-n6 mb-md-0', text: '_INPUT_', placeholder: 'Buscar:' } }] },
      bottomStart: { rowClass: 'row mx-3 justify-content-between pb-4', features: ['info'] },
      bottomEnd:   { rowClass: 'row mx-3 justify-content-between pb-4', features: ['paging'] }
    }
  });

  const thead    = table.querySelector('thead');
  const cloneRow = thead.querySelector('tr').cloneNode(true);
  thead.appendChild(cloneRow);

  thead.querySelectorAll('tr:nth-child(2) th').forEach((th, i) => {
    const lastCol = 6;
    if (i === lastCol) {
      th.innerHTML = `<div class="text-center"><button class="btn btn-sm btn-label-secondary btn-icon" id="btn-reset-filters" title="Limpiar filtros"><i class="ti tabler-filter-off"></i></button></div>`;
      th.querySelector('#btn-reset-filters').addEventListener('click', () => {
        thead.querySelectorAll('tr:nth-child(2) input').forEach(inp => (inp.value = ''));
        dt_filter.columns().search('').draw();
      });
    } else {
      const input = document.createElement('input');
      input.className   = 'form-control form-control-sm';
      input.placeholder = th.textContent.trim();
      th.innerHTML = '';
      th.appendChild(input);
      input.addEventListener('keyup', () => dt_filter.column(i).search(input.value).draw());
    }
  });
});
