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

  $(document).on('click', '.btn-remove-sub', function () {
    $(this).closest('tr').remove();
  });
});
