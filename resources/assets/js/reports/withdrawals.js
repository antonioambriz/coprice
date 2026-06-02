'use strict';

document.addEventListener('DOMContentLoaded', function () {
  // Flatpickr en español para los campos de fecha
  const fpConfig = {
    dateFormat: 'Y-m-d',
    altInput: true,
    altFormat: 'd/m/Y',
    locale: {
      rangeSeparator: ' al ',
      weekdays: { shorthand: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'], longhand: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'] },
      months:   { shorthand: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                  longhand:  ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] },
      firstDayOfWeek: 1,
    },
    allowInput: true,
  };

  if (document.getElementById('date_from')) flatpickr('#date_from', fpConfig);
  if (document.getElementById('date_to'))   flatpickr('#date_to',   fpConfig);

  // Select2 para generador y transportista
  if (window.$ && $.fn.select2) {
    $('#generator_id, #transporter_id').select2({
      theme: 'bootstrap-5',
      width: '100%',
      allowClear: true,
      placeholder: '— Todos —',
    });
  }
});
