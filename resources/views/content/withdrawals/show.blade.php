@extends('layouts/layoutMaster')

@section('title', 'Retiro ' . $withdrawal->folio_interno . ' - Coprice')

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom-icon">
      <li class="breadcrumb-item"><a href="{{ route('dashboard-analytics') }}">Inicio</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item"><a href="{{ route('withdrawals.index') }}">Bitácora de Retiros</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item active">{{ $withdrawal->folio_interno }}</li>
    </ol>
  </nav>

  <div class="row g-4">

    {{-- COLUMNA PRINCIPAL --}}
    <div class="col-lg-9">

      {{-- ENCABEZADO --}}
      <div class="card shadow-none border mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
            <div>
              <h4 class="mb-1 fw-semibold">{{ $withdrawal->folio_interno }}</h4>
              <span class="text-muted">
                <i class="ti tabler-calendar me-1"></i>
                {{ $withdrawal->reception_date->format('d/m/Y') }}
                @if($withdrawal->departure_date)
                  <span class="mx-2 text-muted">→</span>
                  Salida: {{ $withdrawal->departure_date->format('d/m/Y H:i') }}
                @endif
              </span>
            </div>
            <div class="d-flex gap-2 align-items-center">
              @if($withdrawal->is_estimated_weight)
                <span class="badge bg-label-warning">Peso Estimado</span>
              @else
                <span class="badge bg-label-success">Peso Real</span>
              @endif
              @php
                $statusClass = match($withdrawal->payment_status) {
                  'PAGADO'    => 'bg-label-success',
                  'CANCELADO' => 'bg-label-danger',
                  default     => 'bg-label-warning',
                };
              @endphp
              <span class="badge {{ $statusClass }}">{{ $withdrawal->payment_status }}</span>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
              <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Generador</p>
              <p class="fw-semibold mb-0">{{ $withdrawal->generator->company_name ?? '—' }}</p>
              @if($withdrawal->subGenerator)
                <small class="text-muted">{{ $withdrawal->subGenerator->name }}</small>
              @endif
            </div>
            <div class="col-sm-6 col-lg-3">
              <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Transportista</p>
              <p class="fw-semibold mb-0">{{ $withdrawal->transporter->company_name ?? '—' }}</p>
            </div>
            <div class="col-sm-6 col-lg-3">
              <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Folio Báscula</p>
              <p class="fw-semibold mb-0">{{ $withdrawal->ticket_externo ?: '—' }}</p>
            </div>
            <div class="col-sm-6 col-lg-3">
              <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Folio Salida</p>
              <p class="fw-semibold mb-0">{{ $withdrawal->folio_salida ?: '—' }}</p>
            </div>
            <div class="col-sm-6 col-lg-3">
              <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Manifiesto</p>
              <p class="fw-semibold mb-0">{{ $withdrawal->manifest->manifest_number ?? 'S/M' }}</p>
            </div>
            <div class="col-sm-6 col-lg-3">
              <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Etapa de Manejo</p>
              <p class="fw-semibold mb-0">{{ $withdrawal->treatment_stage ?: '—' }}</p>
            </div>
            <div class="col-sm-6 col-lg-3">
              <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Destino Final</p>
              <p class="fw-semibold mb-0">{{ $withdrawal->finalDestination?->display_name ?? '—' }}</p>
            </div>
          </div>
        </div>
      </div>

      {{-- OBSERVACIONES --}}
      @if($withdrawal->observaciones)
        <div class="card shadow-none border mb-4">
          <div class="card-body">
            <p class="mb-2" style="font-size:.70rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#a1acb8">Observaciones</p>
            <p class="mb-0 text-body">{{ $withdrawal->observaciones }}</p>
          </div>
        </div>
      @endif

      {{-- RESIDUOS --}}
      <div class="card shadow-none border">
        <div class="card-body pb-0">
          <p class="mb-3" style="font-size:.70rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#a1acb8">Residuos</p>
        </div>
        <div class="table-responsive">
          <table class="table table-sm mb-0">
            <thead>
              <tr>
                <th style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;font-weight:600;border-bottom:2px solid #e9ecef;background:transparent;width:35%">Residuo</th>
                <th style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;font-weight:600;border-bottom:2px solid #e9ecef;background:transparent">Estado Físico</th>
                <th style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;font-weight:600;border-bottom:2px solid #e9ecef;background:transparent">Clasif.</th>
                <th style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;font-weight:600;border-bottom:2px solid #e9ecef;background:transparent">Cantidad</th>
                <th style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;font-weight:600;border-bottom:2px solid #e9ecef;background:transparent">Cap. Recipiente</th>
              </tr>
            </thead>
            <tbody>
              @forelse($withdrawal->items as $item)
                <tr>
                  <td>{{ $item->waste->description ?? '—' }}</td>
                  <td>{{ $item->physical_state ?: '—' }}</td>
                  <td>{{ $item->packaging_type ?: '—' }}</td>
                  <td>{{ number_format($item->quantity, 3) }} {{ $item->unit }}</td>
                  <td>{{ $item->container_capacity ?: '—' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-3">Sin residuos registrados</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>

    {{-- COLUMNA DERECHA --}}
    <div class="col-lg-3">
      <div class="card shadow-none border">
        <div class="card-body">
          <p class="mb-3" style="font-size:.70rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#a1acb8">Acciones</p>
          <a href="{{ route('withdrawals.edit', $withdrawal) }}" class="btn btn-primary w-100 mb-2">
            <i class="ti tabler-edit me-1"></i> Editar
          </a>
          <a href="{{ route('withdrawals.index') }}" class="btn btn-outline-secondary w-100">
            <i class="ti tabler-arrow-left me-1"></i> Regresar
          </a>
        </div>
        <div class="card-footer bg-transparent">
          <p class="mb-2" style="font-size:.70rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#a1acb8">Info</p>
          <small class="text-muted d-block">Creado: {{ $withdrawal->created_at->format('d/m/Y H:i') }}</small>
          <small class="text-muted d-block">Actualizado: {{ $withdrawal->updated_at->format('d/m/Y H:i') }}</small>
        </div>
      </div>
    </div>

  </div>
@endsection
