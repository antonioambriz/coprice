@extends('layouts/layoutMaster')

@section('title', 'Generadores - Catálogo')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
  {{-- Nota: Crearemos este archivo js a continuación para manejar la tabla --}}
  @vite(['resources/assets/js/generators/index.js'])
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
      <li class="breadcrumb-item active">Generadores</li>
    </ol>
  </nav>

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Listado de Generadores</h5>
      <a href="{{ route('generators.create') }}" class="btn btn-primary">
        <i class="ti tabler-plus me-1"></i> Nuevo Generador
      </a>
    </div>
    <div class="card-datatable text-nowrap">
      <table class="dt-column-search table table-bordered table-responsive table-hover">
        <thead>
          <tr>
            <th>Id</th>
            <th>Empresa / Razón Social</th>
            <th>Autorización</th>
            <th>Dirección</th>
            <th class="text-center">Estatus</th>
            <th class="text-center"><i class="ti tabler-settings ti-sm text-muted"></i> Acciones</th>
          </tr>
        </thead>
        {{-- Footer para los inputs de búsqueda por columna --}}
        <tfoot>
          <tr>
            <th>Id</th>
            <th>Empresa / Razón Social</th>
            <th>Autorización</th>
            <th>Dirección</th>
            <th class="text-center">Estatus</th>
            <th class="text-center">Acciones</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
@endsection
