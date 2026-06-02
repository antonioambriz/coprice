@extends('layouts/layoutMaster')

@section('title', 'Permisos de Acceso - Coprice')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
<script>
var baseUrl = "{{ url('/') }}/";
</script>
@vite(['resources/assets/js/configuration/permissions.js'])
@endsection

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom-icon">
    <li class="breadcrumb-item"><a href="{{ route('dashboard-analytics') }}">Inicio</a>
      <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
    <li class="breadcrumb-item active">Permisos de Acceso</li>
  </ol>
</nav>

<div class="row g-4">
  <div class="col-12">
    <div class="card">

      {{-- Header --}}
      <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2 pb-0">
        <div>
          <h5 class="mb-1">
            <i class="ti tabler-shield-lock me-2 text-primary"></i>Permisos de Acceso por Rol
          </h5>
          <p class="text-muted mb-3" style="font-size:.875rem">
            Configura qué páginas puede ver cada rol. El rol <strong>SUPERADMIN</strong> siempre tiene acceso total.
            Las filas bloqueadas (<i class="ti tabler-lock icon-xs"></i>) son exclusivas de SUPERADMIN.
          </p>
        </div>
        <button id="btn-save" class="btn btn-primary waves-effect waves-light mb-3">
          <i class="ti tabler-device-floppy me-1"></i>Guardar cambios
        </button>
      </div>

      {{-- Tabla matriz --}}
      <div class="card-body p-0">
        <div class="table-responsive px-4 pb-4">
          <table class="table table-bordered table-hover align-middle mb-0" id="permissions-table">
            <thead class="table-light">
              <tr>
                <th style="min-width:220px">Página / Módulo</th>
                {{-- Columna SUPERADMIN siempre visible pero no editable --}}
                <th class="text-center" style="min-width:120px">
                  <span class="badge bg-label-danger px-2 py-1">SUPERADMIN</span>
                </th>
                @foreach($roles as $roleKey => $roleLabel)
                <th class="text-center" style="min-width:120px">
                  <span class="badge bg-label-primary px-2 py-1">{{ $roleLabel }}</span>
                </th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @foreach($pages as $pageKey => $page)
              @php $isSystem = $page['system']; @endphp
              <tr class="{{ $isSystem ? 'table-light text-muted' : '' }}">

                {{-- Nombre de página --}}
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <i class="ti {{ $page['icon'] }} text-{{ $isSystem ? 'secondary' : 'primary' }}" style="font-size:1.1rem"></i>
                    <span class="{{ $isSystem ? 'text-muted' : '' }}">{{ $page['label'] }}</span>
                    @if($isSystem)
                    <i class="ti tabler-lock text-secondary ms-1" title="Solo SUPERADMIN"></i>
                    @endif
                  </div>
                </td>

                {{-- SUPERADMIN: siempre marcado --}}
                <td class="text-center">
                  <div class="form-check d-flex justify-content-center">
                    <input class="form-check-input" type="checkbox" checked disabled
                           style="width:1.2rem;height:1.2rem;cursor:not-allowed;opacity:.85">
                  </div>
                </td>

                {{-- Roles configurables --}}
                @foreach($roles as $roleKey => $roleLabel)
                <td class="text-center">
                  @if($isSystem)
                  {{-- Páginas de sistema: siempre bloqueadas para otros roles --}}
                  <div class="form-check d-flex justify-content-center">
                    <input class="form-check-input" type="checkbox" disabled
                           style="width:1.2rem;height:1.2rem;cursor:not-allowed;opacity:.4">
                  </div>
                  @else
                  <div class="form-check d-flex justify-content-center">
                    <input class="form-check-input permission-checkbox"
                           type="checkbox"
                           name="permissions[{{ $roleKey }}][{{ $pageKey }}]"
                           value="1"
                           style="width:1.2rem;height:1.2rem;cursor:pointer"
                           {{ !empty($permissions[$roleKey][$pageKey]) ? 'checked' : '' }}>
                  </div>
                  @endif
                </td>
                @endforeach

              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      {{-- Leyenda --}}
      <div class="card-footer border-top-0 pt-0 px-4 pb-3">
        <small class="text-muted">
          <i class="ti tabler-info-circle me-1"></i>
          Los cambios aplican en el siguiente inicio de sesión del usuario afectado.
        </small>
      </div>

    </div>
  </div>
</div>
@endsection
