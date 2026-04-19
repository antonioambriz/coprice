@extends('layouts/layoutMaster')

@section('title', 'Nuevo Residuo - Coprice')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/tagify/tagify.scss', 'resources/assets/vendor/libs/quill/typography.scss', 'resources/assets/vendor/libs/quill/katex.scss', 'resources/assets/vendor/libs/quill/editor.scss'])
  <style>
    .tagify {
      --tags-border-color: #dbdade;
      padding: 2px 6px !important;
    }

    #full-editor .ql-editor {
      min-height: 150px !important;
    }
  </style>
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/tagify/tagify.js', 'resources/assets/vendor/libs/quill/katex.js', 'resources/assets/vendor/libs/quill/quill.js'])
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
      <li class="breadcrumb-item active">Alta de Residuo</li>
    </ol>
  </nav>

  <div class="row justify-content-center">
    <div class="col-lg-10">
      <form action="{{ route('wastes.store') }}" method="POST" id="form-create-waste" novalidate>
        @csrf
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center border-bottom mb-4">
            <h5 class="mb-0">Información del Residuo</h5>
            <i class="ti tabler-flask text-primary ti-md"></i>
          </div>

          <div class="card-body">
            <div class="row g-6">

              {{-- Descripción --}}
              <div class="col-md-6">
                <label class="form-label" for="description">Descripción (MAYÚSCULAS)</label>
                <input type="text" id="description" name="description"
                  class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}"
                  placeholder="EJ. LODO FISICOQUÍMICO" style="text-transform: uppercase;" />
              </div>

              {{-- Código y Unidad --}}
              <div class="col-md-4">
                <label class="form-label" for="waste_code">Código de Residuo</label>
                <input type="text" id="waste_code" name="waste_code" class="form-control"
                  value="{{ old('waste_code') }}" placeholder="Opcional">
              </div>

              <div class="col-md-4">
                <label class="form-label" for="unit">Unidad de Medida</label>
                <select id="unit" name="unit" class="select2 form-select">
                  <option value="KG">KILOGRAMOS (KG)</option>
                  <option value="LT">LITROS (LT)</option>
                  <option value="TON">TONELADAS (TON)</option>
                </select>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="default_price">Precio Base Sugerido</label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" step="0.01" id="default_price" name="default_price" class="form-control"
                    value="{{ old('default_price', '0.00') }}">
                </div>
              </div>

              {{-- Switches y Tags --}}
              <div class="col-md-6">
                <div class="form-check form-switch mt-md-8">
                  <input class="form-check-input" type="checkbox" name="is_hazardous" id="is_hazardous" value="1">
                  <label class="form-check-label fw-bold" for="is_hazardous">¿Es Residuo Peligroso?</label>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="tags">Etiquetas</label>
                <input id="tags" name="tags" class="form-control" placeholder="Escribe y pulsa Enter" />
              </div>

              {{-- Quill Editor --}}
              <div class="col-12">
                <label class="form-label">Especificaciones de Manejo</label>
                <div id="full-editor">{!! old('notes') !!}</div>
                <input type="hidden" name="notes" id="notes">
              </div>
            </div>
          </div>

          {{-- Botonera interna ajustada --}}
          <div class="card-footer border-top bg-transparent py-4">
            <div class="d-flex justify-content-end align-items-center gap-3">
              <a href="{{ route('wastes.index') }}" class="btn btn-label-secondary waves-effect">
                Cancelar
              </a>
              <button type="submit" class="btn btn-primary shadow-none waves-effect waves-light">
                <i class="ti tabler-device-floppy me-1"></i> Registrar Residuo
              </button>
            </div>
          </div>
        </div> {{-- Cierre del Card --}}
    </div>
    </form>
  </div>
  </div>
@endsection
