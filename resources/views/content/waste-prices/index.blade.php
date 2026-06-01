@extends('layouts/layoutMaster')

@section('title', 'Matriz de Precios - ' . $subjectName)

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom-icon">
      <li class="breadcrumb-item"><a href="{{ route('dashboard-analytics') }}">Inicio</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item"><a href="{{ route('generators.index') }}">Generadores</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item"><a href="{{ $backRoute }}">Editar</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item active">Precios por Residuo</li>
    </ol>
  </nav>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>
        <h5 class="card-title mb-0">Precios Acordados por Residuo</h5>
        <small class="text-muted">{{ $subjectName }}</small>
      </div>
      <a href="{{ $backRoute }}" class="btn btn-outline-secondary btn-sm">
        <i class="ti tabler-arrow-left me-1"></i> Regresar
      </a>
    </div>
    <div class="card-body">
      <p class="text-muted mb-4">
        Captura el precio acordado por tonelada para cada tipo de residuo.
        Deja en blanco los residuos que no apliquen a este transportista.
      </p>

      <form action="{{ $saveRoute }}" method="POST">
        @csrf
        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Residuo</th>
                <th>Clave</th>
                <th style="width: 180px">Precio por ton ($)</th>
              </tr>
            </thead>
            <tbody>
              @foreach($wastes as $waste)
                <tr>
                  <td>{{ $waste->description }}</td>
                  <td><small class="text-muted">{{ $waste->waste_code ?? '—' }}</small></td>
                  <td>
                    <div class="input-group input-group-sm">
                      <span class="input-group-text">$</span>
                      <input
                        type="number"
                        step="0.0001"
                        min="0"
                        name="prices[{{ $waste->id }}]"
                        class="form-control"
                        placeholder="{{ number_format($waste->default_price ?? 0, 4) }}"
                        value="{{ isset($prices[$waste->id]) ? number_format($prices[$waste->id], 4, '.', '') : '' }}"
                      >
                    </div>
                    @if($waste->default_price)
                      <small class="text-muted">Precio base: ${{ number_format($waste->default_price, 4) }}/ton</small>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="mt-4 text-end">
          <a href="{{ $backRoute }}" class="btn btn-outline-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i> Guardar Precios
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
