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
  const manifestSwitch    = $('#requiresManifest');

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
    const isReceptionDate = this.id === 'receptionDate';
    flatpickr(this, {
      ...fpConfig,
      ...(isReceptionDate ? { enableTime: false, dateFormat: 'Y-m-d', altFormat: 'd/m/Y' } : {}),
      defaultDate: $(this).val() || new Date(),
    });
  });

  // ─── SELECT2 ─────────────────────────────────────────────────
  function initSelect2(selector, parent) {
    $(selector).each(function () {
      $(this).select2({
        placeholder: 'Seleccionar...',
        dropdownParent: parent || $('body'),
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
    const genRequiresManifest  = selected.data('requires-manifest') === 1 || selected.data('requires-manifest') === '1';

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
      // Sin sub-generadores: heredar valor del generador
      manifestSwitch.prop('checked', genRequiresManifest);
      subGenRow.hide();
    }
  });

  // Al seleccionar sub-generador: su requires_manifest tiene prioridad
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

  // ─── CAPACIDADES POR TIPO DE ENVASADO ────────────────────────
  const CONTAINER_CAPACITIES = {
    'Contenedor':          [1, 2, 5, 7, 8, 10, 12, 14, 18, 21, 30],
    'Contenedor metálico': [12, 21],
    'Contenedor cerrado':  [5],
    'Pipa':                [10, 15, 20, 31],
    'Estiba':              [7.5],
    'Góndola':             [30],
    'Plataforma':          [12],
    'Roll off':            [30],
    'Saco':                [0.5],
    'Tambos':              [0.2],
    'Tanque':              [10, 20],
    'Tolva':               [15, 16],
    'Tote':                [1],
    'Vactor':              [5, 9, 10, 20, 30],
  };

  $(document).on('change', '.container-type-sel', function () {
    const type   = $(this).val();
    const capSel = $(this).closest('tr').find('.container-capacity-sel');
    capSel.empty().append('<option value="">—</option>');

    if (type && CONTAINER_CAPACITIES[type]) {
      CONTAINER_CAPACITIES[type].forEach(cap => {
        capSel.append($('<option>', { value: cap, text: cap }));
      });
      capSel.prop('disabled', false);
    } else {
      capSel.prop('disabled', true);
    }
  });

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
