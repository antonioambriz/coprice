@extends('layouts/layoutMaster')

@section('title', 'Reporte de Retiros - Coprice')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    'resources/assets/vendor/libs/select2/select2.js',
  ])
@endsection

@section('page-script')
<script>var baseUrl = "{{ url('/') }}/";</script>
@vite(['resources/assets/js/reports/withdrawals.js'])
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom-icon">
    <li class="breadcrumb-item"><a href="{{ route('dashboard-analytics') }}">Inicio</a>
      <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
    <li class="breadcrumb-item active">Reporte de Retiros</li>
  </ol>
</nav>

{{-- ═══ FILTROS ═══ --}}
<div class="card mb-4">
  <div class="card-header">
    <h5 class="mb-0"><i class="ti tabler-filter me-2 text-primary"></i>Filtros del Reporte</h5>
  </div>
  <div class="card-body">
    <form id="report-form" method="GET" action="{{ route('reports.withdrawals') }}">
      <div class="row g-3">

        {{-- Rango de fechas --}}
        <div class="col-12 col-md-3">
          <label class="form-label">Fecha desde</label>
          <input type="text" id="date_from" name="date_from" class="form-control flatpickr-date"
                 placeholder="dd/mm/aaaa" autocomplete="off"
                 value="{{ request('date_from') }}">
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label">Fecha hasta</label>
          <input type="text" id="date_to" name="date_to" class="form-control flatpickr-date"
                 placeholder="dd/mm/aaaa" autocomplete="off"
                 value="{{ request('date_to') }}">
        </div>

        {{-- Generador --}}
        <div class="col-12 col-md-3">
          <label class="form-label">Generador</label>
          <select name="generator_id" id="generator_id" class="form-select select2">
            <option value="">— Todos —</option>
            @foreach($generators as $g)
            <option value="{{ $g->id }}" {{ request('generator_id') == $g->id ? 'selected' : '' }}>
              {{ $g->company_name }}
            </option>
            @endforeach
          </select>
        </div>

        {{-- Transportista --}}
        <div class="col-12 col-md-3">
          <label class="form-label">Transportista</label>
          <select name="transporter_id" id="transporter_id" class="form-select select2">
            <option value="">— Todos —</option>
            @foreach($transporters as $t)
            <option value="{{ $t->id }}" {{ request('transporter_id') == $t->id ? 'selected' : '' }}>
              {{ $t->company_name }}
            </option>
            @endforeach
          </select>
        </div>

        {{-- Estatus de pago --}}
        <div class="col-12 col-md-6">
          <label class="form-label d-block">Estatus de pago</label>
          <div class="d-flex flex-wrap gap-3 mt-1">
            @foreach(['PENDIENTE' => 'warning', 'PAGADO' => 'success', 'CANCELADO' => 'danger'] as $status => $color)
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="payment_status[]"
                     id="status_{{ $status }}" value="{{ $status }}"
                     {{ in_array($status, (array) request('payment_status', ['PENDIENTE','PAGADO','CANCELADO'])) ? 'checked' : '' }}>
              <label class="form-check-label" for="status_{{ $status }}">
                <span class="badge bg-label-{{ $color }}">{{ $status }}</span>
              </label>
            </div>
            @endforeach
          </div>
        </div>

        {{-- Botones --}}
        <div class="col-12 col-md-6 d-flex align-items-end justify-content-end gap-2">
          <a href="{{ route('reports.withdrawals') }}" class="btn btn-label-secondary">
            <i class="ti tabler-refresh me-1"></i>Limpiar
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-search me-1"></i>Generar reporte
          </button>
        </div>

      </div>
    </form>
  </div>
</div>

