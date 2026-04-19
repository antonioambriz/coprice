<?php

namespace App\Http\Controllers;

use App\Models\Transporter;
use App\Models\TransportEquipment;
use Illuminate\Http\Request;

class TransportEquipmentController extends Controller
{
    public function index(Transporter $transporter)
    {
        return view('content.transporters.equipments', compact('transporter'));
    }

    public function getData(Transporter $transporter)
    {
        $equipments = TransportEquipment::where('transporter_id', $transporter->id)->get();

        return response()->json([
            'data' => $equipments->map(fn($e) => [
                'id'           => $e->id,
                'description'  => $e->description,
                'plate_number' => $e->plate_number ?? '—',
                'activo'       => $e->activo,
            ])
        ]);
    }

    public function store(Request $request, Transporter $transporter)
    {
        $validated = $request->validate([
            'description'  => 'required|string|max:255',
            'plate_number' => 'nullable|string|max:20',
            'activo'       => 'boolean',
        ]);

        $validated['description']  = strtoupper($validated['description']);
        $validated['plate_number'] = isset($validated['plate_number']) ? strtoupper($validated['plate_number']) : null;
        $validated['transporter_id'] = $transporter->id;
        $validated['activo'] = $request->boolean('activo', true);

        $equipment = TransportEquipment::create($validated);

        return response()->json(['success' => true, 'equipment' => $equipment]);
    }

    public function update(Request $request, Transporter $transporter, TransportEquipment $equipment)
    {
        $validated = $request->validate([
            'description'  => 'required|string|max:255',
            'plate_number' => 'nullable|string|max:20',
            'activo'       => 'boolean',
        ]);

        $validated['description']  = strtoupper($validated['description']);
        $validated['plate_number'] = isset($validated['plate_number']) ? strtoupper($validated['plate_number']) : null;
        $validated['activo'] = $request->boolean('activo', true);

        $equipment->update($validated);

        return response()->json(['success' => true, 'equipment' => $equipment]);
    }

    public function destroy(Transporter $transporter, TransportEquipment $equipment)
    {
        $equipment->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Devuelve los equipos activos de un transportista (para carga dinámica en retiros).
     */
    public function getByTransporter(Transporter $transporter)
    {
        $equipments = TransportEquipment::where('transporter_id', $transporter->id)
            ->where('activo', true)
            ->orderBy('description')
            ->get(['id', 'description', 'plate_number']);

        return response()->json($equipments);
    }
}
