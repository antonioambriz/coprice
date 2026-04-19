'use strict';

$(function () {
  // 1. Inicializar Select2
  const select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: $this.data('placeholder'),
        dropdownParent: $this.parent()
      });
    });
  }

  // 2. Inicializar Tagify
  const tagsEl = document.querySelector('#tags');
  if (tagsEl) new Tagify(tagsEl);

  // 3. Inicializar Quill
  const editor = document.getElementById('full-editor');
  if (editor) {
    const quill = new Quill(editor, {
      modules: { toolbar: '.ql-toolbar' }, // O usa el set de herramientas completo
      theme: 'snow'
    });

    // Sincronizar Quill con el input hidden antes del submit
    $('#form-create-waste').on('submit', function () {
      $('#notes').val(quill.root.innerHTML);
    });
  }
});
