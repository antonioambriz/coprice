@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$roleLabels = [
    'SUPERADMIN'  => ['label' => 'Super Admin',  'class' => 'text-danger'],
    'FACTURACION' => ['label' => 'Facturación',   'class' => 'text-warning'],
    'AMBIENTAL'   => ['label' => 'Ambiental',     'class' => 'text-success'],
    'CONSULTA'    => ['label' => 'Consulta',      'class' => 'text-secondary'],
];
$userRole = Auth::check() ? (Auth::user()->role ?? 'CONSULTA') : 'CONSULTA';
$roleMeta = $roleLabels[$userRole] ?? ['label' => $userRole, 'class' => 'text-secondary'];
@endphp

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
<div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4 ms-0">
  <a href="{{ url('/') }}" class="app-brand-link">
    <span class="app-brand-logo demo">@include('_partials.macros')</span>
  </a>
  @if (isset($menuHorizontal))
  <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
    <i class="icon-base ti tabler-x icon-sm d-flex align-items-center justify-content-center"></i>
  </a>
  @endif
</div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
<div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
  <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
    <i class="icon-base ti tabler-menu-2 icon-md"></i>
  </a>
</div>
@endif

<div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">

  @if (!isset($menuHorizontal))
  <!-- Búsqueda -->
  <div class="navbar-nav align-items-center">
    <div class="nav-item navbar-search-wrapper px-md-0 px-2 mb-0">
      <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
        <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
      </a>
    </div>
  </div>
  @endif

  <ul class="navbar-nav flex-row align-items-center ms-md-auto">

    @if (isset($menuHorizontal))
    <!-- Búsqueda -->
    <li class="nav-item navbar-search-wrapper btn btn-text-secondary btn-icon rounded-pill">
      <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
        <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
      </a>
    </li>
    @endif

    @if ($configData['hasCustomizer'] == true)
    <!-- Tema -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill" id="nav-theme"
        href="javascript:void(0);" data-bs-toggle="dropdown">
        <i class="icon-base ti tabler-sun icon-22px theme-icon-active text-heading"></i>
        <span class="d-none ms-2" id="nav-theme-text">Cambiar tema</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
        <li>
          <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light" aria-pressed="false">
            <span><i class="icon-base ti tabler-sun icon-22px me-3" data-icon="sun"></i>Claro</span>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="true">
            <span><i class="icon-base ti tabler-moon-stars icon-22px me-3" data-icon="moon-stars"></i>Oscuro</span>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system" aria-pressed="false">
            <span><i class="icon-base ti tabler-device-desktop-analytics icon-22px me-3" data-icon="device-desktop-analytics"></i>Sistema</span>
          </button>
        </li>
      </ul>
    </li>
    @endif

    <!-- Accesos rápidos -->
    <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown">
      <a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
        href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <i class="icon-base ti tabler-layout-grid-add icon-22px text-heading"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-end p-0">
        <div class="dropdown-menu-header border-bottom">
          <div class="dropdown-header d-flex align-items-center py-3">
            <h6 class="mb-0 me-auto">Accesos rápidos</h6>
          </div>
        </div>
        <div class="dropdown-shortcuts-list scrollable-container">
          <div class="row row-bordered overflow-visible g-0">
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-home icon-26px text-heading"></i>
              </span>
              <a href="{{ url('/') }}" class="stretched-link">Inicio</a>
              <small>Dashboard</small>
            </div>
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-building-factory-2 icon-26px text-heading"></i>
              </span>
              <a href="{{ url('generators') }}" class="stretched-link">Generadores</a>
              <small>Catálogo</small>
            </div>
          </div>
          <div class="row row-bordered overflow-visible g-0">
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-file-text icon-26px text-heading"></i>
              </span>
              <a href="{{ url('manifests') }}" class="stretched-link">Manifiestos</a>
              <small>Documentos</small>
            </div>
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-file-invoice icon-26px text-heading"></i>
              </span>
              <a href="{{ url('withdrawals') }}" class="stretched-link">Entradas</a>
              <small>Documentos</small>
            </div>
          </div>
          <div class="row row-bordered overflow-visible g-0">
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-truck-delivery icon-26px text-heading"></i>
              </span>
              <a href="{{ url('transporters') }}" class="stretched-link">Transportistas</a>
              <small>Catálogo</small>
            </div>
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ti tabler-receipt icon-26px text-heading"></i>
              </span>
              <a href="{{ url('remisions') }}" class="stretched-link">Remisiones</a>
              <small>Documentos</small>
            </div>
          </div>
        </div>
      </div>
    </li>
    <!-- / Accesos rápidos -->

    <!-- Usuario -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          <span class="avatar-initial rounded-circle bg-label-primary">
            {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'U' }}
          </span>
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <div class="dropdown-item mt-0 pe-none">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0 me-2">
                <div class="avatar avatar-online">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'U' }}
                  </span>
                </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="mb-0">
                  {{ Auth::check() ? Auth::user()->name : 'Usuario' }}
                </h6>
                <small class="{{ $roleMeta['class'] }} fw-semibold">{{ $roleMeta['label'] }}</small>
              </div>
            </div>
          </div>
        </li>
        <li>
          <div class="dropdown-divider my-1 mx-n2"></div>
        </li>
        @if (Auth::check())
        <li>
          <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Cerrar sesión</span>
          </a>
        </li>
        <form method="POST" id="logout-form" action="{{ route('logout') }}">
          @csrf
        </form>
        @else
        <li>
          <div class="d-grid px-2 pt-2 pb-1">
            <a class="btn btn-sm btn-danger d-flex"
              href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
              <small class="align-middle">Iniciar sesión</small>
              <i class="icon-base ti tabler-login ms-2 icon-14px"></i>
            </a>
          </div>
        </li>
        @endif
      </ul>
    </li>
    <!--/ Usuario -->

  </ul>
</div>
