'use strict';

$(function () {
  const toggle  = $('#hasSubGenerators');
  const section = $('#subGeneratorsSection');
  const body    = $('#subGeneratorsBody');

  function addRow() {
    const index    = body.find('tr').length;
    const template = $('#subGenRowTemplate').html().replace(/__INDEX__/g, index);
    body.append(template);
  }

  const manifestToggle = $('#requiresManifestToggle');

  function applyToggle(checked) {
    if (checked) {
      section.show();
      manifestToggle.hide();
      if (body.find('tr').length === 0) addRow();
    } else {
      section.hide();
      manifestToggle.show();
    }
  }

  applyToggle(toggle.is(':checked'));

  toggle.on('change', function () {
    applyToggle($(this).is(':checked'));
  });

  $('#addSubGenerator').on('click', function () {
    addRow();
  });

  // Eliminar fila nueva (sin ID)
  $(document).on('click', '.btn-remove-sub', function () {
    $(this).closest('tr').remove();
  });

  // Marcar/desmarcar eliminación de fila existente (con ID)
  $(document).on('click', '.btn-toggle-delete', function () {
    const row      = $(this).closest('tr');
    const flag     = row.find('.delete-flag');
    const deleting = flag.val() !== '1';

    flag.val(deleting ? '1' : '');
    row.toggleClass('table-danger opacity-50', deleting);
    row.find('input:not(.delete-flag), select').prop('disabled', deleting);
    $(this).find('i').toggleClass('tabler-trash', !deleting).toggleClass('tabler-restore', deleting);
    $(this).attr('title', deleting ? 'Restaurar' : 'Eliminar');
  });
});
