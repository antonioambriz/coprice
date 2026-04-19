@extends('layouts/layoutMaster')

@section('title', 'Editar Destino Final - Coprice')

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom-icon">
      <li class="breadcrumb-item"><a href="{{ route('dashboard-analytics') }}">Inicio</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item"><a href="{{ route('final-destinations.index') }}">Destinos Finales</a>
        <i class="ti tabler-chevron-right icon-xs mx-2"></i></li>
      <li class="breadcrumb-item active">{{ $finalDestination->name }}</li>
    </ol>
  </nav>

  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card shadow-none border">
        <div class="card-body">
          <h5 class="card-title mb-4">Editar Destino Final</h5>

          @if ($errors->any())
            <div class="alert alert-danger mb-3">
              <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
          @endif

          <form action="{{ route('final-destinations.update', $finalDestination) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label">Nombre <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $finalDestination->name) }}" required autofocus>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Número de Autorización</label>
              <input type="text" name="authorization_number" class="form-control @error('authorization_number') is-invalid @enderror"
                value="{{ old('authorization_number', $finalDestination->authorization_number) }}"
                placeholder="EJ. CEL-PRME-061/2009">
              @error('authorization_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1"
                  {{ old('activo', $finalDestination->activo) ? 'checked' : '' }}>
                <label class="form-check-label" for="activo">Activo</label>
              </div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">
                <i class="ti tabler-device-floppy me-1"></i> Actualizar
              </button>
              <a href="{{ route('final-destinations.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
