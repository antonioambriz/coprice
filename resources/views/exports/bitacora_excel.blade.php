<table>
  <thead>
    {{-- Encabezado Verde como tu imagen --}}
    <tr style="background-color: #1a5e28; color: #ffffff; font-weight: bold;">
      <th>Fecha de Recepción</th>
      <th>SALID</th>
      <th>TICKE</th>
      <th>No. de Manifiesto</th>
      <th>Empresa Generadora</th>
      <th>Residuo de Manejo Especial</th>
      <th>Fecha de salida</th>
      <th>Estado Físico</th>
      <th>RR</th>
      <th>RNV</th>
      <th>Cantidad</th>
      <th>Unidad</th>
      <th>Tipo de envasado</th>
      <th>Capacidad (Cant/Un)</th>
      <th>Etapa de manejo</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($tickets as $ticket)
      @foreach ($ticket->details as $detail)
        <tr>
          <td>{{ $ticket->reception_date }}</td>
          <td>{{ $ticket->ticket_salida }}</td>
          <td>{{ $ticket->ticket_externo }}</td>
          <td>{{ $ticket->manifest_recepcion }}</td>
          <td>{{ $ticket->generator->company_name ?? 'N/A' }}</td>
          <td>{{ $detail->waste->description ?? 'N/A' }}</td>
          {{-- Formato de fecha larga como en tu imagen: martes, 18 de febrero... --}}
          <td>
            {{ $ticket->departure_date ? \Carbon\Carbon::parse($ticket->departure_date)->translatedFormat('l, d \d\e F \d\e Y') : '' }}
          </td>
          <td>{{ $detail->physical_state }}</td>
          <td>{{ $detail->ingreso_tipo == 'RR' ? 'X' : '' }}</td>
          <td>{{ $detail->ingreso_tipo == 'RNV' ? 'X' : '' }}</td>
          <td>{{ $detail->quantity }}</td>
          <td>{{ $detail->unit }}</td>
          <td>{{ $detail->packaging_type }}</td>
          <td>{{ $detail->container_capacity }} {{ $detail->container_unit }}</td>
          <td>{{ $detail->treatment_stage }}</td>
        </tr>
      @endforeach
    @endforeach
  </tbody>
</table>