{{-- ═══ RESULTADOS ═══ --}}
@if($withdrawals !== null)
<div class="card">
  <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h5 class="mb-0">
        <i class="ti tabler-table me-2 text-success"></i>Resultados
        <span class="badge bg-label-primary ms-2">{{ $withdrawals->count() }} retiros</span>
      </h5>
      @if($withdrawals->isNotEmpty())
      <small class="text-muted">
        Peso total:
        <strong>{{ number_format($withdrawals->sum(fn($w) => $w->items->sum('quantity')), 2) }}</strong>
        unidades
      </small>
      @endif
    </div>

    @if($withdrawals->isNotEmpty())
    <div class="d-flex gap-2">
      {{-- Botón Excel --}}
      <form method="GET" action="{{ route('reports.withdrawals.excel') }}" class="d-inline">
        @foreach(request()->except('_token') as $key => $value)
          @if(is_array($value))
            @foreach($value as $v)
            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
            @endforeach
          @else
          <input type="hidden" name="{{ $key }}" value="{{ $value }}">
          @endif
        @endforeach
        <button type="submit" class="btn btn-success waves-effect waves-light">
          <i class="ti tabler-file-spreadsheet me-1"></i>Excel
        </button>
      </form>

      {{-- Botón PDF --}}
      <form method="GET" action="{{ route('reports.withdrawals.pdf') }}" class="d-inline">
        @foreach(request()->except('_token') as $key => $value)
          @if(is_array($value))
            @foreach($value as $v)
            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
            @endforeach
          @else
          <input type="hidden" name="{{ $key }}" value="{{ $value }}">
          @endif
        @endforeach
        <button type="submit" class="btn btn-danger waves-effect waves-light">
          <i class="ti tabler-file-type-pdf me-1"></i>PDF
        </button>
      </form>
    </div>
    @endif
  </div>

  <div class="card-body p-0">
    @if($withdrawals->isEmpty())
    <div class="text-center py-5">
      <i class="ti tabler-mood-empty" style="font-size:3rem;color:#ccc"></i>
      <p class="text-muted mt-2 mb-0">No se encontraron retiros con los filtros seleccionados.</p>
    </div>
    @else
    <div class="table-responsive px-3 pb-3">
      <table class="table table-hover align-middle table-sm mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Folio</th>
            <th>Fecha</th>
            <th>Generador</th>
            <th>Transportista</th>
            <th>Residuos</th>
            <th class="text-end">Peso</th>
            <th class="text-center">Peso</th>
            <th class="text-center">Manifiesto</th>
            <th class="text-center">Estatus</th>
          </tr>
        </thead>
        <tbody>
          @foreach($withdrawals as $w)
          @php
            $totalKg = $w->items->sum('quantity');
            $unit    = $w->items->first()?->unit ?? '';
          @endphp
          <tr>
            <td>
              <div class="d-flex align-items-center ps-2 border-status-{{ $w->payment_status === 'PAGADO' ? 'active' : 'inactive' }}">
                <span class="text-muted fw-bold" style="font-size:.8rem">#{{ $w->id }}</span>
              </div>
            </td>
            <td>
              <a href="{{ route('withdrawals.show', $w) }}" class="fw-semibold text-primary text-decoration-none">
                {{ $w->folio_interno }}
              </a>
            </td>
            <td class="text-nowrap">{{ $w->reception_date?->format('d/m/Y') }}</td>
            <td>
              <div>{{ $w->generator?->company_name ?? '—' }}</div>
              @if($w->subGenerator)
              <small class="text-muted">{{ $w->subGenerator->name }}</small>
              @endif
            </td>
            <td>{{ $w->transporter?->company_name ?? '—' }}</td>
            <td>
              @foreach($w->items as $item)
              <div style="font-size:.82rem">{{ $item->waste?->description ?? '—' }}</div>
              @endforeach
            </td>
            <td class="text-end text-nowrap">
              <strong>{{ number_format($totalKg, 2) }}</strong>
              <span class="text-muted">{{ $unit }}</span>
            </td>
            <td class="text-center">
              @if($w->is_estimated_weight)
              <span class="badge bg-label-warning">Estimado</span>
              @else
              <span class="badge bg-label-success">Real</span>
              @endif
            </td>
            <td class="text-center">
              {{ $w->manifest?->manifest_number ?? '—' }}
            </td>
            <td class="text-center">
              @php
                $map = ['PENDIENTE'=>'warning','PAGADO'=>'success','CANCELADO'=>'danger'];
                $cls = $map[$w->payment_status] ?? 'secondary';
              @endphp
              <span class="badge bg-label-{{ $cls }}">{{ $w->payment_status }}</span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>
</div>
@endif

@endsection
