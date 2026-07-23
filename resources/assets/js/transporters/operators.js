'use strict';

$(function () {
  let dt;
  const modal       = new bootstrap.Modal(document.getElementById('modalOperator'));
  const $title      = $('#modalOperatorTitle');
  const $id         = $('#operatorId');
  const $name       = $('#operatorName');
  const $license    = $('#operatorLicenseNumber');
  const $phone      = $('#operatorPhone');
  const $expiry     = $('#operatorLicenseExpiry');
  const $activo     = $('#operatorActivo');
  const $errName    = $('#errOperatorName');
  const toast       = new bootstrap.Toast(document.getElementById('globalToast'));

  function showToast(msg) {
    $('#globalToastBody').text(msg);
    toast.show();
  }

  // ─── DataTable ────────────────────────────────────────────────
  dt = $('#operatorsTable').DataTable({
    ajax: { url: operatorsGetDataUrl, dataSrc: 'data' },
    columns: [
      { data: 'id', width: '60px' },
      { data: 'name' },
      { data: 'license_number' },
      { data: 'phone' },
      { data: 'license_expiry' },
      {
        data: 'activo', className: 'text-center',
        render: (data) => data
          ? '<span class="badge bg-label-success">Activo</span>'
          : '<span class="badge bg-label-secondary">Inactivo</span>'
      },
      {
        data: null, className: 'text-center', orderable: false,
        render: (data, type, full) => `
          <div class="d-flex justify-content-center gap-1">
            <button class="btn btn-sm btn-icon btn-outline-secondary btn-edit-operator"
              data-id="${full.id}" data-name="${full.name}"
              data-license="${full.license_number === '—' ? '' : full.license_number}"
              data-phone="${full.phone === '—' ? '' : full.phone}"
              data-expiry="${full.license_expiry === '—' ? '' : full.license_expiry}"
              data-activo="${full.activo}" title="Editar">
              <i class="ti tabler-edit"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-outline-danger btn-delete-operator"
              data-id="${full.id}" data-name="${full.name}" title="Eliminar">
              <i class="ti tabler-trash"></i>
            </button>
          </div>`
      }
    ],
    responsive: true,
  });

  // ─── Abrir modal para crear ───────────────────────────────────
  $('[data-bs-target="#modalOperator"]').on('click', function () {
    $title.text('Nuevo Operador');
    $id.val('');
    $name.val('').removeClass('is-invalid');
    $license.val('');
    $phone.val('');
    $expiry.val('');
    $activo.prop('checked', true);
  });

  // ─── Abrir modal para editar ──────────────────────────────────
  $(document).on('click', '.btn-edit-operator', function () {
    $title.text('Editar Operador');
    $id.val($(this).data('id'));
    $name.val($(this).data('name')).removeClass('is-invalid');
    $license.val($(this).data('license'));
    $phone.val($(this).data('phone'));
    $expiry.val($(this).data('expiry'));
    $activo.prop('checked', $(this).data('activo') == true || $(this).data('activo') == 1);
    modal.show();
  });

  // ─── Guardar (crear o editar) ─────────────────────────────────
  $('#btnSaveOperator').on('click', function () {
    const id    = $id.val();
    const isNew = !id;
    const url   = isNew
      ? operatorsStoreUrl
      : `${baseUrl}transporters/${transporterId}/operators/${id}`;

    $name.removeClass('is-invalid');
    $errName.text('');

    $.ajax({
      url,
      type: 'POST',
      data: {
        _token:          csrfToken,
        _method:         isNew ? 'POST' : 'PUT',
        name:            $name.val(),
        license_number:  $license.val(),
        phone:           $phone.val(),
        license_expiry:  $expiry.val(),
        activo:          $activo.is(':checked') ? 1 : 0,
      },
      success: function () {
        modal.hide();
        dt.ajax.reload(null, false);
        showToast(isNew ? 'Operador registrado.' : 'Operador actualizado.');
      },
      error: function (xhr) {
        const errors = xhr.responseJSON?.errors;
        if (errors?.name) {
          $name.addClass('is-invalid');
          $errName.text(errors.name[0]);
        } else {
          Swal.fire('Error', xhr.responseJSON?.message || 'Error al guardar.', 'error');
        }
      }
    });
  });

  // ─── Eliminar ─────────────────────────────────────────────────
  $(document).on('click', '.btn-delete-operator', function () {
    const id   = $(this).data('id');
    const name = $(this).data('name');

    Swal.fire({
      title: '¿Eliminar operador?',
      text: name,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-label-secondary' },
      buttonsStyling: false,
    }).then(result => {
      if (!result.isConfirmed) return;
      $.ajax({
        url:  `${baseUrl}transporters/${transporterId}/operators/${id}`,
        type: 'POST',
        data: { _token: csrfToken, _method: 'DELETE' },
        success: function () {
          dt.ajax.reload(null, false);
          showToast('Operador eliminado.');
        },
        error: function () {
          Swal.fire('Error', 'No se pudo eliminar el operador.', 'error');
        }
      });
    });
  });
});
