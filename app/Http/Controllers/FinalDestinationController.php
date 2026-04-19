<?php

namespace App\Http\Controllers;

use App\Models\FinalDestination;
use Illuminate\Http\Request;

class FinalDestinationController extends Controller
{
    public function index()
    {
        return view('content.final-destinations.index');
    }

    public function create()
    {
        return view('content.final-destinations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'authorization_number' => 'nullable|string|max:100',
            'activo'               => 'boolean',
        ]);

        $validated['name']                 = strtoupper($validated['name']);
        $validated['authorization_number'] = strtoupper($validated['authorization_number'] ?? '');
        $validated['activo']               = $request->boolean('activo', true);

        FinalDestination::create($validated);

        return redirect()->route('final-destinations.index')
            ->with('success', 'Destino final creado exitosamente.');
    }

    public function edit(FinalDestination $finalDestination)
    {
        return view('content.final-destinations.edit', compact('finalDestination'));
    }

    public function update(Request $request, FinalDestination $finalDestination)
    {
        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'authorization_number' => 'nullable|string|max:100',
            'activo'               => 'boolean',
        ]);

        $validated['name']                 = strtoupper($validated['name']);
        $validated['authorization_number'] = strtoupper($validated['authorization_number'] ?? '');
        $validated['activo']               = $request->boolean('activo', true);

        $finalDestination->update($validated);

        return redirect()->route('final-destinations.index')
            ->with('success', 'Destino final actualizado correctamente.');
    }

    public function destroy(FinalDestination $finalDestination)
    {
        $finalDestination->delete();
        return response()->json(['success' => true]);
    }

    public function getData()
    {
        $destinations = FinalDestination::all();

        return response()->json([
            'data' => $destinations->map(fn ($d) => [
                'id'                   => $d->id,
                'name'                 => $d->name,
                'authorization_number' => $d->authorization_number ?? '—',
                'activo'               => $d->activo,
            ])
        ]);
    }
}
