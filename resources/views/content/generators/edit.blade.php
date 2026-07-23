@extends('layouts/layoutMaster')

@section('title', 'Editar Generador')

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

@section('page-style')
<style>
  #contactsTable_wrapper > .row:not(.dt-layout-table) {
    padding-inline: 1.5rem;
  }
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom-icon">
      <li class="breadcrumb-item">
        <a href="{{ route('dashboard-analytics') }}">Inicio</a>
        <i class="breadcrumb-icon icon-base ti tabler-chevron-right align-middle icon-xs mx-2"></i>
      </li>
      <li class="breadcrumb-item">
        Catálogos
        <i class="breadcrumb-icon icon-base ti tabler-chevron-right align-middle icon-xs mx-2"></i>
      </li>
      <li class="breadcrumb-item active">Generadores</li>
    </ol>
  </nav>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Modificar Generador: {{ $generator->company_name }}</h5>
      <span class="badge bg-label-primary">ID: {{ $generator->id }}</span>
    </div>
    <div class="card-body">
      <form action="{{ route('generators.update', $generator->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Razón Social</label>
            <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
              value="{{ old('company_name', $generator->company_name) }}" required>
            @error('company_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">Autorización</label>
            <input type="text" name="authorization" class="form-control" value="{{ old('authorization', $generator->authorization) }}" maxlength="13">
          </div>
          <div class="col-12">
            <label class="form-label">Dirección</label>
            <textarea name="address" class="form-control" rows="3">{{ old('address', $generator->address) }}</textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Transportista Preferido</label>
            <select name="preferred_transporter_id" class="form-select">
              <option value="">— Sin preferencia —</option>
              @foreach ($transporters as $t)
                <option value="{{ $t->id }}"
                  {{ old('preferred_transporter_id', $generator->preferred_transporter_id) == $t->id ? 'selected' : '' }}>
                  {{ $t->company_name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- TOGGLES --}}
          <div class="col-12 d-flex gap-4 flex-wrap align-items-center">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="has_sub_generators" id="hasSubGenerators"
                value="1" {{ old('has_sub_generators', $generator->has_sub_generators) ? 'checked' : '' }}>
              <label class="form-check-label fw-semibold" for="hasSubGenerators">
                Tiene divisiones / sub-generadores
              </label>
            </div>
            <div class="form-check form-switch" id="requiresManifestToggle">
              <input class="form-check-input" type="checkbox" name="requires_manifest" id="requiresManifest"
                value="1" {{ old('requires_manifest', $generator->requires_manifest) ? 'checked' : '' }}>
              <label class="form-check-label fw-semibold" for="requiresManifest">
                Requiere manifiesto
              </label>
            </div>
          </div>

          {{-- TABLA SUB-GENERADORES --}}
          <div class="col-12" id="subGeneratorsSection" style="display:none">
            <div class="table-responsive">
              <table class="table table-sm table-bordered mb-2">
                <thead>
                  <tr>
                    <th>Nombre División</th>
                    <th>Peso Asumido (kg)</th>
                    <th>Frec. Reporte</th>
                    <th class="text-center">Req. Manifiesto</th>
                    <th class="text-center">Activo</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="subGeneratorsBody">
                  @php $subGens = old('sub_generators') ? collect(old('sub_generators')) : $generator->subGenerators; @endphp
                  @foreach($subGens as $i => $sg)
                    @php
                      $isModel   = $sg instanceof \App\Models\SubGenerator;
                      $sgId      = $isModel ? $sg->id : ($sg['id'] ?? '');
                      $sgName    = $isModel ? $sg->name : ($sg['name'] ?? '');
                      $sgWeight  = $isModel ? $sg->assumed_weight : ($sg['assumed_weight'] ?? '');
                      $sgFreq    = $isModel ? $sg->report_frequency : ($sg['report_frequency'] ?? 'sporadic');
                      $sgManif   = $isModel ? $sg->requires_manifest : !empty($sg['requires_manifest']);
                      $sgStatus  = $isModel ? $sg->status : (isset($sg['status']) ? (bool)$sg['status'] : true);
                      $sgDeleted = !$isModel && !empty($sg['_delete']);
                    @endphp
                    <tr class="{{ $sgDeleted ? 'table-danger opacity-50' : '' }}" data-index="{{ $i }}">
                      <input type="hidden" name="sub_generators[{{ $i }}][id]" value="{{ $sgId }}">
                      <input type="hidden" name="sub_generators[{{ $i }}][_delete]" class="delete-flag" value="{{ $sgDeleted ? '1' : '' }}">
                      <td><input type="text" name="sub_generators[{{ $i }}][name]" class="form-control form-control-sm" value="{{ $sgName }}" {{ $sgDeleted ? 'disabled' : 'required' }}></td>
                      <td><input type="number" step="0.01" name="sub_generators[{{ $i }}][assumed_weight]" class="form-control form-control-sm" value="{{ $sgWeight }}" placeholder="0.00" {{ $sgDeleted ? 'disabled' : '' }}></td>
                      <td>
                        <select name="sub_generators[{{ $i }}][report_frequency]" class="form-select form-select-sm" {{ $sgDeleted ? 'disabled' : '' }}>
                          <option value="sporadic" {{ $sgFreq == 'sporadic' ? 'selected' : '' }}>Esporádico</option>
                          <option value="weekly"   {{ $sgFreq == 'weekly'   ? 'selected' : '' }}>Semanal</option>
                          <option value="monthly"  {{ $sgFreq == 'monthly'  ? 'selected' : '' }}>Mensual</option>
                        </select>
                      </td>
                      <td class="text-center align-middle">
                        <input class="form-check-input" type="checkbox" name="sub_generators[{{ $i }}][requires_manifest]" value="1" {{ $sgManif ? 'checked' : '' }} {{ $sgDeleted ? 'disabled' : '' }}>
                      </td>
                      <td class="text-center align-middle">
                        <input class="form-check-input" type="checkbox" name="sub_generators[{{ $i }}][status]" value="1" {{ $sgStatus ? 'checked' : '' }} {{ $sgDeleted ? 'disabled' : '' }}>
                      </td>
                      <td class="text-center align-middle">
                        @if($sgId)
                          <button type="button" class="btn btn-sm btn-text-danger btn-toggle-delete" title="{{ $sgDeleted ? 'Restaurar' : 'Eliminar' }}">
                            <i class="ti {{ $sgDeleted ? 'tabler-restore' : 'tabler-trash' }}"></i>
                          </button>
                        @else
                          <button type="button" class="btn btn-sm btn-text-danger btn-remove-sub">
                            <i class="ti tabler-trash"></i>
                          </button>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addSubGenerator">
              <i class="ti tabler-plus me-1"></i> Añadir División
            </button>
          </div>

          <div class="col-12 text-end mt-4">
            <hr>
            <a href="{{ route('generators.index') }}" class="btn btn-label-secondary me-2">
              <i class="ti tabler-arrow-left me-1"></i> Regresar
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="ti tabler-device-floppy me-1"></i> Guardar Cambios
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  @include('content.contacts._panel', [
    'contactsGetDataUrl' => route('generators.contacts.get-data', $generator),
    'contactsStoreUrl'   => route('generators.contacts.store', $generator),
    'contactsBaseUrl'    => url('generators/'.$generator->id.'/contacts'),
  ])

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

  <script type="text/template" id="subGenRowTemplate">
    <tr>
      <input type="hidden" name="sub_generators[__INDEX__][id]" value="">
      <input type="hidden" name="sub_generators[__INDEX__][_delete]" class="delete-flag" value="">
      <td><input type="text" name="sub_generators[__INDEX__][name]" class="form-control form-control-sm" required></td>
      <td><input type="number" step="0.01" name="sub_generators[__INDEX__][assumed_weight]" class="form-control form-control-sm" placeholder="0.00"></td>
      <td>
        <select name="sub_generators[__INDEX__][report_frequency]" class="form-select form-select-sm">
          <option value="sporadic">Esporádico</option>
          <option value="weekly">Semanal</option>
          <option value="monthly">Mensual</option>
        </select>
      </td>
      <td class="text-center align-middle">
        <input class="form-check-input" type="checkbox" name="sub_generators[__INDEX__][requires_manifest]" value="1">
      </td>
      <td class="text-center align-middle">
        <input class="form-check-input" type="checkbox" name="sub_generators[__INDEX__][status]" value="1" checked>
      </td>
      <td class="text-center align-middle">
        <button type="button" class="btn btn-sm btn-text-danger btn-remove-sub"><i class="ti tabler-trash"></i></button>
      </td>
    </tr>
  </script>
@endsection

@section('page-script')
  @vite(['resources/assets/js/generators/edit.js'])

  <script>
    var contactsGetDataUrl = "{{ route('generators.contacts.get-data', $generator) }}";
    var contactsStoreUrl   = "{{ route('generators.contacts.store', $generator) }}";
    var contactsBaseUrl    = "{{ url('generators/'.$generator->id.'/contacts') }}";
    var csrfToken           = "{{ csrf_token() }}";
  </script>
  @vite(['resources/assets/js/contacts/panel.js'])
@endsection
