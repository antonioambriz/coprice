<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contact;
use App\Models\Generator;
use App\Models\Transporter;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Mapeo: nombre del parámetro de ruta => modelo padre correspondiente
    private const PARENT_MODELS = [
        'generator'   => Generator::class,
        'transporter' => Transporter::class,
        'client'      => Client::class,
    ];

    /**
     * Resuelve el modelo "padre" (Generador, Transportista o Cliente) a partir
     * del parámetro de ruta correspondiente. No usamos binding implícito de
     * Laravel porque los métodos reciben (Request $request) sin un parámetro
     * tipado por modelo, así que el valor de ruta llega como ID crudo.
     */
    private function resolveContactable(Request $request)
    {
        foreach (self::PARENT_MODELS as $param => $modelClass) {
            if ($request->route($param) !== null) {
                return $modelClass::findOrFail($request->route($param));
            }
        }

        abort(404);
    }

    public function getData(Request $request)
    {
        $contactable = $this->resolveContactable($request);

        return response()->json([
            'data' => $contactable->contacts()->orderByDesc('is_primary')->get()->map(fn ($c) => [
                'id'         => $c->id,
                'name'       => $c->name,
                'position'   => $c->position ?? '—',
                'phone'      => $c->phone ?? '—',
                'email'      => $c->email ?? '—',
                'is_primary' => $c->is_primary,
            ])
        ]);
    }

    public function store(Request $request)
    {
        $contactable = $this->resolveContactable($request);

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'position'   => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
            'is_primary' => 'boolean',
        ]);

        $validated['is_primary'] = $request->boolean('is_primary');

        $contact = $contactable->contacts()->create($validated);

        return response()->json(['success' => true, 'contact' => $contact]);
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'position'   => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
            'is_primary' => 'boolean',
        ]);

        $validated['is_primary'] = $request->boolean('is_primary');

        $contact->update($validated);

        return response()->json(['success' => true, 'contact' => $contact]);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(['success' => true]);
    }
}
