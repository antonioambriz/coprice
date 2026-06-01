'use strict';

let dt_filter;

$(document).ready(function () {
  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  window.showToast = function (message, icon = 'success') {
    const toastElement = document.getElementById('globalToast');
    const toastBody   = document.getElementById('globalToastBody');
    const toastIcon   = toastElement?.querySelector('.toast-header i');
    if (!toastElement) return;
    toastBody.textContent = message;
    if (toastIcon) toastIcon.className = icon === 'error'
      ? 'ti tabler-ban text-danger me-2'
      : 'ti tabler-check text-success me-2';
    const bsToast = new bootstrap.Toast(toastElement, { delay: 3500 });
    toastElement.classList.remove('animate__tada');
    void toastElement.offsetWidth;
    toastElement.classList.add('animate__tada');
    bsToast.show();
  };

  $(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    const deleteUrl = $(this).data('url');
    const name      = $(this).data('name');

    Swal.fire({
      title: '¿Estás seguro?',
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
            if (dt_filter) dt_filter.ajax.reload(null, false);
            showToast('Destino final eliminado.');
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
  const dt_filter_table = document.querySelector('.dt-column-search');
  const addUrl          = baseUrl + 'final-destinations/create';

  if (dt_filter_table) {
    dt_filter = new DataTable(dt_filter_table, {
      ajax: baseUrl + 'final-destinations/get-data',
      order: [[1, 'asc']],
      pageLength: 25,
      processing: true,
      columns: [
        { data: 'id',                   width: '5%',  className: 'text-center' },
        { data: 'name',                 width: '40%' },
        { data: 'authorization_number', width: '30%' },
        { data: 'activo',               width: '10%', className: 'text-center', orderable: false },
        { data: null,                   width: '10%', className: 'text-center', orderable: false, searchable: false },
      ],
      columnDefs: [
        {
          targets: 0,
          render: function (data, _type, full) {
            const cls = full.activo ? 'border-status-active' : 'border-status-inactive';
            return `<div class="d-flex align-items-center ps-2 ${cls}"><span class="text-muted fw-bold" style="font-size:.8rem">#${data}</span></div>`;
          }
        },
        {
          targets: 1,
          render: data => `<span class="text-body fw-medium">${data}</span>`
        },
        {
          targets: 3,
          render: function (data) {
            return data
              ? '<span class="badge bg-label-success">Activo</span>'
              : '<span class="badge bg-label-secondary">Inactivo</span>';
          }
        },
        {
          targets: -1,
          render: function (_data, _type, full) {
            const url = `${baseUrl}final-destinations/${full.id}`;
            return `
              <div class="d-flex align-items-center justify-content-center">
                <a href="${url}/edit" class="btn btn-sm btn-icon btn-outline-secondary waves-effect me-1" title="Editar">
                  <span class="icon-base ti tabler-edit icon-18px"></span>
                </a>
                <button type="button" class="btn btn-sm btn-icon btn-outline-danger waves-effect btn-delete"
                  data-url="${url}" data-name="${full.name}" title="Eliminar">
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
        topStart: {
          rowClass: 'row mx-3 my-0 justify-content-between pt-4',
          features: [{ pageLength: { menu: [10, 25, 50], text: 'Mostrar _MENU_' } }]
        },
        topEnd: {
          rowClass: 'row m-3 my-0 justify-content-between',
          features: [{
            buttons: [{
              text: '<i class="ti tabler-plus me-md-1"></i><span class="d-md-inline-block d-none">Añadir Destino</span>',
              className: 'add-new btn btn-primary waves-effect waves-light',
              action: function () { window.location.href = addUrl; }
            }],
            search: { className: 'me-5 ms-n4 pe-5 mb-n6 mb-md-0', text: '_INPUT_', placeholder: 'Buscar:' }
          }]
        },
        bottomStart: { rowClass: 'row mx-3 justify-content-between pb-4', features: ['info'] },
        bottomEnd:   { rowClass: 'row mx-3 justify-content-between pb-4', features: ['paging'] }
      }
    });

    const thead      = document.querySelector('.dt-column-search thead');
    const cloneRow   = thead.querySelector('tr').cloneNode(true);
    thead.appendChild(cloneRow);
    const secondRow  = thead.querySelectorAll('tr:nth-child(2) th');

    secondRow.forEach((th, i) => {
      if (i === secondRow.length - 1) {
        th.innerHTML = `<div class="text-center"><button type="button" class="btn btn-sm btn-label-secondary btn-icon" id="btn-reset-filters" title="Limpiar"><i class="ti tabler-filter-off"></i></button></div>`;
        th.querySelector('#btn-reset-filters').addEventListener('click', function (e) {
          e.stopPropagation();
          thead.querySelectorAll('tr:nth-child(2) input').forEach(inp => (inp.value = ''));
          dt_filter.columns().search('').draw();
        });
      } else {
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control form-control-sm';
        input.placeholder = th.textContent;
        th.innerHTML = '';
        th.appendChild(input);
        input.addEventListener('click', e => e.stopPropagation());
        input.addEventListener('keyup', function (e) {
          e.stopPropagation();
          if (dt_filter.column(i).search() !== this.value) dt_filter.column(i).search(this.value).draw();
        });
      }
    });
  }

  setTimeout(() => {
    document.querySelectorAll('.dt-buttons .btn').forEach(el => el.classList.remove('btn-secondary'));
    document.querySelectorAll('.dt-layout-full').forEach(el => el.classList.add('table-responsive'));
  }, 100);
});
