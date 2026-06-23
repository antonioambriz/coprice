@extends('layouts/layoutMaster')

@section('title', 'Bitácora de Retiros - Coprice')

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
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  ])
@endsection

@section('page-script')
  <script>var baseUrl = "{{ url('/') }}/";</script>
  @vite(['resources/assets/js/withdrawals/index.js'])
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom-icon">
      <li class="breadcrumb-item"><a href="{{ route('dashboard-analytics') }}">Inicio</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item active">Bitácora de Retiros</li>
    </ol>
  </nav>

  <div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Bitácora de Retiros</h5>
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalSama">
          <i class="ti tabler-file-certificate me-1"></i> Reporte SAMA
        </button>
        <a href="{{ route('withdrawals.exportExcel') }}" class="btn btn-success">
          <i class="ti tabler-file-spreadsheet me-1"></i> Exportar Bitácora
        </a>
      </div>
    </div>
    <div class="card-datatable text-nowrap">
      <table class="dt-column-search table table-bordered table-hover">
        <thead>
          <tr>
            <th>Id</th>
            <th>Fecha</th>
            <th>Folio</th>
            <th>Generador</th>
            <th>Transportista</th>
            <th>Manifiesto</th>
            <th class="text-center">Estatus</th>
            <th>Usuario</th>
            <th class="text-center"><i class="ti tabler-settings ti-sm text-muted"></i></th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Id</th>
            <th>Fecha</th>
            <th>Folio</th>
            <th>Generador</th>
            <th>Transportista</th>
            <th>Manifiesto</th>
            <th class="text-center">Estatus</th>
            <th>Usuario</th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  {{-- Modal SAMA --}}
  <div class="modal fade" id="modalSama" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reporte SAMA</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('withdrawals.exportSama') }}" method="GET">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Fecha inicio</label>
              <input type="date" name="from" class="form-control" required>
            </div>
            <div class="mb-0">
              <label class="form-label">Fecha fin</label>
              <input type="date" name="to" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success btn-sm">
              <i class="ti tabler-download me-1"></i> Descargar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Toast Global --}}
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="globalToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="ti tabler-check text-success me-2"></i>
        <strong class="me-auto">Coprice System</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
      </div>
      <div class="toast-body" id="globalToastBody"></div>
    </div>
  </div>
@endsection
