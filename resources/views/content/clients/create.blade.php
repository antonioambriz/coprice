@extends('layouts/layoutMaster')

@section('title', 'Nuevo Cliente')

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
      <li class="breadcrumb-item active">Clientes</li>
    </ol>
  </nav>

  <form action="{{ route('clients.store') }}" method="POST">
    @csrf
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">Datos del Nuevo Cliente</h5>
          <div class="card-body">
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

            </div>
          </div>
        </div>

        <div class="card mb-4">
          <h5 class="card-header">Dirección Fiscal</h5>
          <div class="card-body">
            <div class="row">

              <div class="mb-3 col-md-4">
                <label for="street" class="form-label">Calle</label>
                <input class="form-control @error('street') is-invalid @enderror" type="text" id="street"
                  name="street" value="{{ old('street') }}" />
                @error('street')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3 col-md-2">
                <label for="ext_number" class="form-label">No. Ext</label>
                <input class="form-control @error('ext_number') is-invalid @enderror" type="text" id="ext_number"
                  name="ext_number" value="{{ old('ext_number') }}" />
                @error('ext_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3 col-md-2">
                <label for="int_number" class="form-label">No. Int</label>
                <input class="form-control @error('int_number') is-invalid @enderror" type="text" id="int_number"
                  name="int_number" value="{{ old('int_number') }}" />
                @error('int_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3 col-md-4">
                <label for="municipality" class="form-label">Municipio</label>
                <input class="form-control @error('municipality') is-invalid @enderror" type="text" id="municipality"
                  name="municipality" value="{{ old('municipality') }}" />
                @error('municipality')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3 col-md-4">
                <label for="state" class="form-label">Estado</label>
                <input class="form-control @error('state') is-invalid @enderror" type="text" id="state"
                  name="state" value="{{ old('state') }}" />
                @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3 col-md-2">
                <label for="postal_code" class="form-label">C.P.</label>
                <input class="form-control @error('postal_code') is-invalid @enderror" type="text" id="postal_code"
                  name="postal_code" value="{{ old('postal_code') }}" maxlength="5" />
                @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3 col-md-4">
                <label for="country" class="form-label">País</label>
                <input class="form-control @error('country') is-invalid @enderror" type="text" id="country"
                  name="country" value="{{ old('country', 'México') }}" />
                @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3 col-md-6">
                <label for="payment_method" class="form-label">Forma de Pago</label>
                <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method">
                  <option value="">— No especificado —</option>
                  @foreach(\App\Models\Client::PAYMENT_METHODS as $code => $label)
                    <option value="{{ $code }}" {{ old('payment_method') === $code ? 'selected' : '' }}>{{ $code }} - {{ $label }}</option>
                  @endforeach
                </select>
                @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3 col-md-6">
                <label for="credit_days" class="form-label">Condiciones de Crédito</label>
                <select class="form-select @error('credit_days') is-invalid @enderror" id="credit_days" name="credit_days">
                  <option value="">— No especificado —</option>
                  @foreach(\App\Models\Client::CREDIT_DAYS_OPTIONS as $days)
                    <option value="{{ $days }}" {{ (string) old('credit_days') === (string) $days ? 'selected' : '' }}>{{ $days }} días</option>
                  @endforeach
                </select>
                @error('credit_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary me-2">Guardar Cliente</button>
              <a href="{{ route('clients.index') }}" class="btn btn-label-secondary">Cancelar</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection
