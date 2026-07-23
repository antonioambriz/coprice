'use strict';

$(function () {
  const modal      = new bootstrap.Modal(document.getElementById('modalContact'));
  const $title     = $('#modalContactTitle');
  const $id        = $('#contactId');
  const $name      = $('#contactName');
  const $position  = $('#contactPosition');
  const $phone     = $('#contactPhone');
  const $email     = $('#contactEmail');
  const $isPrimary = $('#contactIsPrimary');
  const $errName   = $('#errContactName');
  const toast      = new bootstrap.Toast(document.getElementById('globalToast'));

  function showToast(msg) {
    $('#globalToastBody').text(msg);
    toast.show();
  }

  const dt = $('#contactsTable').DataTable({
    ajax: { url: contactsGetDataUrl, dataSrc: 'data' },
    columns: [
      { data: 'id', width: '60px' },
      { data: 'name' },
      { data: 'position' },
      { data: 'phone' },
      { data: 'email' },
      {
        data: 'is_primary', className: 'text-center',
        render: (data) => data
          ? '<span class="badge bg-label-primary">Principal</span>'
          : ''
      },
      {
        data: null, className: 'text-center', orderable: false,
        render: (data, type, full) => `
          <div class="d-flex justify-content-center gap-1">
            <button class="btn btn-sm btn-icon btn-outline-secondary btn-edit-contact"
              data-id="${full.id}" data-name="${full.name}"
              data-position="${full.position === '—' ? '' : full.position}"
              data-phone="${full.phone === '—' ? '' : full.phone}"
              data-email="${full.email === '—' ? '' : full.email}"
              data-primary="${full.is_primary}" title="Editar">
              <i class="ti tabler-edit"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-outline-danger btn-delete-contact"
              data-id="${full.id}" data-name="${full.name}" title="Eliminar">
              <i class="ti tabler-trash"></i>
            </button>
          </div>`
      }
    ],
    responsive: true,
  });

  // ─── Abrir modal para crear ───────────────────────────────────
  $('[data-bs-target="#modalContact"]').on('click', function () {
    $title.text('Nuevo Contacto');
    $id.val('');
    $name.val('').removeClass('is-invalid');
    $position.val('');
    $phone.val('');
    $email.val('');
    $isPrimary.prop('checked', false);
  });

  // ─── Abrir modal para editar ──────────────────────────────────
  $(document).on('click', '.btn-edit-contact', function () {
    $title.text('Editar Contacto');
    $id.val($(this).data('id'));
    $name.val($(this).data('name')).removeClass('is-invalid');
    $position.val($(this).data('position'));
    $phone.val($(this).data('phone'));
    $email.val($(this).data('email'));
    $isPrimary.prop('checked', $(this).data('primary') == true || $(this).data('primary') == 1);
    modal.show();
  });

  // ─── Guardar (crear o editar) ─────────────────────────────────
  $('#btnSaveContact').on('click', function () {
    const id    = $id.val();
    const isNew = !id;
    const url   = isNew ? contactsStoreUrl : `${contactsBaseUrl}/${id}`;

    $name.removeClass('is-invalid');
    $errName.text('');

    $.ajax({
      url,
      type: 'POST',
      data: {
        _token:      csrfToken,
        _method:     isNew ? 'POST' : 'PUT',
        name:        $name.val(),
        position:    $position.val(),
        phone:       $phone.val(),
        email:       $email.val(),
        is_primary:  $isPrimary.is(':checked') ? 1 : 0,
      },
      success: function () {
        modal.hide();
        dt.ajax.reload(null, false);
        showToast(isNew ? 'Contacto registrado.' : 'Contacto actualizado.');
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
  $(document).on('click', '.btn-delete-contact', function () {
    const id   = $(this).data('id');
    const name = $(this).data('name');

    Swal.fire({
      title: '¿Eliminar contacto?',
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
        url:  `${contactsBaseUrl}/${id}`,
        type: 'POST',
        data: { _token: csrfToken, _method: 'DELETE' },
        success: function () {
          dt.ajax.reload(null, false);
          showToast('Contacto eliminado.');
        },
        error: function () {
          Swal.fire('Error', 'No se pudo eliminar el contacto.', 'error');
        }
      });
    });
  });
});
