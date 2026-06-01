'use strict';

$(function () {
  const wastesBody         = $('#wastesBody');
  const btnAddRow          = $('#addRow');
  const generatorSel       = $('#generatorSelect');
  const subGenRow          = $('#subGeneratorRow');
  const subGenSel          = $('#subGeneratorSelect');
  const assumedInfo        = $('#assumedWeightInfo');
  const assumedValue       = $('#assumedWeightValue');
  const transporterSel     = $('#transporterSelect');
  const equipmentSwitch    = $('#requiresTransportEquipment');
  const equipmentRow       = $('#transportEquipmentRow');
  const equipmentSel       = $('#transportEquipmentSelect');
  const manifestSwitch     = $('#requiresManifest');

  // ─── FLATPICKR ───────────────────────────────────────────────
  const fpConfig = {
    locale: 'es',
    enableTime: true,
    dateFormat: 'Y-m-d H:i',
    altInput: true,
    altFormat: 'd/m/Y H:i',
    time_24hr: true,
    allowInput: true,
  };

  $('.flatpickr').each(function () {
    flatpickr(this, { ...fpConfig, defaultDate: $(this).val() || null });
  });

  // ─── SELECT2 ─────────────────────────────────────────────────
  function initSelect2(selector, parent) {
    $(selector).each(function () {
      $(this).select2({
        placeholder: 'Seleccionar...',
        dropdownParent: parent || $(this).parent(),
        width: '100%',
      });
    });
  }

  initSelect2('.select2');

  // ─── CARGA DINÁMICA DE SUB-GENERADORES ───────────────────────
  function loadSubGenerators(genId, selectId) {
    subGenSel.empty().append('<option value="">— Seleccionar división —</option>');
    assumedInfo.addClass('d-none');

    if (!genId) { subGenRow.hide(); return; }

    $.get(baseUrl + 'withdrawals/sub-generators/' + genId, function (data) {
      $.each(data, function (_i, sub) {
        subGenSel.append(
          $('<option>', {
            value: sub.id,
            text: sub.name,
            'data-assumed': sub.assumed_weight || '',
            'data-requires-manifest': sub.requires_manifest ? '1' : '0',
            selected: sub.id === selectId,
          })
        );
      });
      subGenSel.trigger('change.select2');
      subGenRow.show();

      // Mostrar peso asumido si ya hay uno seleccionado
      const sel = subGenSel.find(':selected');
      if (sel.val() && sel.data('assumed')) {
        assumedValue.text(Number(sel.data('assumed')).toLocaleString('es-MX'));
        assumedInfo.removeClass('d-none');
      }
    });
  }

  generatorSel.on('change', function () {
    const selected             = $(this).find(':selected');
    const hasSub               = selected.data('has-sub') === 1 || selected.data('has-sub') === '1';
    const genId                = $(this).val();
    const preferredTransporter = selected.data('preferred-transporter');
    const genRequiresManifest  = selected.data('requires-manifest') === 1 || selected.data('requires-manifest') === '1';

    if (preferredTransporter) {
      transporterSel.val(preferredTransporter).trigger('change');
    }

    if (hasSub && genId) {
      loadSubGenerators(genId, null);
    } else {
      subGenRow.hide();
      manifestSwitch.prop('checked', genRequiresManifest);
    }
  });

  subGenSel.on('change', function () {
    const selected = $(this).find(':selected');
    const assumed  = selected.data('assumed');
    const subRequiresManifest = selected.data('requires-manifest');

    if (assumed) {
      assumedValue.text(Number(assumed).toLocaleString('es-MX'));
      assumedInfo.removeClass('d-none');
    } else {
      assumedInfo.addClass('d-none');
    }

    if (selected.val()) {
      manifestSwitch.prop('checked', subRequiresManifest === 1 || subRequiresManifest === '1');
    }
  });

  // ─── CARGA DINÁMICA DE EQUIPOS DE TRANSPORTE ─────────────────
  function loadTransportEquipments(transporterId, selectId) {
    equipmentSel.empty().append('<option value="">— Seleccionar equipo —</option>');
    if (!transporterId) return;

    $.get(baseUrl + 'withdrawals/transport-equipments/' + transporterId, function (data) {
      $.each(data, function (_i, eq) {
        const label = eq.plate_number ? eq.description + ' (' + eq.plate_number + ')' : eq.description;
        equipmentSel.append($('<option>', { value: eq.id, text: label, selected: eq.id === selectId }));
      });
    });
  }

  function toggleEquipmentRow(preselectedId) {
    if (equipmentSwitch.is(':checked')) {
      equipmentRow.show();
      loadTransportEquipments(transporterSel.val(), preselectedId || null);
    } else {
      equipmentRow.hide();
      equipmentSel.val('');
    }
  }

  equipmentSwitch.on('change', function () { toggleEquipmentRow(null); });

  transporterSel.on('change', function () {
    if (equipmentSwitch.is(':checked')) {
      loadTransportEquipments($(this).val(), null);
    }
  });

  // ─── FILAS DE RESIDUOS ────────────────────────────────────────
  window.addRow = function (item) {
    const index  = wastesBody.find('tr').length;
    const newRow = $(($('#wasteRowTemplate').html()).replace(/__INDEX__/g, index));

    if (item) {
      newRow.find('[name$="[waste_id]"]').val(item.waste_id);
      newRow.find('[name$="[quantity]"]').val(item.quantity);
      newRow.find('[name$="[unit]"]').val(item.unit);
      newRow.find('[name$="[container_type]"]').val(item.container_type || '');

      if (item.container_capacity) {
        const parts = item.container_capacity.toString().split(' ');
        newRow.find('[name$="[container_capacity]"]').val(parts[0] || '');
        if (parts[1]) newRow.find('[name$="[container_unit]"]').val(parts[1]);
      }
    }

    wastesBody.append(newRow);
    initSelect2(newRow.find('.select2-dynamic'), newRow.find('td:first-child'));

    if (item) {
      newRow.find('.select2-dynamic').val(item.waste_id).trigger('change');
    }
  };

  btnAddRow.on('click', function (e) {
    e.preventDefault();
    addRow(null);
  });

  $(document).on('click', '.btn-remove', function (e) {
    e.preventDefault();
    if (wastesBody.find('tr').length > 1) {
      $(this).closest('tr').remove();
    }
  });

  // ─── INICIALIZACIÓN CON DATOS EXISTENTES ─────────────────────
  const genSelected = generatorSel.find(':selected');
  const hasSub      = genSelected.data('has-sub') === 1 || genSelected.data('has-sub') === '1';

  if (hasSub && generatorSel.val()) {
    loadSubGenerators(generatorSel.val(), existingSubGenId);
  }

  if (equipmentSwitch.is(':checked') && transporterSel.val()) {
    toggleEquipmentRow(existingTransportId);
  }

  if (existingItems && existingItems.length > 0) {
    existingItems.forEach(function (item) { addRow(item); });
  } else {
    addRow(null);
  }
});
