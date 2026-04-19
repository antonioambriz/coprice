@extends('layouts/layoutMaster')

@section('title', 'Nuevo Retiro - Coprice')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
  ])
  <style>
    .table-responsive { overflow: visible !important; }
    #subGeneratorRow  { display: none; }
    .section-label {
      font-size: 0.70rem;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #a1acb8;
    }
    #wastesTable thead th {
      font-size: 0.72rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #6c757d;
      border-bottom: 2px solid #e9ecef;
      background: transparent;
    }
    #wastesTable td { vertical-align: middle; }
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
      <li class="breadcrumb-item"><a href="{{ route('withdrawals.index') }}">Bitácora de Retiros</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item active">Nuevo Retiro</li>
    </ol>
  </nav>

  <form action="{{ route('withdrawals.store') }}" method="POST" id="withdrawalForm">
    @csrf
    <div class="row g-4">

      {{-- COLUMNA PRINCIPAL --}}
      <div class="col-lg-9">

        {{-- ERRORES --}}
        @if ($errors->any())
          <div class="alert alert-danger mb-3">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif


        <div class="card shadow-none border">
          <div class="card-body">

            {{-- IDENTIFICACIÓN --}}
            <p class="section-label mb-3">Identificación</p>
            <div class="row g-3 mb-4">
              <div class="col-md-3">
                <label class="form-label">Folio</label>
                <input type="text" name="folio_interno" class="form-control fw-semibold text-primary"
                  value="{{ $nuevoFolio }}" readonly>
              </div>
              <div class="col-md-3">
                <label class="form-label">Fecha Recepción</label>
                <input type="text" name="reception_date" id="receptionDate" class="form-control flatpickr"
                  value="{{ now()->format('Y-m-d H:i') }}">
              </div>
              <div class="col-md-3">
                <label class="form-label">Folio Báscula</label>
                <input type="text" name="ticket_externo" class="form-control" placeholder="Ej. 30005">
              </div>
              <div class="col-md-3">
                <label class="form-label">Folio Salida</label>
                <input type="text" name="folio_salida" class="form-control" placeholder="Folio de salida"
                  value="{{ old('folio_salida') }}">
              </div>
            </div>
            <div class="row g-3 mb-4">
              <div class="col-md-3"></div>
              <div class="col-md-3 d-flex flex-column justify-content-end">
                <div class="form-check form-switch mb-1">
                  <input class="form-check-input" type="checkbox" name="requires_transport_equipment"
                    id="requiresTransportEquipment" value="1" {{ old('requires_transport_equipment') ? 'checked' : '' }}>
                  <label class="form-check-label text-muted" for="requiresTransportEquipment">Requiere Equipo</label>
                </div>
              </div>
              <div class="col-md-3 d-flex flex-column justify-content-end">
                <div class="form-check form-switch mb-1">
                  <input class="form-check-input" type="checkbox" name="requires_manifest"
                    id="requiresManifest" value="1" {{ old('requires_manifest') ? 'checked' : '' }}>
                  <label class="form-check-label text-muted" for="requiresManifest">Requiere Manifiesto</label>
                </div>
              </div>
              <div class="col-md-3 d-flex flex-column justify-content-end">
                <div class="form-check form-switch mb-1">
                  <input class="form-check-input" type="checkbox" name="is_estimated_weight"
                    id="isEstimatedWeight" value="1" {{ old('is_estimated_weight') ? 'checked' : '' }}>
                  <label class="form-check-label text-muted" for="isEstimatedWeight">Peso Estimado</label>
                </div>
              </div>
            </div>

            <hr class="my-3">

            {{-- PARTES --}}
            <p class="section-label mb-3">Partes</p>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Generador</label>
                <select name="generator_id" id="generatorSelect" class="select2 form-select" required>
                  <option value=""></option>
                  @foreach ($generators as $g)
                    <option value="{{ $g->id }}"
                      data-has-sub="{{ $g->has_sub_generators ? '1' : '0' }}"
                      data-preferred-transporter="{{ $g->preferred_transporter_id }}"
                      {{ old('generator_id') == $g->id ? 'selected' : '' }}>
                      {{ $g->company_name }}
                    </option>
                  @endforeach
                </select>
                <div id="subGeneratorRow" class="mt-2">
                  <label class="form-label">División</label>
                  <select name="sub_generator_id" id="subGeneratorSelect" class="select2 form-select">
                    <option value="">— Seleccionar división —</option>
                  </select>
                  <small id="assumedWeightInfo" class="text-muted mt-1 d-none">
                    <i class="ti tabler-info-circle me-1"></i>Peso asumido: <strong id="assumedWeightValue"></strong> kg
                  </small>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Transportista</label>
                <select name="transporter_id" id="transporterSelect" class="select2 form-select" required>
                  <option value=""></option>
                  @foreach ($transporters as $t)
                    <option value="{{ $t->id }}"
                      {{ $t->company_name === 'COPRICE' ? 'selected' : '' }}
                      {{ old('transporter_id') == $t->id ? 'selected' : '' }}>
                      {{ $t->company_name }}
                    </option>
                  @endforeach
                </select>
                <div id="transportEquipmentRow" class="mt-2" style="display:none;">
                  <label class="form-label">Equipo de Transporte</label>
                  <select name="transport_equipment_id" id="transportEquipmentSelect" class="form-select">
                    <option value="">— Seleccionar equipo —</option>
                  </select>
                </div>
              </div>
            </div>


          </div>
        </div>

        {{-- RESIDUOS --}}
        <div class="card shadow-none border mt-4">
          <div class="card-body pb-0">
            <p class="section-label mb-3">Residuos</p>
          </div>
          <div class="table-responsive">
            <table class="table table-sm mb-0" id="wastesTable">
              <thead>
                <tr>
                  <th style="width:28%">Residuo</th>
                  <th>Estado Físico</th>
                  <th>Clasif.</th>
                  <th>Cantidad / Unidad</th>
                  <th>Tipo Envasado</th>
                  <th>Cap. Recipiente</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="wastesBody"></tbody>
            </table>
          </div>
          <div class="card-footer bg-transparent border-top-0 pt-0">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="addRow">
              <i class="ti tabler-plus me-1"></i> Añadir Residuo
            </button>
          </div>
        </div>

      </div>

      {{-- COLUMNA DERECHA --}}
      <div class="col-lg-3">
        <div class="card shadow-none border">
          <div class="card-body">
            <p class="section-label mb-3">Detalles</p>
            <div class="mb-3">
              <label class="form-label">Fecha Salida Generador</label>
              <input type="text" name="departure_date" class="form-control flatpickr">
            </div>
            <div class="mb-3">
              <label class="form-label">Etapa de manejo integral</label>
              <select name="treatment_stage" class="form-select">
                <option value="TRATAMIENTO"    {{ old('treatment_stage', 'TRATAMIENTO') === 'TRATAMIENTO'    ? 'selected' : '' }}>TRATAMIENTO</option>
                <option value="TRANSPORTE"     {{ old('treatment_stage') === 'TRANSPORTE'     ? 'selected' : '' }}>TRANSPORTE</option>
                <option value="CONFINAMIENTO"  {{ old('treatment_stage') === 'CONFINAMIENTO'  ? 'selected' : '' }}>CONFINAMIENTO</option>
                <option value="RECICLAJE"      {{ old('treatment_stage') === 'RECICLAJE'      ? 'selected' : '' }}>RECICLAJE</option>
                <option value="REUSO"          {{ old('treatment_stage') === 'REUSO'          ? 'selected' : '' }}>REUSO</option>
                <option value="COPROCESAMIENTO"{{ old('treatment_stage') === 'COPROCESAMIENTO'? 'selected' : '' }}>COPROCESAMIENTO</option>
                <option value="INCINERACIÓN"   {{ old('treatment_stage') === 'INCINERACIÓN'   ? 'selected' : '' }}>INCINERACIÓN</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Destino Final</label>
              <select name="final_destination_id" class="form-select">
                <option value="">— Sin especificar —</option>
                @foreach($finalDestinations as $fd)
                  <option value="{{ $fd->id }}" {{ old('final_destination_id') == $fd->id ? 'selected' : '' }}>
                    {{ $fd->display_name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Observaciones</label>
              <textarea name="observaciones" class="form-control" rows="3" placeholder="Notas u observaciones...">{{ old('observaciones') }}</textarea>
            </div>
            <input type="hidden" name="payment_status" value="PENDIENTE">
          </div>
          <div class="card-footer bg-transparent">
            <button type="submit" class="btn btn-primary w-100">
              <i class="ti tabler-device-floppy me-1"></i> Guardar Retiro
            </button>
            <a href="{{ route('withdrawals.index') }}" class="btn btn-outline-secondary w-100 mt-2">
              Cancelar
            </a>
          </div>
        </div>
      </div>

    </div>
  </form>

  {{-- TEMPLATE FILA RESIDUO --}}
  <script type="text/template" id="wasteRowTemplate">
    <tr>
      <td>
        <select name="items[__INDEX__][waste_id]" class="select2-dynamic form-select form-select-sm" required>
          @foreach($wastes as $w)
            <option value="{{ $w->id }}">{{ $w->description }}</option>
          @endforeach
        </select>
      </td>
      <td>
        <select name="items[__INDEX__][physical_state]" class="form-select form-select-sm">
          <option value="Sólido">Sólido</option>
          <option value="Líquido">Líquido</option>
          <option value="Semisólido">Semisólido</option>
        </select>
      </td>
      <td>
        <select name="items[__INDEX__][packaging_type]" class="form-select form-select-sm">
          <option value="RR">RR</option>
          <option value="RNV">RNV</option>
        </select>
      </td>
      <td>
        <div class="input-group input-group-sm">
          <input type="number" step="0.001" name="items[__INDEX__][quantity]" class="form-control" placeholder="0.000" required>
          <select name="items[__INDEX__][unit]" class="form-select" style="max-width:62px;">
            <option value="KG">KG</option>
            <option value="TON">TON</option>
            <option value="LT">LT</option>
          </select>
        </div>
      </td>
      <td>
        <select name="items[__INDEX__][container_type]" class="form-select form-select-sm">
          <option value="">—</option>
          <option value="VACTOR">VACTOR</option>
          <option value="TAMBO">TAMBO</option>
          <option value="CONTENEDOR">CONTENEDOR</option>
          <option value="TANQUE">TANQUE</option>
          <option value="COSTAL">COSTAL</option>
          <option value="CAJA">CAJA</option>
          <option value="ESTIBA">ESTIBA</option>
          <option value="TOLVA">TOLVA</option>
          <option value="OTRO">OTRO</option>
        </select>
      </td>
      <td>
        <div class="input-group input-group-sm">
          <input type="number" name="items[__INDEX__][container_capacity]" class="form-control" placeholder="Cap.">
          <select name="items[__INDEX__][container_unit]" class="form-select" style="max-width:62px;">
            <option value="LT">LT</option>
            <option value="KG">KG</option>
            <option value="M3">M3</option>
          </select>
        </div>
      </td>
      <td class="text-center">
        <button type="button" class="btn btn-sm btn-icon btn-text-secondary btn-remove">
          <i class="ti tabler-trash"></i>
        </button>
      </td>
    </tr>
  </script>
@endsection

@section('page-script')
  <script>
    var baseUrl = "{{ url('/') }}/";
  </script>
  @vite(['resources/assets/js/withdrawals/create.js'])
@endsection
