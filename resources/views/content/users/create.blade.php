@extends('layouts/layoutMaster')

@section('title', 'Nuevo Usuario')

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom-icon">
      <li class="breadcrumb-item"><a href="{{ route('dashboard-analytics') }}">Inicio</a>
        <i class="breadcrumb-icon icon-base ti tabler-chevron-right align-middle icon-xs mx-2"></i>
      </li>
      <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a>
        <i class="breadcrumb-icon icon-base ti tabler-chevron-right align-middle icon-xs mx-2"></i>
      </li>
      <li class="breadcrumb-item active">Nuevo</li>
    </ol>
  </nav>

  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Registrar Usuario</h5>
        </div>
        <div class="card-body pt-4">

          @if ($errors->any())
            <div class="alert alert-danger mb-3">
              <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
          @endif

          <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label class="form-label">Nombre</label>
              <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Rol</label>
              <select name="role" class="form-select" required>
                @foreach(['SUPERADMIN','FACTURACION','AMBIENTAL','CONSULTA'] as $rol)
                  <option value="{{ $rol }}" {{ old('role') === $rol ? 'selected' : '' }}>{{ $rol }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Confirmar Contraseña</label>
              <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">
                <i class="ti tabler-device-floppy me-1"></i> Guardar
              </button>
              <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection
