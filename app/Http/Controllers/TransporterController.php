<?php

namespace App\Http\Controllers;

use App\Models\Transporter;
use Illuminate\Http\Request;

class TransporterController extends Controller
{
    /**
     * Muestra la lista principal de transportistas.
     */
    public function index()
    {
        return view('content.transporters.index');
    }

    /**
     * Muestra el formulario para crear un nuevo transportista.
     */
    public function create()
    {
        return view('content.transporters.create');
    }

    /**
     * Almacena el transportista en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name'         => 'required|string|max:255',
            'rfc'                  => 'nullable|string|max:13|unique:transporters,rfc',
            'authorization_number' => 'nullable|string|max:100',
            'contact_person'       => 'nullable|string|max:255',
            'email_remissions'     => 'nullable|email|max:255',
            'address'              => 'nullable|string',
            'activo'               => 'required|integer|in:0,1',
        ]);

        // Estándar Citio: Mayúsculas
        $validated['company_name'] = strtoupper($request->company_name);
        $validated['authorization_number'] = strtoupper($request->authorization_number ?? '');
        $validated['contact_person'] = strtoupper($request->contact_person);
        $validated['address'] = strtoupper($request->address);

        Transporter::create($validated);

        return redirect()->route('transporters.index')
            ->with('success', 'Transportista creado exitosamente.');
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Transporter $transporter)
    {
        return view('content.transporters.edit', compact('transporter'));
    }

    /**
     * Actualiza los datos del transportista.
     */
    public function update(Request $request, Transporter $transporter)
    {
        $validated = $request->validate([
            'company_name'         => 'required|string|max:255',
            'rfc'                  => 'nullable|string|max:13|unique:transporters,rfc,' . $transporter->id,
            'authorization_number' => 'nullable|string|max:100',
            'contact_person'       => 'nullable|string|max:255',
            'email_remissions'     => 'nullable|email|max:255',
            'address'              => 'nullable|string',
            'activo'               => 'required|integer|in:0,1',
        ]);

        $validated['company_name'] = strtoupper($request->company_name);
        $validated['authorization_number'] = strtoupper($request->authorization_number ?? '');
        $validated['contact_person'] = strtoupper($request->contact_person);
        $validated['address'] = strtoupper($request->address);

        $transporter->update($validated);

        return redirect()->route('transporters.index')
            ->with('success', 'Transportista actualizado correctamente.');
    }

    /**
     * Eliminación lógica (Soft Delete).
     */
    public function destroy(Transporter $transporter)
    {
        $transporter->delete();

        return redirect()->route('transporters.index')
            ->with('success', 'Transportista enviado a la papelera.');
    }

    /**
     * Provee los datos para el listado (JSON).
     */
    public function getTransportersData()
    {
        $transporters = Transporter::all();

        return response()->json([
            'data' => $transporters->map(function($t) {
                return [
                    'id'                   => $t->id,
                    'company_name'         => $t->company_name,
                    'rfc'                  => $t->rfc ?? 'N/A',
                    'authorization_number' => $t->authorization_number ?? '—',
                    'contact_person'       => $t->contact_person,
                    'email_remissions'     => $t->email_remissions,
                    'address'              => $t->address,
                    'status'               => $t->activo
                ];
            })
        ]);
    }

    /**
     * Opcional: Restaurar un transportista eliminado.
     */
    public function restore($id)
    {
        $transporter = Transporter::withTrashed()->findOrFail($id);
        $transporter->restore();

        return redirect()->route('transporters.index')
            ->with('success', 'Transportista restaurado con éxito.');
    }
}
