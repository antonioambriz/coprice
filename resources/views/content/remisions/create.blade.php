@extends('layouts/layoutMaster')

@section('title', 'Nueva Remisión - Coprice')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
  ])
  <style>
    .section-label {
      font-size: 0.70rem; font-weight: 600; letter-spacing: 0.08em;
      text-transform: uppercase; color: #a1acb8;
    }
    body { overflow-x: hidden; }
  </style>
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    'resources/assets/vendor/libs/flatpickr/l10n/es.js',
    'resources/assets/vendor/libs/select2/select2.js',
  ])
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom-icon">
      <li class="breadcrumb-item"><a href="{{ route('dashboard-analytics') }}">Inicio</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item"><a href="{{ route('remisions.index') }}">Remisiones</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item active">Nueva</li>
    </ol>
  </nav>

  <form action="{{ route('remisions.store') }}" method="POST" id="remisionForm">
    @csrf
    <div class="row g-4">

      {{-- COLUMNA PRINCIPAL --}}
      <div class="col-lg-9">

        @if ($errors->any())
          <div class="alert alert-danger mb-3">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif

        <div class="card shadow-none border">
          <div class="card-body">
            <p class="section-label mb-3">Identificación</p>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label class="form-label">Número de Remisión <span class="text-danger">*</span></label>
                <input type="text" name="remision_number"
                  class="form-control fw-semibold @error('remision_number') is-invalid @enderror"
                  value="{{ old('remision_number') }}" placeholder="EJ. REM-2026-001" required autofocus>
                @error('remision_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-3">
                <label class="form-label">Fecha de emisión</label>
                <input type="text" id="emissionDate" name="emission_date" class="form-control"
                  value="{{ old('emission_date') }}" placeholder="DD/MM/AAAA" autocomplete="off">
              </div>
            </div>

            <hr class="my-3">

            <p class="section-label mb-3">Generador</p>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Generador <span class="text-danger">*</span></label>
                <select name="generator_id" id="generatorSelect" class="select2 form-select" required>
                  <option value=""></option>
                  @foreach($generators as $g)
                    <option value="{{ $g->id }}"
                      data-has-sub="{{ $g->has_sub_generators ? '1' : '0' }}"
                      {{ old('generator_id') == $g->id ? 'selected' : '' }}>
                      {{ $g->company_name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6" id="subGeneratorRow" style="display:none">
                <label class="form-label">División</label>
                <select name="sub_generator_id" id="subGeneratorSelect" class="select2 form-select">
                  <option value="">— Seleccionar división —</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        {{-- RETIROS --}}
        <div class="card shadow-none border mt-4">
          <div class="card-body">
            <p class="section-label mb-3">Entradas a vincular</p>
            <select name="withdrawal_ids[]" id="withdrawalsSelect"
              class="form-select" multiple
              placeholder="— Selecciona un generador primero —"
              disabled>
            </select>
            <div class="d-flex justify-content-between mt-2">
              <small class="text-muted">Entradas sin remisión del generador seleccionado. Puedes buscar por folio.</small>
              <small class="fw-semibold">Total: $<span id="totalDisplay">0.00</span></small>
            </div>
          </div>
        </div>

      </div>

      {{-- COLUMNA DERECHA --}}
      <div class="col-lg-3">
        <div class="card shadow-none border">
          <div class="card-body">
            <p class="section-label mb-3">Estado</p>
            <div class="mb-3">
              <label class="form-label">Estatus</label>
              <select name="status" class="form-select">
                <option value="BORRADOR" {{ old('status', 'BORRADOR') === 'BORRADOR' ? 'selected' : '' }}>Borrador</option>
                <option value="ENVIADA"  {{ old('status') === 'ENVIADA'  ? 'selected' : '' }}>Enviada</option>
                <option value="PAGADA"   {{ old('status') === 'PAGADA'   ? 'selected' : '' }}>Pagada</option>
                <option value="CANCELADA"{{ old('status') === 'CANCELADA'? 'selected' : '' }}>Cancelada</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Notas</label>
              <textarea name="notes" class="form-control" rows="4"
                placeholder="Observaciones...">{{ old('notes') }}</textarea>
            </div>
          </div>
          <div class="card-footer bg-transparent">
            <button type="submit" class="btn btn-primary w-100">
              <i class="ti tabler-device-floppy me-1"></i> Guardar Remisión
            </button>
            <a href="{{ route('remisions.index') }}" class="btn btn-outline-secondary w-100 mt-2">Cancelar</a>
          </div>
        </div>
      </div>

    </div>
  </form>
@endsection

@section('page-script')
  <script>var baseUrl = "{{ url('/') }}/";</script>
  @vite(['resources/assets/js/remisions/create.js'])
@endsection
