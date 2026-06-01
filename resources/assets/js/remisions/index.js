'use strict';

let dt_remisions;

const STATUS_BADGES = {
  BORRADOR:  'bg-label-secondary',
  ENVIADA:   'bg-label-info',
  PAGADA:    'bg-label-success',
  CANCELADA: 'bg-label-danger',
};
const STATUS_LABELS = {
  BORRADOR:  'Borrador',
  ENVIADA:   'Enviada',
  PAGADA:    'Pagada',
  CANCELADA: 'Cancelada',
};

$(document).ready(function () {
  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

  window.showToast = function (message, icon = 'success') {
    const el   = document.getElementById('globalToast');
    const body = document.getElementById('globalToastBody');
    const ico  = el?.querySelector('.toast-header i');
    if (!el) return;
    body.textContent = message;
    if (ico) ico.className = icon === 'error'
      ? 'ti tabler-ban text-danger me-2'
      : 'ti tabler-check text-success me-2';
    const t = new bootstrap.Toast(el, { delay: 3500 });
    el.classList.remove('animate__tada');
    void el.offsetWidth;
    el.classList.add('animate__tada');
    t.show();
  };

  $(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    const url = $(this).data('url');
    const num = $(this).data('number');

    Swal.fire({
      title: '¿Eliminar remisión?',
      text: `Se eliminará: ${num}`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-label-secondary' },
      buttonsStyling: false
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          url, type: 'POST', data: { _method: 'DELETE' },
          success: function () {
            if (dt_remisions) dt_remisions.ajax.reload(null, false);
            showToast('Remisión eliminada correctamente.');
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
  const table  = document.querySelector('.dt-column-search');
  const addUrl = baseUrl + 'remisions/create';

  if (!table) return;

  dt_remisions = new DataTable(table, {
    ajax: baseUrl + 'remisions/get-data',
    order: [[1, 'asc']],
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
      { data: 'id',                width: '4%',  className: 'text-center' },
      { data: 'remision_number',   width: '16%' },
      { data: 'generator_name',    width: '22%' },
      { data: 'sub_generator',     width: '13%' },
      { data: 'emission_date',     width: '10%' },
      { data: 'withdrawals_count', width: '7%',  className: 'text-center' },
      { data: 'total',             width: '10%', className: 'text-end' },
      { data: 'status',            width: '9%',  className: 'text-center', orderable: false },
      { data: null,                width: '8%',  className: 'text-center', orderable: false, searchable: false },
    ],
    columnDefs: [
      {
        targets: 0,
        render: function (data, _t, full) {
          const cls = full.status === 'PAGADA' ? 'border-status-active' : 'border-status-inactive';
          return `<div class="d-flex align-items-center ps-2 ${cls}"><span class="text-muted fw-bold" style="font-size:.8rem">#${data}</span></div>`;
        }
      },
      {
        targets: 1,
        render: data => `<span class="fw-semibold text-primary">${data}</span>`
      },
      {
        targets: 5,
        render: data => data > 0
          ? `<span class="badge bg-label-primary">${data}</span>`
          : `<span class="badge bg-label-secondary">${data}</span>`
      },
      {
        targets: 6,
        render: data => `<span class="fw-semibold">$${data}</span>`
      },
      {
        targets: 7,
        render: function (data) {
          const cls   = STATUS_BADGES[data] ?? 'bg-label-secondary';
          const label = STATUS_LABELS[data] ?? data;
          return `<span class="badge ${cls}">${label}</span>`;
        }
      },
      {
        targets: -1,
        render: function (_d, _t, full) {
          const url = `${baseUrl}remisions/${full.id}`;
          return `
            <div class="d-flex align-items-center justify-content-center gap-1">
              <a href="${url}/edit" class="btn btn-sm btn-icon btn-outline-secondary" title="Editar">
                <span class="ti tabler-edit icon-18px"></span>
              </a>
              <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete"
                data-url="${url}" data-number="${full.remision_number}" title="Eliminar">
                <span class="ti tabler-trash icon-18px"></span>
              </button>
            </div>`;
        }
      }
    ],
    layout: {
      topStart: {
        rowClass: 'row mx-3 my-0 justify-content-between pt-4',
        features: [{ pageLength: { menu: [10, 25, 50], text: 'Mostrar _MENU_' } }]
      },
      topEnd: {
        rowClass: 'row m-3 my-0 justify-content-between',
        features: [{
          buttons: [{
            text: '<i class="ti tabler-plus me-md-1"></i><span class="d-md-inline-block d-none">Nueva Remisión</span>',
            className: 'add-new btn btn-primary waves-effect waves-light',
            action: () => (window.location.href = addUrl)
          }],
          search: { className: 'me-5 ms-n4 pe-5 mb-n6 mb-md-0', text: '_INPUT_', placeholder: 'Buscar:' }
        }]
      },
      bottomStart: { rowClass: 'row mx-3 justify-content-between pb-4', features: ['info'] },
      bottomEnd:   { rowClass: 'row mx-3 justify-content-between pb-4', features: ['paging'] }
    }
  });

  const thead    = table.querySelector('thead');
  const cloneRow = thead.querySelector('tr').cloneNode(true);
  thead.appendChild(cloneRow);
  thead.querySelectorAll('tr:nth-child(2) th').forEach((th, i) => {
    if (i === thead.querySelectorAll('tr:nth-child(2) th').length - 1) {
      th.innerHTML = `<div class="text-center"><button class="btn btn-sm btn-label-secondary btn-icon" id="btn-reset-filters" title="Limpiar"><i class="ti tabler-filter-off"></i></button></div>`;
      th.querySelector('#btn-reset-filters').addEventListener('click', function (e) {
        e.stopPropagation();
        thead.querySelectorAll('tr:nth-child(2) input').forEach(inp => (inp.value = ''));
        dt_remisions.columns().search('').draw();
      });
    } else {
      const input = document.createElement('input');
      input.type = 'text';
      input.className = 'form-control form-control-sm';
      input.placeholder = th.textContent.trim();
      th.innerHTML = '';
      th.appendChild(input);
      input.addEventListener('click', e => e.stopPropagation());
      input.addEventListener('keyup', function () {
        dt_remisions.column(i).search(this.value).draw();
      });
    }
  });

  setTimeout(() => {
    document.querySelectorAll('.dt-buttons .btn').forEach(el => el.classList.remove('btn-secondary'));
  }, 100);
});
