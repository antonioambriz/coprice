'use strict';

let dt_withdrawals;

$(document).ready(function () {
  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

  window.showToast = function (message) {
    const el   = document.getElementById('globalToast');
    const body = document.getElementById('globalToastBody');
    if (!el) return;
    body.textContent = message;
    new bootstrap.Toast(el, { delay: 3500 }).show();
  };

  $(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    const url   = $(this).data('url');
    const folio = $(this).data('folio');

    Swal.fire({
      title: '¿Eliminar entrada?',
      text: `Se eliminará el folio: ${folio}`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      customClass: {
        confirmButton: 'btn btn-danger me-3',
        cancelButton: 'btn btn-label-secondary',
      },
      buttonsStyling: false,
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          url,
          type: 'POST',
          data: { _method: 'DELETE' },
          success: function () {
            if (dt_withdrawals) dt_withdrawals.ajax.reload(null, false);
            showToast('Entrada eliminada correctamente.');
          },
        });
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
  const table     = document.querySelector('.dt-column-search');
  const createUrl = baseUrl + 'withdrawals/create';

  if (!table) return;

  dt_withdrawals = new DataTable(table, {
    ajax: baseUrl + 'withdrawals/get-data',
    order: [[1, 'desc']],
    pageLength: 25,
    processing: true,
    serverSide: true,
    columns: [
      { data: 'id', width: '2%' },
      { data: 'fecha', width: '5%' },
      { data: 'folio_interno', width: '5%' },
      { data: 'generator_name' },
      { data: 'transporter_name' },
      { data: 'manifest', width: '5%' },
      { data: 'status', width: '5%' },
      { data: 'user_name', width: '2%' },
      { data: null, orderable: false, searchable: false, width: '5%' },
    ],
    columnDefs: [
      {
        targets: 0,
        render: function (data, _type, full) {
          const cls = full.activo ? 'border-status-active' : 'border-status-inactive';
          return `<div class="d-flex align-items-center ps-2 ${cls}"><span class="text-muted fw-bold">#${data}</span></div>`;
        },
      },
      {
        targets: 1,
        render: function (data) {
          if (!data) return '---';
          const d = new Date(data);
          return `<div class="d-flex flex-column">
            <span class="text-nowrap">${d.toLocaleDateString('es-MX')}</span>
          </div>`;
        },
      },
      {
        targets: 3, // Generador + División
        render: function (data, _type, full) {
          const sub = full.sub_generator_name && full.sub_generator_name !== '—'
            ? `<small class="text-muted d-block">${full.sub_generator_name}</small>`
            : '';
          return `<div>${data}${sub}</div>`;
        },
      },
      {
        targets: 6, // Estatus pago
        render: function (data) {
          const map = {
            PENDIENTE: 'bg-label-warning',
            PAGADO:    'bg-label-success',
            CANCELADO: 'bg-label-danger',
          };
          return `<span class="badge ${map[data] || 'bg-label-secondary'}">${data}</span>`;
        },
      },
      {
        targets: 7, // Usuario (iniciales)
        render: function (data) {
          if (!data || data === '—') return data;
          return (data.match(/\b\w/g) || []).slice(0, 2).join('').toUpperCase();
        },
      },
      {
        targets: -1,
        render: function (_data, _type, full) {
          const base = `${baseUrl}withdrawals/${full.id}`;
          return `
            <div class="d-flex align-items-center justify-content-center gap-1">
              <a href="${base}" class="btn btn-sm btn-icon btn-outline-secondary" title="Ver detalle">
                <span class="ti tabler-eye"></span>
              </a>
              <a href="${base}/edit" class="btn btn-sm btn-icon btn-outline-secondary" title="Editar">
                <span class="ti tabler-edit"></span>
              </a>
              <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete"
                data-url="${base}" data-folio="${full.folio_interno}">
                <span class="ti tabler-trash"></span>
              </button>
            </div>`;
        },
      },
    ],
    layout: {
      topStart: {
        rowClass: 'row mx-3 my-0 justify-content-between pt-4',
        features: [{ pageLength: { menu: [10, 25, 50], text: 'Mostrar _MENU_' } }],
      },
      topEnd: {
        rowClass: 'row m-3 my-0 justify-content-between',
        features: [
          {
            buttons: [
              {
                text: '<i class="ti tabler-plus me-md-1"></i><span class="d-md-inline-block d-none">Nueva Entrada</span>',
                className: 'add-new btn btn-primary waves-effect waves-light',
                action: () => (window.location.href = createUrl),
              },
            ],
            search: { className: 'me-5 ms-n4 pe-5 mb-n6 mb-md-0', text: '_INPUT_', placeholder: 'Buscar:' },
          },
        ],
      },
      bottomStart: { rowClass: 'row mx-3 justify-content-between pb-4', features: ['info'] },
      bottomEnd:   { rowClass: 'row mx-3 justify-content-between pb-4', features: ['paging'] },
    },
  });

  // Filtros por columna
  const thead    = table.querySelector('thead');
  const cloneRow = thead.querySelector('tr').cloneNode(true);
  thead.appendChild(cloneRow);
  thead.querySelectorAll('tr:nth-child(2) th').forEach((th, i) => {
    if (i === 8) {
      th.innerHTML =
        '<div class="text-center"><button class="btn btn-sm btn-label-secondary btn-icon" id="btn-reset-filters"><i class="ti tabler-filter-off"></i></button></div>';
      th.querySelector('#btn-reset-filters').addEventListener('click', () => {
        thead.querySelectorAll('input').forEach(inp => (inp.value = ''));
        dt_withdrawals.columns().search('').draw();
      });
    } else {
      const input = document.createElement('input');
      input.className   = 'form-control form-control-sm';
      input.placeholder = th.textContent.trim();
      th.innerHTML = '';
      th.appendChild(input);
      input.addEventListener('keyup', function () {
        dt_withdrawals.column(i).search(this.value).draw();
      });
    }
  });

  setTimeout(() => {
    document.querySelectorAll('.dt-buttons .btn').forEach(el => el.classList.remove('btn-secondary'));
  }, 100);
});
