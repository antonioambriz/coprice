'use strict';

document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('btn-save');
  if (!btn) return;

  btn.addEventListener('click', function () {
    const payload = {};

    document.querySelectorAll('.permission-checkbox').forEach(cb => {
      // name = "permissions[ROLE][page_key]"
      const match = cb.name.match(/permissions\[([^\]]+)\]\[([^\]]+)\]/);
      if (!match) return;
      const [, role, page] = match;
      if (!payload[role]) payload[role] = {};
      payload[role][page] = cb.checked ? 1 : 0;
    });

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Guardando...';

    fetch(baseUrl + 'permissions', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ permissions: payload }),
    })
      .then(r => r.json())
      .then(data => {
        Swal.fire({
          icon: 'success',
          title: '¡Guardado!',
          text: data.message,
          confirmButtonText: 'Aceptar',
          customClass: { confirmButton: 'btn btn-primary' },
          buttonsStyling: false,
        });
      })
      .catch(() => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'No se pudieron guardar los permisos.',
          confirmButtonText: 'Cerrar',
          customClass: { confirmButton: 'btn btn-label-secondary' },
          buttonsStyling: false,
        });
      })
      .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ti tabler-device-floppy me-1"></i>Guardar cambios';
      });
  });
});
