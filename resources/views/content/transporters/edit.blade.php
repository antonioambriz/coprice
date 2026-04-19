@extends('layouts/layoutMaster')

@section('title', 'Editar Transportista')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  ])
@endsection

@section('content')
  <h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Transportistas /</span> Editar: {{ $transporter->company_name }}
  </h4>

  <div class="row">
    <div class="col-md-12">
      <div class="card mb-4">
        <h5 class="card-header">Modificar Datos del Transportista</h5>
        <div class="card-body">
          <form action="{{ route('transporters.update', $transporter) }}" method="POST">
            @csrf
            @method('PUT') {{-- Requerido para rutas de actualización --}}

            <div class="row">
              <div class="mb-3 col-md-6">
                <label for="company_name" class="form-label">Nombre de la Empresa / Razón Social</label>
                <input class="form-control @error('company_name') is-invalid @enderror" type="text" id="company_name"
                  name="company_name" value="{{ old('company_name', $transporter->company_name) }}" required />
                @error('company_name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-6">
                <label for="rfc" class="form-label">RFC</label>
                <input class="form-control @error('rfc') is-invalid @enderror" type="text" id="rfc"
                  name="rfc" value="{{ old('rfc', $transporter->rfc) }}" maxlength="13" />
                @error('rfc')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-6">
                <label for="authorization_number" class="form-label">Número de Autorización</label>
                <input class="form-control @error('authorization_number') is-invalid @enderror" type="text"
                  id="authorization_number" name="authorization_number"
                  value="{{ old('authorization_number', $transporter->authorization_number) }}"
                  placeholder="EJ. IRA-PRME-516/2016" />
                @error('authorization_number')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-6">
                <label for="contact_person" class="form-label">Persona de Contacto</label>
                <input class="form-control @error('contact_person') is-invalid @enderror" type="text"
                  id="contact_person" name="contact_person"
                  value="{{ old('contact_person', $transporter->contact_person) }}" />
                @error('contact_person')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-6">
                <label for="email_remissions" class="form-label">Email para Remisiones</label>
                <input class="form-control @error('email_remissions') is-invalid @enderror" type="email"
                  id="email_remissions" name="email_remissions"
                  value="{{ old('email_remissions', $transporter->email_remissions) }}" />
                @error('email_remissions')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-12">
                <label for="address" class="form-label">Dirección Completa</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $transporter->address) }}</textarea>
                @error('address')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-12">
                <div class="form-check form-switch mt-2">
                  <input class="form-check-input @error('activo') is-invalid @enderror" type="checkbox" id="activo"
                    name="activo" value="1" {{ old('activo', $transporter->activo) == 1 ? 'checked' : '' }}>
                  <label class="form-check-label" for="activo">Transportista Activo</label>
                </div>
                @error('activo')
                  <div class="text-danger small">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mt-2">
              <button type="submit" class="btn btn-primary me-2">Actualizar Cambios</button>
              <a href="{{ route('transporters.index') }}" class="btn btn-label-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>

      {{-- Equipos de Transporte --}}
      <div class="card mb-4">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title mb-0">Equipos de Transporte</h5>
            <small class="text-muted">Vehículos asociados a este transportista</small>
          </div>
          <button class="btn btn-primary btn-nuevo-equipo" data-bs-toggle="modal" data-bs-target="#modalEquipment">
            <i class="ti tabler-plus me-1"></i> Nuevo Equipo
          </button>
        </div>
        <div class="card-datatable text-nowrap">
          <table id="equipmentsTable" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Placa</th>
                <th class="text-center">Estatus</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal Crear / Editar Equipo --}}
  <div class="modal fade" id="modalEquipment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEquipmentTitle">Nuevo Equipo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="equipmentId">
          <div class="mb-3">
            <label class="form-label">Descripción <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="equipmentDescription" placeholder="Ej. CAMIÓN 3 EJES, VACTOR">
            <div class="invalid-feedback" id="errDescription"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Placa</label>
            <input type="text" class="form-control" id="equipmentPlate" placeholder="Ej. ABC-123-D" maxlength="20">
          </div>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="equipmentActivo" checked>
            <label class="form-check-label" for="equipmentActivo">Activo</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnSaveEquipment">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Toast --}}
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="globalToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="ti tabler-check text-success me-2"></i>
        <strong class="me-auto">Coprice</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
      </div>
      <div class="toast-body" id="globalToastBody"></div>
    </div>
  </div>
@endsection

@section('page-script')
  <script>
    var baseUrl       = "{{ url('/') }}/";
    var transporterId = {{ $transporter->id }};
    var getDataUrl    = "{{ route('transport-equipments.get-data', $transporter) }}";
    var storeUrl      = "{{ route('transport-equipments.store', $transporter) }}";
    var csrfToken     = "{{ csrf_token() }}";
  </script>
  @vite(['resources/assets/js/transporters/equipments.js'])
@endsection
