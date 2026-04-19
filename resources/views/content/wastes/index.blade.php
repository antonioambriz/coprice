@extends('layouts/layoutMaster')

@section('title', 'Catálogo de Residuos')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
  @vite(['resources/assets/js/wastes/index.js'])
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom-icon">
      <li class="breadcrumb-item">
        <a href="{{ route('dashboard-analytics') }}">Inicio</a>
        <i class="breadcrumb-icon icon-base ti tabler-chevron-right align-middle icon-xs"></i>
      </li>
      <li class="breadcrumb-item active">
        <a href="javascript:void(0);">Catálogo de Residuos</a>
      </li>
    </ol>
  </nav>

  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="dt-column-search table table-bordered">
        <thead>
          <tr>
            <th>Id</th>
            <th>Descripción</th>
            <th>Código</th>
            <th class="text-center">Unidad</th>
            <th class="text-center">Precio Base</th>
            <th class="text-center">Peligroso</th>
            <th class="text-center"><i class="ti tabler-settings ti-sm text-muted" style="font-size: 20px;"></i></th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Id</th>
            <th>Descripción</th>
            <th>Código</th>
            <th class="text-center">Unidad</th>
            <th class="text-center">Precio Base</th>
            <th class="text-center">Peligroso</th>
            <th class="text-center"><i class="ti tabler-settings ti-sm text-muted" style="font-size: 20px;"></i></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  {{-- Toast Global --}}
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="globalToast" class="toast animate__animated" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="ti tabler-check text-success me-2"></i>
        <strong class="me-auto">Coprice</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body" id="globalToastBody"></div>
    </div>
  </div>
@endsection
