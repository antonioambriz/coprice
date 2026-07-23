{{--
  Panel reutilizable de "Contactos" (card + tabla + modal), anidado bajo la
  vista de edición de Generador, Transportista o Cliente.

  La vista que lo incluye debe declarar en su @section('page-script'):
    var contactsGetDataUrl = "{{ route('{prefix}.contacts.get-data', $model) }}";
    var contactsStoreUrl   = "{{ route('{prefix}.contacts.store', $model) }}";
    var contactsBaseUrl    = "{{ url('{prefix}/'.$model->id.'/contacts') }}";
    var csrfToken           = "{{ csrf_token() }}";
    @vite(['resources/assets/js/contacts/panel.js'])
--}}

<div class="card mb-4">
  <div class="card-header border-bottom d-flex justify-content-between align-items-center">
    <div>
      <h5 class="card-title mb-0">Contactos</h5>
      <small class="text-muted">Personas de contacto asociadas a este registro</small>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalContact">
      <i class="ti tabler-plus me-1"></i> Nuevo Contacto
    </button>
  </div>
  <div class="card-datatable text-nowrap">
    <table id="contactsTable" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Puesto</th>
          <th>Teléfono</th>
          <th>Email</th>
          <th class="text-center">Principal</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

{{-- Modal Crear / Editar Contacto --}}
<div class="modal fade" id="modalContact" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalContactTitle">Nuevo Contacto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="contactId">
        <div class="mb-3">
          <label class="form-label">Nombre <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="contactName" placeholder="Ej. Juan Pérez">
          <div class="invalid-feedback" id="errContactName"></div>
        </div>
        <div class="mb-3">
          <label class="form-label">Puesto</label>
          <input type="text" class="form-control" id="contactPosition" placeholder="Ej. Gerente de Compras">
        </div>
        <div class="mb-3">
          <label class="form-label">Teléfono</label>
          <input type="text" class="form-control" id="contactPhone" maxlength="20">
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" id="contactEmail">
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="contactIsPrimary">
          <label class="form-check-label" for="contactIsPrimary">Contacto principal</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSaveContact">Guardar</button>
      </div>
    </div>
  </div>
</div>
