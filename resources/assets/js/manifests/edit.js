'use strict';

import $ from 'jquery';
import 'select2';
import flatpickr from 'flatpickr';
import { Spanish } from 'flatpickr/dist/l10n/es.js';

document.addEventListener('DOMContentLoaded', function () {
  flatpickr('#emissionDate', { locale: Spanish, dateFormat: 'Y-m-d', altInput: true, altFormat: 'd/m/Y', allowInput: true });

  const rangeEl  = document.getElementById('periodRange');
  const fpRange  = flatpickr('#periodRange', {
    locale: Spanish,
    mode: 'range',
    dateFormat: 'Y-m-d',
    defaultDate: rangeEl?.dataset.start
      ? [rangeEl.dataset.start, rangeEl.dataset.end].filter(Boolean)
      : undefined,
    onChange(selectedDates) {
      document.getElementById('period_start').value = selectedDates[0]
        ? flatpickr.formatDate(selectedDates[0], 'Y-m-d') : '';
      document.getElementById('period_end').value = selectedDates[1]
        ? flatpickr.formatDate(selectedDates[1], 'Y-m-d') : '';
    },
  });

  document.querySelectorAll('.dropdown-item[data-range]').forEach(item => {
    item.addEventListener('click', function (e) {
      e.preventDefault();
      const range = this.dataset.range;
      const now   = new Date();
      let start, end;

      if (range === 'week') {
        const dow = now.getDay();
        const diff = dow === 0 ? -6 : 1 - dow;
        start = new Date(now); start.setDate(now.getDate() + diff);
        end   = new Date(start); end.setDate(start.getDate() + 6);
      } else if (range === 'fortnight') {
        const d = now.getDate();
        if (d <= 15) {
          start = new Date(now.getFullYear(), now.getMonth(), 1);
          end   = new Date(now.getFullYear(), now.getMonth(), 15);
        } else {
          start = new Date(now.getFullYear(), now.getMonth(), 16);
          end   = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        }
      } else if (range === 'month') {
        start = new Date(now.getFullYear(), now.getMonth(), 1);
        end   = new Date(now.getFullYear(), now.getMonth() + 1, 0);
      } else {
        fpRange.open();
        return;
      }

      fpRange.setDate([start, end]);
      document.getElementById('rangePresetLabel').textContent = this.textContent;
    });
  });

  $('.select2').select2({ placeholder: '— Seleccionar —', allowClear: true });

  const currentSubId = parseInt($('#subGeneratorSelect').data('current')) || null;
  const manifestId   = parseInt(document.getElementById('manifestForm')?.dataset.manifestId) || null;

  function loadWithdrawals(generatorId) {
    const $sel = $('#withdrawalsSelect');
    $sel.empty().trigger('change');
    $sel.prop('disabled', true);

    if (!generatorId) return;

    $sel.select2({ placeholder: 'Cargando...', allowClear: true, width: '100%' });

    const url = manifestId
      ? baseUrl + 'manifests/withdrawals/' + generatorId + '/' + manifestId
      : baseUrl + 'manifests/withdrawals/' + generatorId;

    $.get(url, function (data) {
      $sel.empty();
      if (!data.length) {
        $sel.select2({ placeholder: 'No hay retiros disponibles', allowClear: true, width: '100%' });
        return;
      }
      data.forEach(w => {
        const opt = new Option(
          `${w.folio_interno}  ·  ${w.reception_date}  ·  ${w.transporter_name}`,
          w.id, w.linked, w.linked
        );
        $sel.append(opt);
      });
      $sel.prop('disabled', false)
          .select2({ placeholder: '— Seleccionar retiros —', allowClear: true, width: '100%' })
          .trigger('change');
    });
  }

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
          data.forEach(sub => {
            const sel = sub.id === currentSubId ? 'selected' : '';
            $subSel.append(`<option value="${sub.id}" ${sel}>${sub.name}</option>`);
          });
          $subRow.show();
          $subSel.trigger('change.select2');
        }
      });
    }

    loadWithdrawals(genId);
  });

  if ($('#generatorSelect').val()) $('#generatorSelect').trigger('change');
});
