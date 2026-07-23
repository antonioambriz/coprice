@extends('layouts/layoutMaster')

@section('title', 'Manifiestos - Coprice')

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
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  ])
@endsection

@section('page-script')
  <script>var baseUrl = "{{ url('/') }}/";</script>
  @vite(['resources/assets/js/manifests/index.js'])
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom-icon">
      <li class="breadcrumb-item"><a href="{{ route('dashboard-analytics') }}">Inicio</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item active">Manifiestos</li>
    </ol>
  </nav>

  <div class="card">
    <div class="card-datatable text-nowrap">
      <table class="dt-column-search table table-bordered table-hover">
        <thead>
          <tr>
            <th>Id</th>
            <th>Núm. Manifiesto</th>
            <th>Generador</th>
            <th>División</th>
            <th>Período</th>
            <th class="text-center">Entradas</th>
            <th class="text-center">Generado</th>
            <th class="text-center"><i class="ti tabler-settings ti-sm text-muted"></i></th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Id</th>
            <th>Núm. Manifiesto</th>
            <th>Generador</th>
            <th>División</th>
            <th>Período</th>
            <th class="text-center">Entradas</th>
            <th class="text-center">Generado</th>
            <th></th>
          </tr>
        </tfoot>
      </table>
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
