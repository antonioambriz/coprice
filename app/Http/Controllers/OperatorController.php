<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Transporter;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function getData(Transporter $transporter)
    {
        $operators = Operator::where('transporter_id', $transporter->id)->get();

        return response()->json([
            'data' => $operators->map(fn($o) => [
                'id'              => $o->id,
                'name'            => $o->name,
                'license_number'  => $o->license_number ?? '—',
                'phone'           => $o->phone ?? '—',
                'license_expiry'  => $o->license_expiry?->format('Y-m-d') ?? '—',
                'activo'          => $o->activo,
            ])
        ]);
    }

    public function store(Request $request, Transporter $transporter)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'license_number'  => 'nullable|string|max:50',
            'phone'           => 'nullable|string|max:20',
            'license_expiry'  => 'nullable|date',
            'activo'          => 'boolean',
        ]);

        $validated['name']           = strtoupper($validated['name']);
        $validated['license_number'] = isset($validated['license_number']) ? strtoupper($validated['license_number']) : null;
        $validated['transporter_id'] = $transporter->id;
        $validated['activo']         = $request->boolean('activo', true);

        $operator = Operator::create($validated);

        return response()->json(['success' => true, 'operator' => $operator]);
    }

    public function update(Request $request, Transporter $transporter, Operator $operator)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'license_number'  => 'nullable|string|max:50',
            'phone'           => 'nullable|string|max:20',
            'license_expiry'  => 'nullable|date',
            'activo'          => 'boolean',
        ]);

        $validated['name']           = strtoupper($validated['name']);
        $validated['license_number'] = isset($validated['license_number']) ? strtoupper($validated['license_number']) : null;
        $validated['activo']         = $request->boolean('activo', true);

        $operator->update($validated);

        return response()->json(['success' => true, 'operator' => $operator]);
    }

    public function destroy(Transporter $transporter, Operator $operator)
    {
        $operator->delete();
        return response()->json(['success' => true]);
    }
}
