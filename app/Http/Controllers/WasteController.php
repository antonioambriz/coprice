<?php

namespace App\Http\Controllers;

use App\Models\Waste;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class WasteController extends Controller
{
    /**
     * Muestra la lista principal de residuos.
     */
    public function index(): View
    {
        return view('content.wastes.index');
    }

    /**
     * Muestra el formulario para crear un nuevo residuo.
     */
    public function create(): View
    {
        return view('content.wastes.create');
    }

    /**
     * Almacena un nuevo residuo en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        // Normalizamos el switch de peligrosidad (booleano)
        $request->merge([
            'is_hazardous' => $request->has('is_hazardous') ? 1 : 0,
        ]);

        $validated = $request->validate([
            'description'    => 'required|string|max:255',
            'waste_code'     => 'nullable|string|max:50',
            'unit'           => 'required|string',
            'physical_state' => 'nullable|in:Sólido,Líquido,Semisólido',
            'packaging_type' => 'nullable|in:RR,RNV',
            'default_price'  => 'nullable|numeric|min:0',
            'is_hazardous'   => 'required|boolean',
            'notes'          => 'nullable|string',
        ]);

        $validated['description'] = strtoupper($request->description);
        $validated['waste_code']  = $request->waste_code ? strtoupper($request->waste_code) : null;

        Waste::create($validated);

        return redirect()->route('wastes.index')
            ->with('success', 'Residuo registrado exitosamente.');
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Waste $waste): View
    {
        return view('content.wastes.edit', compact('waste'));
    }

    /**
     * Actualiza los datos del residuo.
     */
    public function update(Request $request, Waste $waste): RedirectResponse
    {
        $request->merge([
            'is_hazardous' => $request->has('is_hazardous') ? 1 : 0,
        ]);

        $validated = $request->validate([
            'description'    => 'required|string|max:255',
            'waste_code'     => 'nullable|string|max:50',
            'unit'           => 'required|string',
            'physical_state' => 'nullable|in:Sólido,Líquido,Semisólido',
            'packaging_type' => 'nullable|in:RR,RNV',
            'default_price'  => 'nullable|numeric|min:0',
            'is_hazardous'   => 'required|boolean',
            'notes'          => 'nullable|string',
        ]);

        $validated['description'] = strtoupper($request->description);
        $validated['waste_code']  = $request->waste_code ? strtoupper($request->waste_code) : null;

        $waste->update($validated);

        return redirect()->route('wastes.index')
            ->with('success', 'Residuo actualizado correctamente.');
    }

    /**
     * Elimina un residuo (Soft Delete).
     * Soluciona el error: Call to undefined method destroy()
     */
    public function destroy(Waste $waste): JsonResponse
    {
        $waste->delete();

        return response()->json([
            'success' => true,
            'message' => 'El residuo ha sido eliminado exitosamente.'
        ]);
    }

    /**
     * Provee los datos en formato JSON para el DataTable.
     */
    public function getData(): JsonResponse
    {
        $wastes = Waste::all();

        return response()->json([
            'data' => $wastes->map(function($waste) {
                return [
                    'id'             => $waste->id,
                    'waste_code'     => $waste->waste_code ?? '—',
                    'description'    => $waste->description,
                    'physical_state' => $waste->physical_state ?? '—',
                    'stage'          => $waste->stage ?? '—',
                    'unit'           => $waste->unit,
                    'packaging_type' => $waste->packaging_type ?? '—',
                    'default_price'  => '$' . number_format($waste->default_price, 2),
                    'is_hazardous'   => (bool)$waste->is_hazardous,
                    'activo'         => true,
                ];
            })
        ]);
    }
}
