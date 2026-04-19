'use strict';

$(function () {
  const wastesBody        = $('#wastesBody');
  const btnAddRow         = $('#addRow');
  const generatorSel      = $('#generatorSelect');
  const subGenRow         = $('#subGeneratorRow');
  const subGenSel         = $('#subGeneratorSelect');
  const assumedInfo       = $('#assumedWeightInfo');
  const assumedValue      = $('#assumedWeightValue');
  const transporterSel    = $('#transporterSelect');
  const equipmentSwitch   = $('#requiresTransportEquipment');
  const equipmentRow      = $('#transportEquipmentRow');
  const equipmentSel      = $('#transportEquipmentSelect');

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
    flatpickr(this, {
      ...fpConfig,
      defaultDate: $(this).val() || new Date(),
    });
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
  generatorSel.on('change', function () {
    const selected             = $(this).find(':selected');
    const hasSub               = selected.data('has-sub') === 1 || selected.data('has-sub') === '1';
    const genId                = $(this).val();
    const preferredTransporter = selected.data('preferred-transporter');

    // Auto-seleccionar transportista preferido
    if (preferredTransporter) {
      $('select[name="transporter_id"]').val(preferredTransporter).trigger('change');
    }

    subGenSel.empty().append('<option value="">— Seleccionar división —</option>');
    assumedInfo.addClass('d-none');

    if (hasSub && genId) {
      $.get(baseUrl + 'withdrawals/sub-generators/' + genId, function (data) {
        $.each(data, function (_i, sub) {
          subGenSel.append(
            $('<option>', {
              value: sub.id,
              text: sub.name,
              'data-assumed': sub.assumed_weight || '',
              'data-requires-manifest': sub.requires_manifest ? '1' : '0',
            })
          );
        });
        subGenSel.trigger('change.select2');
        subGenRow.show();
      });
    } else {
      subGenRow.hide();
    }
  });

  // Mostrar peso asumido al seleccionar sub-generador
  subGenSel.on('change', function () {
    const assumed = $(this).find(':selected').data('assumed');
    if (assumed) {
      assumedValue.text(Number(assumed).toLocaleString('es-MX'));
      assumedInfo.removeClass('d-none');
    } else {
      assumedInfo.addClass('d-none');
    }
  });

  // ─── CARGA DINÁMICA DE EQUIPOS DE TRANSPORTE ─────────────────
  function loadTransportEquipments(transporterId) {
    equipmentSel.empty().append('<option value="">— Seleccionar equipo —</option>');
    if (!transporterId) return;

    $.get(baseUrl + 'withdrawals/transport-equipments/' + transporterId, function (data) {
      $.each(data, function (_i, eq) {
        const label = eq.plate_number ? eq.description + ' (' + eq.plate_number + ')' : eq.description;
        equipmentSel.append($('<option>', { value: eq.id, text: label }));
      });
    });
  }

  function toggleEquipmentRow() {
    if (equipmentSwitch.is(':checked')) {
      equipmentRow.show();
      loadTransportEquipments(transporterSel.val());
    } else {
      equipmentRow.hide();
      equipmentSel.val('');
    }
  }

  equipmentSwitch.on('change', toggleEquipmentRow);

  transporterSel.on('change', function () {
    if (equipmentSwitch.is(':checked')) {
      loadTransportEquipments($(this).val());
    }
  });

  // Si viene marcado por old() al recargar con errores
  if (equipmentSwitch.is(':checked')) toggleEquipmentRow();

  // ─── FILAS DE RESIDUOS ────────────────────────────────────────
  window.addRow = function () {
    const index  = wastesBody.find('tr').length;
    const newRow = $(($('#wasteRowTemplate').html()).replace(/__INDEX__/g, index));
    wastesBody.append(newRow);
    initSelect2(newRow.find('.select2-dynamic'), newRow.find('td:first-child'));
  };

  btnAddRow.on('click', function (e) {
    e.preventDefault();
    addRow();
  });

  $(document).on('click', '.btn-remove', function (e) {
    e.preventDefault();
    if (wastesBody.find('tr').length > 1) {
      $(this).closest('tr').remove();
    }
  });

  if (wastesBody.find('tr').length === 0) addRow();
});
