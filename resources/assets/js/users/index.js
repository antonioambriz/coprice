const ROLE_BADGES = {
  SUPERADMIN:  '<span class="badge bg-label-danger">SUPERADMIN</span>',
  FACTURACION: '<span class="badge bg-label-warning">FACTURACION</span>',
  AMBIENTAL:   '<span class="badge bg-label-success">AMBIENTAL</span>',
  CONSULTA:    '<span class="badge bg-label-secondary">CONSULTA</span>',
};

$(function () {
  const table = $('.dt-column-search').DataTable({
    ajax: { url: baseUrl + 'users/get-data', dataSrc: 'data' },
    columns: [
      { data: 'id' },
      { data: 'name' },
      { data: 'email' },
      {
        data: 'role',
        className: 'text-center',
        render: (val) => ROLE_BADGES[val] ?? `<span class="badge bg-label-secondary">${val}</span>`,
      },
      {
        data: 'id',
        orderable: false,
        searchable: false,
        className: 'text-center',
        render: (id) =>
          `<a href="${baseUrl}users/${id}/edit" class="btn btn-sm btn-icon btn-text-secondary" title="Editar">
             <i class="ti tabler-edit"></i>
           </a>
           <button class="btn btn-sm btn-icon btn-text-danger btn-delete" data-id="${id}" title="Eliminar">
             <i class="ti tabler-trash"></i>
           </button>`,
      },
    ],
    order: [[1, 'asc']],
    language: {
      search: 'Buscar:',
      lengthMenu: 'Mostrar _MENU_',
      info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
      paginate: { previous: 'Anterior', next: 'Siguiente' },
      zeroRecords: 'No se encontraron registros',
    },
    initComplete: function () {
      this.api().columns().every(function (i) {
        if (i === 3 || i === 4) return;
        const input = $(`<input type="text" class="form-control form-control-sm" placeholder="${this.header().textContent.trim()}">`);
        $(this.footer()).empty().append(input);
        input.on('input', () => { this.search(input.val()).draw(); });
      });
    },
  });

  $(document).on('click', '.btn-delete', function () {
    const id = $(this).data('id');
    Swal.fire({
      title: '¿Eliminar usuario?',
      text: 'Esta acción no se puede deshacer.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-outline-secondary' },
      buttonsStyling: false,
    }).then((result) => {
      if (!result.isConfirmed) return;
      fetch(`${baseUrl}users/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      })
        .then((r) => r.json())
        .then((res) => {
          if (res.success) {
            table.ajax.reload();
            showToast(res.message);
          }
        });
    });
  });

  function showToast(msg) {
    $('#globalToastBody').text(msg);
    const toast = new bootstrap.Toast(document.getElementById('globalToast'));
    toast.show();
  }
});
