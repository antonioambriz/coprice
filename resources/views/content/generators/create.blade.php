@extends('layouts/layoutMaster')

@section('title', 'Nuevo Generador')

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
    <div class="card-header">
      <h5 class="card-title mb-0">Registrar Generador</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('generators.store') }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Razón Social</label>
            <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
              value="{{ old('company_name') }}" required>
            @error('company_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">Autorización</label>
            <input type="text" name="authorization" class="form-control" value="{{ old('authorization') }}" maxlength="13">
          </div>
          <div class="col-12">
            <label class="form-label">Dirección</label>
            <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Transportista Preferido</label>
            <select name="preferred_transporter_id" class="form-select">
              <option value="">— Sin preferencia —</option>
              @foreach ($transporters as $t)
                <option value="{{ $t->id }}" {{ old('preferred_transporter_id') == $t->id ? 'selected' : '' }}>
                  {{ $t->company_name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- TOGGLES --}}
          <div class="col-12 d-flex gap-4 flex-wrap">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="has_sub_generators" id="hasSubGenerators"
                value="1" {{ old('has_sub_generators') ? 'checked' : '' }}>
              <label class="form-check-label fw-semibold" for="hasSubGenerators">
                Tiene divisiones / sub-generadores
              </label>
            </div>
            <div class="form-check form-switch" id="requiresManifestToggle">
              <input class="form-check-input" type="checkbox" name="requires_manifest" id="requiresManifest"
                value="1" {{ old('requires_manifest') ? 'checked' : '' }}>
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
                    <th></th>
                  </tr>
                </thead>
                <tbody id="subGeneratorsBody">
                  @if(old('sub_generators'))
                    @foreach(old('sub_generators') as $i => $sg)
                      <tr>
                        <td><input type="text" name="sub_generators[{{ $i }}][name]" class="form-control form-control-sm" value="{{ $sg['name'] }}" required></td>
                        <td><input type="number" step="0.01" name="sub_generators[{{ $i }}][assumed_weight]" class="form-control form-control-sm" value="{{ $sg['assumed_weight'] ?? '' }}" placeholder="0.00"></td>
                        <td>
                          <select name="sub_generators[{{ $i }}][report_frequency]" class="form-select form-select-sm">
                            <option value="sporadic" {{ ($sg['report_frequency'] ?? 'sporadic') == 'sporadic' ? 'selected' : '' }}>Esporádico</option>
                            <option value="weekly"   {{ ($sg['report_frequency'] ?? '') == 'weekly'   ? 'selected' : '' }}>Semanal</option>
                            <option value="monthly"  {{ ($sg['report_frequency'] ?? '') == 'monthly'  ? 'selected' : '' }}>Mensual</option>
                          </select>
                        </td>
                        <td class="text-center align-middle">
                          <input class="form-check-input" type="checkbox" name="sub_generators[{{ $i }}][requires_manifest]" value="1" {{ !empty($sg['requires_manifest']) ? 'checked' : '' }}>
                        </td>
                        <td class="text-center align-middle">
                          <button type="button" class="btn btn-sm btn-text-danger btn-remove-sub"><i class="ti tabler-trash"></i></button>
                        </td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addSubGenerator">
              <i class="ti tabler-plus me-1"></i> Añadir División
            </button>
          </div>

          <div class="col-12 text-end mt-2">
            <a href="{{ route('generators.index') }}" class="btn btn-label-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar Generador</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script type="text/template" id="subGenRowTemplate">
    <tr>
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
        <button type="button" class="btn btn-sm btn-text-danger btn-remove-sub"><i class="ti tabler-trash"></i></button>
      </td>
    </tr>
  </script>
@endsection

@section('page-script')
  @vite(['resources/assets/js/generators/create.js'])
@endsection
