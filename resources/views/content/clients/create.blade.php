@extends('layouts/layoutMaster')

@section('title', 'Nuevo Cliente')

@section('content')
  <h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Clientes /</span> Crear Nuevo
  </h4>

  <div class="row">
    <div class="col-md-12">
      <div class="card mb-4">
        <h5 class="card-header">Datos del Nuevo Cliente</h5>
        <div class="card-body">
          <form action="{{ route('clients.store') }}" method="POST">
            @csrf
            <div class="row">

              <div class="mb-3 col-md-6">
                <label for="company_name" class="form-label">Nombre / Razón Social <span class="text-danger">*</span></label>
                <input class="form-control @error('company_name') is-invalid @enderror" type="text" id="company_name"
                  name="company_name" value="{{ old('company_name') }}" placeholder="EJ. AGUAS Y DRENAJES" required autofocus />
                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3 col-md-3">
                <label for="rfc" class="form-label">RFC</label>
                <input class="form-control @error('rfc') is-invalid @enderror" type="text" id="rfc"
                  name="rfc" value="{{ old('rfc') }}" placeholder="ABC000000XXX" maxlength="13" />
                @error('rfc')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3 col-md-4">
                <label for="contact_person" class="form-label">Persona de Contacto</label>
                <input class="form-control @error('contact_person') is-invalid @enderror" type="text"
                  id="contact_person" name="contact_person" value="{{ old('contact_person') }}" />
                @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3 col-md-4">
                <label for="email" class="form-label">Email</label>
                <input class="form-control @error('email') is-invalid @enderror" type="email"
                  id="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com" />
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3 col-md-4 d-flex align-items-end">
                <div class="form-check form-switch mb-1">
                  <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1"
                    {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                  <label class="form-check-label" for="activo">Cliente Activo</label>
                </div>
              </div>

              <div class="mb-3 col-md-12">
                <label for="address" class="form-label">Dirección</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address"
                  name="address" rows="2" placeholder="CALLE, NÚMERO, COLONIA, CP, CIUDAD">{{ old('address') }}</textarea>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

            </div>
            <div class="mt-4">
              <button type="submit" class="btn btn-primary me-2">Guardar Cliente</button>
              <a href="{{ route('clients.index') }}" class="btn btn-label-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
