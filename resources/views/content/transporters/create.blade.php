@extends('layouts/layoutMaster')

@section('title', 'Nuevo Transportista')

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

  <div class="row">
    <div class="col-md-12">
      <div class="card mb-4">
        <h5 class="card-header">Datos del Nuevo Transportista</h5>
        <div class="card-body">
          <form action="{{ route('transporters.store') }}" method="POST">
            @csrf
            <div class="row">
              <div class="mb-3 col-md-6">
                <label for="company_name" class="form-label">Nombre de la Empresa / Razón Social</label>
                <input class="form-control @error('company_name') is-invalid @enderror" type="text" id="company_name"
                  name="company_name" value="{{ old('company_name') }}" placeholder="EJ. TRANSPORTES CITIO" required
                  autofocus />
                @error('company_name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-6">
                <label for="rfc" class="form-label">RFC</label>
                <input class="form-control @error('rfc') is-invalid @enderror" type="text" id="rfc"
                  name="rfc" value="{{ old('rfc') }}" placeholder="ABC000000XXX" maxlength="13" />
                @error('rfc')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-6">
                <label for="authorization_number" class="form-label">Número de Autorización</label>
                <input class="form-control @error('authorization_number') is-invalid @enderror" type="text"
                  id="authorization_number" name="authorization_number"
                  value="{{ old('authorization_number') }}" placeholder="EJ. IRA-PRME-516/2016" />
                @error('authorization_number')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-6">
                <label for="contact_person" class="form-label">Persona de Contacto</label>
                <input class="form-control @error('contact_person') is-invalid @enderror" type="text"
                  id="contact_person" name="contact_person" value="{{ old('contact_person') }}"
                  placeholder="NOMBRE DEL CONTACTO" />
                @error('contact_person')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-6">
                <label for="email_remissions" class="form-label">Email para Remisiones</label>
                <input class="form-control @error('email_remissions') is-invalid @enderror" type="email"
                  id="email_remissions" name="email_remissions" value="{{ old('email_remissions') }}"
                  placeholder="correo@ejemplo.com" />
                @error('email_remissions')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-12">
                <label for="address" class="form-label">Dirección Completa</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                  placeholder="CALLE, NÚMERO, COLONIA, CP, CIUDAD">{{ old('address') }}</textarea>
                @error('address')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-12">
                <div class="form-check form-switch mt-2">
                  <input class="form-check-input @error('activo') is-invalid @enderror" type="checkbox" id="activo"
                    name="activo" value="1" {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                  <label class="form-check-label" for="activo">Transportista Activo</label>
                </div>
                @error('activo')
                  <div class="text-danger small">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary me-2">Guardar Transportista</button>
              <a href="{{ route('transporters.index') }}" class="btn btn-label-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
