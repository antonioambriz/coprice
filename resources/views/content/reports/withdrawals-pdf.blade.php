<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Entradas</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 8.5pt;
    color: #1a1a2e;
    background: #fff;
  }

  /* ── Encabezado ── */
  .header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    border-bottom: 3px solid #2B5C3F;
    padding-bottom: 8px;
    margin-bottom: 10px;
  }
  .header-left h1 {
    font-size: 14pt;
    color: #2B5C3F;
    font-weight: bold;
  }
  .header-left p {
    font-size: 8pt;
    color: #666;
    margin-top: 2px;
  }
  .header-right {
    text-align: right;
    font-size: 7.5pt;
    color: #555;
  }

  /* ── Filtros aplicados ── */
  .filters {
    background: #f4f8f5;
    border: 1px solid #d0ddd4;
    border-radius: 4px;
    padding: 6px 10px;
    margin-bottom: 10px;
    font-size: 7.5pt;
    color: #444;
  }
  .filters strong { color: #2B5C3F; }

  /* ── Tabla ── */
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 7.5pt;
  }
  thead tr {
    background: #2B5C3F;
    color: #fff;
  }
  thead th {
    padding: 5px 6px;
    text-align: left;
    font-weight: bold;
    white-space: nowrap;
  }
  tbody tr:nth-child(even) { background: #f4f8f5; }
  tbody tr:nth-child(odd)  { background: #ffffff; }
  tbody td {
    padding: 4px 6px;
    border-bottom: 1px solid #e0e8e4;
    vertical-align: top;
  }

  .text-right  { text-align: right; }
  .text-center { text-align: center; }
  .nowrap      { white-space: nowrap; }

  .badge-success  { background: #d1fae5; color: #065f46; padding: 1px 5px; border-radius: 3px; font-size: 7pt; }
  .badge-warning  { background: #fef3c7; color: #92400e; padding: 1px 5px; border-radius: 3px; font-size: 7pt; }
  .badge-danger   { background: #fee2e2; color: #991b1b; padding: 1px 5px; border-radius: 3px; font-size: 7pt; }
  .badge-est      { background: #fef9c3; color: #713f12; padding: 1px 5px; border-radius: 3px; font-size: 7pt; }

  /* ── Totales ── */
  .totals-row td {
    background: #2B5C3F;
    color: #fff;
    font-weight: bold;
    padding: 5px 6px;
    border: none;
  }

  /* ── Pie ── */
  .footer {
    margin-top: 12px;
    border-top: 1px solid #d0ddd4;
    padding-top: 6px;
    font-size: 7pt;
    color: #888;
    display: flex;
    justify-content: space-between;
  }
</style>
</head>
<body>

{{-- Encabezado --}}
<div class="header">
  <div class="header-left">
    <h1>Reporte de Entradas</h1>
    <p>Coprice — Sistema de Gestión de Residuos</p>
  </div>
  <div class="header-right">
    <div>Generado: {{ now()->format('d/m/Y H:i') }}</div>
    <div>Total de registros: <strong>{{ $withdrawals->count() }}</strong></div>
  </div>
</div>

{{-- Filtros aplicados --}}
<div class="filters">
  <strong>Filtros aplicados:</strong>
  @if(!empty($filters['date_from']) || !empty($filters['date_to']))
    &nbsp;Período:
    {{ !empty($filters['date_from']) ? \Carbon\Carbon::parse($filters['date_from'])->format('d/m/Y') : '—' }}
    al
    {{ !empty($filters['date_to']) ? \Carbon\Carbon::parse($filters['date_to'])->format('d/m/Y') : '—' }}
    &nbsp;|
  @endif
  @if(!empty($filters['generator_name']))
    &nbsp;Generador: {{ $filters['generator_name'] }} &nbsp;|
  @endif
  @if(!empty($filters['transporter_name']))
    &nbsp;Transportista: {{ $filters['transporter_name'] }} &nbsp;|
  @endif
  @if(!empty($filters['payment_status']))
    &nbsp;Estatus: {{ implode(', ', $filters['payment_status']) }}
  @endif
  @if(empty(array_filter($filters)))
    Todos los registros
  @endif
</div>

{{-- Tabla --}}
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Folio</th>
      <th>Fecha</th>
      <th>Generador</th>
      <th>División</th>
      <th>Transportista</th>
      <th>Residuos</th>
      <th class="text-right">Cantidad</th>
      <th class="text-center">Peso</th>
      <th class="text-center">Manifiesto</th>
      <th class="text-center">Estatus</th>
    </tr>
  </thead>
  <tbody>
    @php $grandTotal = 0; @endphp
    @foreach($withdrawals as $w)
    @php
      $totalQty  = $w->items->sum('quantity');
      $grandTotal += $totalQty;
      $unit = $w->items->first()?->unit ?? '';
      $residuos = $w->items->map(fn($i) => $i->waste?->description ?? '—')->implode(', ');
    @endphp
    <tr>
      <td class="nowrap text-center">{{ $w->id }}</td>
      <td class="nowrap">{{ $w->folio_interno }}</td>
      <td class="nowrap">{{ $w->reception_date?->format('d/m/Y') }}</td>
      <td>{{ $w->generator?->company_name ?? '—' }}</td>
      <td>{{ $w->subGenerator?->name ?? '—' }}</td>
      <td>{{ $w->transporter?->company_name ?? '—' }}</td>
      <td style="max-width:130px">{{ $residuos }}</td>
      <td class="text-right nowrap">{{ number_format($totalQty, 2) }} {{ $unit }}</td>
      <td class="text-center">
        @if($w->is_estimated_weight)
        <span class="badge-est">Est.</span>
        @else
        <span class="badge-success">Real</span>
        @endif
      </td>
      <td class="text-center nowrap">{{ $w->manifest?->manifest_number ?? '—' }}</td>
      <td class="text-center">
        @if($w->payment_status === 'PAGADO')
        <span class="badge-success">PAGADO</span>
        @elseif($w->payment_status === 'CANCELADO')
        <span class="badge-danger">CANCELADO</span>
        @else
        <span class="badge-warning">PENDIENTE</span>
        @endif
      </td>
    </tr>
    @endforeach

    {{-- Fila de totales --}}
    <tr class="totals-row">
      <td colspan="7" class="text-right">TOTAL:</td>
      <td class="text-right">{{ number_format($grandTotal, 2) }}</td>
      <td colspan="3"></td>
    </tr>
  </tbody>
</table>

{{-- Pie de página --}}
<div class="footer">
  <span>Coprice — Reporte generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}</span>
  <span>{{ $withdrawals->count() }} registro(s)</span>
</div>

</body>
</html>
