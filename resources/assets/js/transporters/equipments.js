'use strict';

$(function () {
  let dt;
  const modal       = new bootstrap.Modal(document.getElementById('modalEquipment'));
  const $title      = $('#modalEquipmentTitle');
  const $id         = $('#equipmentId');
  const $desc       = $('#equipmentDescription');
  const $plate      = $('#equipmentPlate');
  const $activo     = $('#equipmentActivo');
  const $errDesc    = $('#errDescription');
  const toast       = new bootstrap.Toast(document.getElementById('globalToast'));

  function showToast(msg) {
    $('#globalToastBody').text(msg);
    toast.show();
  }

  // ─── DataTable ────────────────────────────────────────────────
  dt = $('#equipmentsTable').DataTable({
    ajax: { url: getDataUrl, dataSrc: 'data' },
    columns: [
      { data: 'id', width: '60px' },
      { data: 'description' },
      { data: 'plate_number' },
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
            <button class="btn btn-sm btn-icon btn-outline-secondary btn-edit-equipment"
              data-id="${full.id}" data-desc="${full.description}"
              data-plate="${full.plate_number === '—' ? '' : full.plate_number}"
              data-activo="${full.activo}" title="Editar">
              <i class="ti tabler-edit"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-outline-danger btn-delete-equipment"
              data-id="${full.id}" data-desc="${full.description}" title="Eliminar">
              <i class="ti tabler-trash"></i>
            </button>
          </div>`
      }
    ],
    responsive: true,
  });

  // ─── Abrir modal para crear ───────────────────────────────────
  $('[data-bs-target="#modalEquipment"]').on('click', function () {
    $title.text('Nuevo Equipo');
    $id.val('');
    $desc.val('').removeClass('is-invalid');
    $plate.val('');
    $activo.prop('checked', true);
  });

  // ─── Abrir modal para editar ──────────────────────────────────
  $(document).on('click', '.btn-edit-equipment', function () {
    $title.text('Editar Equipo');
    $id.val($(this).data('id'));
    $desc.val($(this).data('desc')).removeClass('is-invalid');
    $plate.val($(this).data('plate'));
    $activo.prop('checked', $(this).data('activo') == true || $(this).data('activo') == 1);
    modal.show();
  });

  // ─── Guardar (crear o editar) ─────────────────────────────────
  $('#btnSaveEquipment').on('click', function () {
    const id    = $id.val();
    const isNew = !id;
    const url   = isNew
      ? storeUrl
      : `${baseUrl}transporters/${transporterId}/equipments/${id}`;

    $desc.removeClass('is-invalid');
    $errDesc.text('');

    $.ajax({
      url,
      type: 'POST',
      data: {
        _token:       csrfToken,
        _method:      isNew ? 'POST' : 'PUT',
        description:  $desc.val(),
        plate_number: $plate.val(),
        activo:       $activo.is(':checked') ? 1 : 0,
      },
      success: function () {
        modal.hide();
        dt.ajax.reload(null, false);
        showToast(isNew ? 'Equipo registrado.' : 'Equipo actualizado.');
      },
      error: function (xhr) {
        const errors = xhr.responseJSON?.errors;
        if (errors?.description) {
          $desc.addClass('is-invalid');
          $errDesc.text(errors.description[0]);
        } else {
          Swal.fire('Error', xhr.responseJSON?.message || 'Error al guardar.', 'error');
        }
      }
    });
  });

  // ─── Eliminar ─────────────────────────────────────────────────
  $(document).on('click', '.btn-delete-equipment', function () {
    const id   = $(this).data('id');
    const desc = $(this).data('desc');

    Swal.fire({
      title: '¿Eliminar equipo?',
      text: desc,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-label-secondary' },
      buttonsStyling: false,
    }).then(result => {
      if (!result.isConfirmed) return;
      $.ajax({
        url:  `${baseUrl}transporters/${transporterId}/equipments/${id}`,
        type: 'POST',
        data: { _token: csrfToken, _method: 'DELETE' },
        success: function () {
          dt.ajax.reload(null, false);
          showToast('Equipo eliminado.');
        },
        error: function () {
          Swal.fire('Error', 'No se pudo eliminar el equipo.', 'error');
        }
      });
    });
  });
});
