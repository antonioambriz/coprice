@extends('layouts/layoutMaster')

@section('title', 'Catálogo de Transportistas')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
  <script>
    var baseUrl = "{{ url('/') }}/";
  </script>
  @vite(['resources/assets/js/transporters/index.js'])
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom-icon">
      <li class="breadcrumb-item">
        <a href="{{ route('dashboard-analytics') }}">Inicio</a>
        <i class="breadcrumb-icon icon-base ti tabler-chevron-right align-middle icon-xs mx-2"></i>
      </li>
      <li class="breadcrumb-item">
        Catálogos
        <i class="breadcrumb-icon icon-base ti tabler-chevron-right align-middle icon-xs mx-2"></i>
      </li>
      <li class="breadcrumb-item active">Transportistas</li>
    </ol>
  </nav>

  {{-- Alerta de éxito para acciones rápidas --}}
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card">
    {{-- Encabezado con el botón Añadir integrado --}}
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Listado de Transportistas</h5>
      <a href="{{ route('transporters.create') }}" class="btn btn-primary">
        <i class="ti tabler-plus me-1"></i> Nuevo Transportista
      </a>
    </div>

    <div class="card-datatable text-nowrap">
      <table class="dt-column-search table table-bordered table-responsive table-hover">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nombre / Razón Social</th>
            <th>Autorización</th>
            <th>Contacto</th>
            <th>Email Remisiones</th>
            <th class="text-center">Estatus</th>
            <th class="text-center"><i class="ti tabler-settings ti-sm text-muted"></i> Acciones</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Id</th>
            <th>Nombre / Razón Social</th>
            <th>Autorización</th>
            <th>Contacto</th>
            <th>Email Remisiones</th>
            <th class="text-center">Estatus</th>
            <th class="text-center">Acciones</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  {{-- Toast para notificaciones dinámicas (Borrado, Estatus, etc.) --}}
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
