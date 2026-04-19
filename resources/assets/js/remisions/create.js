'use strict';

import $ from 'jquery';
import 'select2';
import flatpickr from 'flatpickr';
import { Spanish } from 'flatpickr/dist/l10n/es.js';

document.addEventListener('DOMContentLoaded', function () {
  flatpickr('#emissionDate', { locale: Spanish, dateFormat: 'Y-m-d', altInput: true, altFormat: 'd/m/Y', allowInput: true });

  $('.select2').select2({ placeholder: '— Seleccionar —', allowClear: true });

  $('#withdrawalsSelect').select2({
    placeholder: '— Selecciona un generador primero —',
    allowClear: true,
    width: '100%',
  });

  // Mapa de subtotales por withdrawal id
  const subtotals = {};

  function updateTotal() {
    const selected = $('#withdrawalsSelect').val() || [];
    const total = selected.reduce((sum, id) => sum + (parseFloat(subtotals[id]) || 0), 0);
    document.getElementById('totalDisplay').textContent = total.toLocaleString('es-MX', { minimumFractionDigits: 2 });
  }

  function loadWithdrawals(generatorId) {
    const $sel = $('#withdrawalsSelect');
    $sel.empty().trigger('change');
    $sel.prop('disabled', true);

    if (!generatorId) return;

    $sel.select2({ placeholder: 'Cargando...', allowClear: true, width: '100%' });

    $.get(baseUrl + 'remisions/withdrawals/' + generatorId, function (data) {
      $sel.empty();
      if (!data.length) {
        $sel.select2({ placeholder: 'No hay retiros disponibles', allowClear: true, width: '100%' });
        return;
      }
      data.forEach(w => {
        subtotals[w.id] = parseFloat(w.subtotal.replace(',', '')) || 0;
        $sel.append(new Option(
          `${w.folio_interno}  ·  ${w.reception_date}  ·  ${w.transporter_name}  ·  $${w.subtotal}`,
          w.id, false, false
        ));
      });
      $sel.prop('disabled', false)
          .select2({ placeholder: '— Seleccionar retiros —', allowClear: true, width: '100%' })
          .trigger('change');
    });
  }

  $('#withdrawalsSelect').on('change', updateTotal);

  $('#generatorSelect').on('change', function () {
    const selected = $(this).find(':selected');
    const hasSub   = selected.data('has-sub');
    const genId    = $(this).val();
    const $subRow  = $('#subGeneratorRow');
    const $subSel  = $('#subGeneratorSelect');

    $subSel.empty().append('<option value="">— Seleccionar división —</option>');
    $subRow.hide();

    if (hasSub && genId) {
      $.get(baseUrl + 'withdrawals/sub-generators/' + genId, function (data) {
        if (data.length > 0) {
          data.forEach(sub => $subSel.append(`<option value="${sub.id}">${sub.name}</option>`));
          $subRow.show();
          $subSel.trigger('change.select2');
        }
      });
    }

    loadWithdrawals(genId);
  });

  if ($('#generatorSelect').val()) $('#generatorSelect').trigger('change');
});
