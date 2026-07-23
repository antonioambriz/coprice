@extends('layouts/layoutMaster')

@section('title', 'Editar Cliente')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  ])
@endsection
@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  ])
@endsection

@section('page-style')
<style>
  #contactsTable_wrapper > .row:not(.dt-layout-table) {
    padding-inline: 1.5rem;
  }
</style>
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
      <li class="breadcrumb-item active">Clientes</li>
    </ol>
  </nav>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- DATOS GENERALES --}}
  <div class="card mb-4">
    <h5 class="card-header">Datos del Cliente</h5>
    <div class="card-body">
      <form action="{{ route('clients.update', $client) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">

          <div class="mb-3 col-md-6">
            <label for="company_name" class="form-label">Nombre / Razón Social <span class="text-danger">*</span></label>
            <input class="form-control @error('company_name') is-invalid @enderror" type="text" id="company_name"
              name="company_name" value="{{ old('company_name', $client->company_name) }}" required />
            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3 col-md-3">
            <label for="rfc" class="form-label">RFC</label>
            <input class="form-control @error('rfc') is-invalid @enderror" type="text" id="rfc"
              name="rfc" value="{{ old('rfc', $client->rfc) }}" maxlength="13" />
            @error('rfc')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3 col-md-3 d-flex align-items-end">
            <div class="form-check form-switch mb-1">
              <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1"
                {{ old('activo', $client->activo) ? 'checked' : '' }}>
              <label class="form-check-label" for="activo">Cliente Activo</label>
            </div>
          </div>

          <div class="mb-3 col-md-4">
            <label for="contact_person" class="form-label">Persona de Contacto</label>
            <input class="form-control @error('contact_person') is-invalid @enderror" type="text"
              id="contact_person" name="contact_person"
              value="{{ old('contact_person', $client->contact_person) }}" />
            @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3 col-md-4">
            <label for="email" class="form-label">Email</label>
            <input class="form-control @error('email') is-invalid @enderror" type="email"
              id="email" name="email" value="{{ old('email', $client->email) }}" />
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

        </div>

        <hr class="my-4">
        <h6 class="mb-3">Dirección Fiscal</h6>
        <div class="row">

          <div class="mb-3 col-md-4">
            <label for="street" class="form-label">Calle</label>
            <input class="form-control @error('street') is-invalid @enderror" type="text" id="street"
              name="street" value="{{ old('street', $client->street) }}" />
            @error('street')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3 col-md-2">
            <label for="ext_number" class="form-label">No. Ext</label>
            <input class="form-control @error('ext_number') is-invalid @enderror" type="text" id="ext_number"
              name="ext_number" value="{{ old('ext_number', $client->ext_number) }}" />
            @error('ext_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3 col-md-2">
            <label for="int_number" class="form-label">No. Int</label>
            <input class="form-control @error('int_number') is-invalid @enderror" type="text" id="int_number"
              name="int_number" value="{{ old('int_number', $client->int_number) }}" />
            @error('int_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3 col-md-4">
            <label for="municipality" class="form-label">Municipio</label>
            <input class="form-control @error('municipality') is-invalid @enderror" type="text" id="municipality"
              name="municipality" value="{{ old('municipality', $client->municipality) }}" />
            @error('municipality')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3 col-md-4">
            <label for="state" class="form-label">Estado</label>
            <input class="form-control @error('state') is-invalid @enderror" type="text" id="state"
              name="state" value="{{ old('state', $client->state) }}" />
            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3 col-md-2">
            <label for="postal_code" class="form-label">C.P.</label>
            <input class="form-control @error('postal_code') is-invalid @enderror" type="text" id="postal_code"
              name="postal_code" value="{{ old('postal_code', $client->postal_code) }}" maxlength="5" />
            @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3 col-md-4">
            <label for="country" class="form-label">País</label>
            <input class="form-control @error('country') is-invalid @enderror" type="text" id="country"
              name="country" value="{{ old('country', $client->country ?? 'México') }}" />
            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3 col-md-6">
            <label for="payment_method" class="form-label">Forma de Pago</label>
            <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method">
              <option value="">— No especificado —</option>
              @foreach(\App\Models\Client::PAYMENT_METHODS as $code => $label)
                <option value="{{ $code }}" {{ old('payment_method', $client->payment_method) === $code ? 'selected' : '' }}>{{ $code }} - {{ $label }}</option>
              @endforeach
            </select>
            @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3 col-md-6">
            <label for="credit_days" class="form-label">Condiciones de Crédito</label>
            <select class="form-select @error('credit_days') is-invalid @enderror" id="credit_days" name="credit_days">
              <option value="">— No especificado —</option>
              @foreach(\App\Models\Client::CREDIT_DAYS_OPTIONS as $days)
                <option value="{{ $days }}" {{ (string) old('credit_days', $client->credit_days) === (string) $days ? 'selected' : '' }}>{{ $days }} días</option>
              @endforeach
            </select>
            @error('credit_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

        </div>
        <div class="mt-2 d-flex gap-2 flex-wrap align-items-center">
          <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
          <a href="{{ route('clients.index') }}" class="btn btn-label-secondary">Cancelar</a>
          <a href="{{ route('waste-prices.client', $client) }}" class="btn btn-outline-primary ms-auto">
            <i class="ti tabler-currency-dollar me-1"></i> Precios por Residuo
          </a>
        </div>
      </form>
    </div>
  </div>

  @include('content.contacts._panel', [
    'contactsGetDataUrl' => route('clients.contacts.get-data', $client),
    'contactsStoreUrl'   => route('clients.contacts.store', $client),
    'contactsBaseUrl'    => url('clients/'.$client->id.'/contacts'),
  ])

  {{-- GENERADORES Y RESIDUOS --}}
  <div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
      <div>
        <h5 class="card-title mb-0">Generadores y Residuos</h5>
        <small class="text-muted">Residuos que este cliente maneja por generador</small>
      </div>
      <button type="button" class="btn btn-outline-primary btn-sm" id="btnAddGenerator">
        <i class="ti tabler-plus me-1"></i> Agregar Generador
      </button>
    </div>

    {{-- Selector de nuevo generador --}}
    <div class="p-3 border-bottom bg-light d-none" id="addGeneratorRow">
      <div class="row g-2 align-items-end">
        <div class="col-md-9">
          <label class="form-label small mb-1">Seleccionar Generador</label>
          <select id="generatorSelector" class="form-select form-select-sm">
            <option value="">— Seleccionar —</option>
            @foreach($generators as $g)
              <option value="{{ $g->id }}" data-name="{{ $g->company_name }}">{{ $g->company_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button type="button" class="btn btn-primary btn-sm flex-fill" id="btnConfirmAdd">Agregar</button>
          <button type="button" class="btn btn-label-secondary btn-sm" id="btnCancelAdd">Cancelar</button>
        </div>
      </div>
    </div>

    {{-- Acordeón --}}
    <div class="accordion accordion-flush" id="generatorsAccordion">

      @forelse($assignedGenerators as $generator)
        @php $rel = $wastesByGenerator[$generator->id] ?? ['waste_ids' => [], 'final_destination_id' => null]; @endphp
        <div class="accordion-item generator-item" data-generator-id="{{ $generator->id }}">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed py-3" type="button"
              data-bs-toggle="collapse" data-bs-target="#gen-{{ $generator->id }}">
              <span class="fw-medium me-2">{{ $generator->company_name }}</span>
              <span class="badge bg-label-primary waste-count">{{ count($rel['waste_ids']) }} residuos</span>
            </button>
          </h2>
          <div id="gen-{{ $generator->id }}" class="accordion-collapse collapse">
            <div class="accordion-body">

              <div class="row g-3 mb-3">
                <div class="col-md-5">
                  <label class="form-label small fw-semibold">Destino Final</label>
                  <select class="form-select form-select-sm final-destination-sel">
                    <option value="">— Sin especificar —</option>
                    @foreach($finalDestinations as $fd)
                      <option value="{{ $fd->id }}"
                        {{ $rel['final_destination_id'] == $fd->id ? 'selected' : '' }}>
                        {{ $fd->name }}{{ $fd->authorization_number ? ' ('.$fd->authorization_number.')' : '' }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <label class="form-label small fw-semibold">Residuos</label>
              <div class="row g-2 mb-3">
                @foreach($wastes as $waste)
                  <div class="col-md-4">
                    <div class="form-check">
                      <input class="form-check-input waste-check" type="checkbox"
                        value="{{ $waste->id }}"
                        id="w-{{ $generator->id }}-{{ $waste->id }}"
                        {{ in_array($waste->id, $rel['waste_ids']) ? 'checked' : '' }}>
                      <label class="form-check-label small" for="w-{{ $generator->id }}-{{ $waste->id }}">
                        {{ $waste->description }}
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>

              <div class="text-end">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-generator">
                  <i class="ti tabler-trash me-1"></i> Quitar generador
                </button>
              </div>

            </div>
          </div>
        </div>
      @empty
        <div class="text-center text-muted py-5" id="emptyState">
          <i class="ti tabler-inbox d-block mb-2" style="font-size:2rem;"></i>
          Sin generadores asignados. Agrega uno con el botón de arriba.
        </div>
      @endforelse

    </div>

    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
      <small class="text-muted" id="relCount">
        {{ $assignedGenerators->count() }} generador(es) asignado(s)
      </small>
      <button type="button" class="btn btn-primary" id="btnSaveRelationships">
        <i class="ti tabler-device-floppy me-1"></i> Guardar Relaciones
      </button>
    </div>
  </div>

  {{-- Toast --}}
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="globalToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="ti tabler-check text-success me-2" id="toastIcon"></i>
        <strong class="me-auto">Coprice</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
      </div>
      <div class="toast-body" id="globalToastBody"></div>
    </div>
  </div>

@endsection

@section('page-script')
<script>
const SAVE_URL       = "{{ route('clients.generator-wastes.save', $client) }}";
const CSRF_TOKEN     = "{{ csrf_token() }}";

var contactsGetDataUrl = "{{ route('clients.contacts.get-data', $client) }}";
var contactsStoreUrl   = "{{ route('clients.contacts.store', $client) }}";
var contactsBaseUrl    = "{{ url('clients/'.$client->id.'/contacts') }}";
var csrfToken           = "{{ csrf_token() }}";

const ALL_WASTES = @json($wastes->map(fn($w) => ['id' => $w->id, 'description' => $w->description]));
const ALL_DESTINATIONS = @json($finalDestinations->map(fn($f) => ['id' => $f->id, 'label' => $f->name . ($f->authorization_number ? ' ('.$f->authorization_number.')' : '')]));

// IDs de generadores ya asignados (para evitar duplicados)
const assignedIds = new Set([...document.querySelectorAll('.generator-item')].map(el => el.dataset.generatorId));

// ── Toast ───────────────────────────────────────────────────────
function showToast(message, type = 'success') {
  const el   = document.getElementById('globalToast');
  const body = document.getElementById('globalToastBody');
  const icon = document.getElementById('toastIcon');
  body.textContent = message;
  icon.className   = type === 'error'
    ? 'ti tabler-ban text-danger me-2'
    : 'ti tabler-check text-success me-2';
  new bootstrap.Toast(el, { delay: 3500 }).show();
}

// ── Actualizar badge de residuos ────────────────────────────────
function updateBadge(item) {
  const count = item.querySelectorAll('.waste-check:checked').length;
  item.querySelector('.waste-count').textContent = `${count} residuos`;
}

// ── Construir HTML de un accordion item nuevo ───────────────────
function buildAccordionItem(generatorId, generatorName) {
  const wastesHtml = ALL_WASTES.map(w => `
    <div class="col-md-4">
      <div class="form-check">
        <input class="form-check-input waste-check" type="checkbox"
          value="${w.id}" id="w-${generatorId}-${w.id}">
        <label class="form-check-label small" for="w-${generatorId}-${w.id}">
          ${w.description}
        </label>
      </div>
    </div>`).join('');

  const destOptions = ALL_DESTINATIONS.map(d =>
    `<option value="${d.id}">${d.label}</option>`
  ).join('');

  return `
    <div class="accordion-item generator-item" data-generator-id="${generatorId}">
      <h2 class="accordion-header">
        <button class="accordion-button py-3" type="button"
          data-bs-toggle="collapse" data-bs-target="#gen-${generatorId}">
          <span class="fw-medium me-2">${generatorName}</span>
          <span class="badge bg-label-primary waste-count">0 residuos</span>
        </button>
      </h2>
      <div id="gen-${generatorId}" class="accordion-collapse collapse show">
        <div class="accordion-body">
          <div class="row g-3 mb-3">
            <div class="col-md-5">
              <label class="form-label small fw-semibold">Destino Final</label>
              <select class="form-select form-select-sm final-destination-sel">
                <option value="">— Sin especificar —</option>
                ${destOptions}
              </select>
            </div>
          </div>
          <label class="form-label small fw-semibold">Residuos</label>
          <div class="row g-2 mb-3">${wastesHtml}</div>
          <div class="text-end">
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-generator">
              <i class="ti tabler-trash me-1"></i> Quitar generador
            </button>
          </div>
        </div>
      </div>
    </div>`;
}

// ── Actualizar contador del footer ──────────────────────────────
function updateRelCount() {
  const n = document.querySelectorAll('.generator-item').length;
  document.getElementById('relCount').textContent = `${n} generador(es) asignado(s)`;
}

// ── Agregar generador ───────────────────────────────────────────
document.getElementById('btnAddGenerator').addEventListener('click', () => {
  document.getElementById('addGeneratorRow').classList.remove('d-none');
  document.getElementById('generatorSelector').value = '';
});

document.getElementById('btnCancelAdd').addEventListener('click', () => {
  document.getElementById('addGeneratorRow').classList.add('d-none');
});

document.getElementById('btnConfirmAdd').addEventListener('click', () => {
  const sel  = document.getElementById('generatorSelector');
  const id   = sel.value;
  const name = sel.options[sel.selectedIndex]?.dataset.name;

  if (!id) { showToast('Selecciona un generador.', 'error'); return; }
  if (assignedIds.has(id)) { showToast('Este generador ya está agregado.', 'error'); return; }

  const empty = document.getElementById('emptyState');
  if (empty) empty.remove();

  document.getElementById('generatorsAccordion').insertAdjacentHTML('beforeend', buildAccordionItem(id, name));
  assignedIds.add(id);
  document.getElementById('addGeneratorRow').classList.add('d-none');
  updateRelCount();
});

// ── Quitar generador ────────────────────────────────────────────
document.getElementById('generatorsAccordion').addEventListener('click', e => {
  if (!e.target.closest('.btn-remove-generator')) return;
  const item = e.target.closest('.generator-item');
  Swal.fire({
    title: '¿Quitar generador?',
    text: 'Se eliminarán todos los residuos asignados a este generador.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, quitar',
    cancelButtonText: 'Cancelar',
    customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-label-secondary' },
    buttonsStyling: false
  }).then(r => {
    if (r.isConfirmed) {
      assignedIds.delete(item.dataset.generatorId);
      item.remove();
      updateRelCount();
    }
  });
});

// ── Actualizar badge al cambiar checkboxes ──────────────────────
document.getElementById('generatorsAccordion').addEventListener('change', e => {
  if (e.target.classList.contains('waste-check')) {
    updateBadge(e.target.closest('.generator-item'));
  }
});

// ── Guardar relaciones ──────────────────────────────────────────
document.getElementById('btnSaveRelationships').addEventListener('click', () => {
  const relationships = [];

  document.querySelectorAll('.generator-item').forEach(item => {
    const generatorId       = item.dataset.generatorId;
    const wasteIds          = [...item.querySelectorAll('.waste-check:checked')].map(c => c.value);
    const finalDestinationId = item.querySelector('.final-destination-sel')?.value || null;
    relationships.push({ generator_id: generatorId, waste_ids: wasteIds, final_destination_id: finalDestinationId });
  });

  fetch(SAVE_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
    body: JSON.stringify({ relationships })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) showToast(data.message);
    else showToast('Error al guardar.', 'error');
  })
  .catch(() => showToast('Error de conexión.', 'error'));
});
</script>
@vite(['resources/assets/js/contacts/panel.js'])
@endsection
