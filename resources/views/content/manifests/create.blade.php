@extends('layouts/layoutMaster')

@section('title', 'Nuevo Manifiesto - Coprice')

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
    #withdrawalsTable thead th {
      font-size: 0.72rem; font-weight: 600; text-transform: uppercase;
      letter-spacing: 0.05em; color: #6c757d;
      border-bottom: 2px solid #e9ecef; background: transparent;
    }
    #withdrawalsTable td { vertical-align: middle; }
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
      <li class="breadcrumb-item"><a href="{{ route('manifests.index') }}">Manifiestos</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item active">Nuevo</li>
    </ol>
  </nav>

  <form action="{{ route('manifests.store') }}" method="POST" id="manifestForm">
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
                <label class="form-label">Número de Manifiesto <span class="text-danger">*</span></label>
                <input type="text" name="manifest_number"
                  class="form-control fw-semibold @error('manifest_number') is-invalid @enderror"
                  value="{{ old('manifest_number') }}" placeholder="EJ. MAN-2026-001" required autofocus>
                @error('manifest_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
            <p class="section-label mb-3">Retiros a vincular</p>
            <div class="mb-3">
              <label class="form-label">Período</label>
              <div class="input-group">
                <div class="dropdown">
                  <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="rangePresetLabel">Acceso rápido</span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" data-range="week">Esta semana</a></li>
                    <li><a class="dropdown-item" href="#" data-range="fortnight">Esta quincena</a></li>
                    <li><a class="dropdown-item" href="#" data-range="month">Este mes</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-range="custom">Personalizado</a></li>
                  </ul>
                </div>
                <input type="text" id="periodRange" class="form-control" placeholder="Inicio — Fin" autocomplete="off">
              </div>
              <input type="hidden" name="period_start" id="period_start" value="{{ old('period_start') }}">
              <input type="hidden" name="period_end" id="period_end" value="{{ old('period_end') }}">
            </div>
            <select name="withdrawal_ids[]" id="withdrawalsSelect"
              class="form-select" multiple
              placeholder="— Selecciona un generador primero —"
              disabled>
            </select>
            <small class="text-muted mt-1 d-block">Selecciona el generador para cargar sus retiros disponibles. Puedes buscar por folio.</small>
          </div>
        </div>

      </div>

      {{-- COLUMNA DERECHA --}}
      <div class="col-lg-3">
        <div class="card shadow-none border">
          <div class="card-body">
            <p class="section-label mb-3">Estado</p>
            <div class="mb-3">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="generated" id="generated" value="1"
                  {{ old('generated') ? 'checked' : '' }}>
                <label class="form-check-label" for="generated">Generado / Entregado</label>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Notas</label>
              <textarea name="notes" class="form-control" rows="4"
                placeholder="Observaciones...">{{ old('notes') }}</textarea>
            </div>
          </div>
          <div class="card-footer bg-transparent">
            <button type="submit" class="btn btn-primary w-100">
              <i class="ti tabler-device-floppy me-1"></i> Guardar Manifiesto
            </button>
            <a href="{{ route('manifests.index') }}" class="btn btn-outline-secondary w-100 mt-2">Cancelar</a>
          </div>
        </div>
      </div>

    </div>
  </form>
@endsection

@section('page-script')
  <script>var baseUrl = "{{ url('/') }}/";</script>
  @vite(['resources/assets/js/manifests/create.js'])
@endsection
