@extends('layouts/layoutMaster')

@section('title', 'Editar Residuo - Coprice')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/quill/typography.scss', 'resources/assets/vendor/libs/quill/katex.scss', 'resources/assets/vendor/libs/quill/editor.scss'])
  <style>
    #full-editor .ql-editor { min-height: 150px !important; }
  </style>
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/quill/katex.js', 'resources/assets/vendor/libs/quill/quill.js'])
@endsection

@section('page-script')
  @vite(['resources/assets/js/wastes/create.js'])
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom-icon">
      <li class="breadcrumb-item"><a href="{{ route('dashboard-analytics') }}">Inicio</a><i
          class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item"><a href="{{ route('wastes.index') }}">Residuos</a><i
          class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item active">Editar Residuo</li>
    </ol>
  </nav>

  <div class="row justify-content-center">
    <div class="col-lg-10">
      <form action="{{ route('wastes.update', $waste) }}" method="POST" id="form-create-waste" novalidate>
        @csrf
        @method('PUT')
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center border-bottom mb-4">
            <h5 class="mb-0">Editar Residuo</h5>
            <i class="ti tabler-flask text-primary ti-md"></i>
          </div>

          <div class="card-body">
            <div class="row g-6">

              <div class="col-md-6">
                <label class="form-label" for="description">Descripción (MAYÚSCULAS)</label>
                <input type="text" id="description" name="description"
                  class="form-control @error('description') is-invalid @enderror"
                  value="{{ old('description', $waste->description) }}"
                  placeholder="EJ. LODO FISICOQUÍMICO" style="text-transform: uppercase;" />
              </div>

              <div class="col-md-4">
                <label class="form-label" for="waste_code">Código de Residuo</label>
                <input type="text" id="waste_code" name="waste_code" class="form-control"
                  value="{{ old('waste_code', $waste->waste_code) }}" placeholder="Opcional">
              </div>

              <div class="col-md-4">
                <label class="form-label" for="unit">Unidad de Medida</label>
                <select id="unit" name="unit" class="select2 form-select">
                  <option value="KG"  {{ old('unit', $waste->unit) === 'KG'  ? 'selected' : '' }}>KILOGRAMOS (KG)</option>
                  <option value="LT"  {{ old('unit', $waste->unit) === 'LT'  ? 'selected' : '' }}>LITROS (LT)</option>
                  <option value="TON" {{ old('unit', $waste->unit) === 'TON' ? 'selected' : '' }}>TONELADAS (TON)</option>
                </select>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="physical_state">Estado Físico</label>
                <select id="physical_state" name="physical_state" class="form-select">
                  <option value="">— No especificado —</option>
                  <option value="Sólido"    {{ old('physical_state', $waste->physical_state) === 'Sólido'    ? 'selected' : '' }}>Sólido</option>
                  <option value="Líquido"   {{ old('physical_state', $waste->physical_state) === 'Líquido'   ? 'selected' : '' }}>Líquido</option>
                  <option value="Semisólido"{{ old('physical_state', $waste->physical_state) === 'Semisólido'? 'selected' : '' }}>Semisólido</option>
                </select>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="packaging_type">Clasificación</label>
                <select id="packaging_type" name="packaging_type" class="form-select">
                  <option value="">— No especificado —</option>
                  <option value="RR"  {{ old('packaging_type', $waste->packaging_type) === 'RR'  ? 'selected' : '' }}>RR</option>
                  <option value="RNV" {{ old('packaging_type', $waste->packaging_type) === 'RNV' ? 'selected' : '' }}>RNV</option>
                </select>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="default_price">Precio Base Sugerido</label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" step="0.01" id="default_price" name="default_price" class="form-control"
                    value="{{ old('default_price', $waste->default_price) }}">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-check form-switch mt-md-8">
                  <input class="form-check-input" type="checkbox" name="is_hazardous" id="is_hazardous"
                    value="1" {{ old('is_hazardous', $waste->is_hazardous) ? 'checked' : '' }}>
                  <label class="form-check-label fw-bold" for="is_hazardous">¿Es Residuo Peligroso?</label>
                </div>
              </div>

              <div class="col-12">
                <label class="form-label">Especificaciones de Manejo</label>
                <div id="full-editor">{!! old('notes', $waste->notes) !!}</div>
                <input type="hidden" name="notes" id="notes">
              </div>
            </div>
          </div>

          <div class="card-footer border-top bg-transparent py-4">
            <div class="d-flex justify-content-end align-items-center gap-3">
              <a href="{{ route('wastes.index') }}" class="btn btn-label-secondary waves-effect">
                Cancelar
              </a>
              <button type="submit" class="btn btn-primary shadow-none waves-effect waves-light">
                <i class="ti tabler-device-floppy me-1"></i> Guardar Cambios
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
