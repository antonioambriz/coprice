<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\FinalDestination;
use App\Models\Generator;
use App\Models\Waste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        return view('content.clients.index');
    }

    public function create()
    {
        return view('content.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name'   => 'required|string|max:255',
            'rfc'            => 'nullable|string|max:13',
            'contact_person' => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
            'activo'         => 'boolean',
        ]);

        $validated['activo']       = $request->boolean('activo');
        $validated['company_name'] = strtoupper($request->company_name);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    public function edit(Client $client)
    {
        $generators        = Generator::orderBy('company_name')->get(['id', 'company_name']);
        $wastes            = Waste::orderBy('description')->get(['id', 'description']);
        $finalDestinations = FinalDestination::where('activo', true)->get(['id', 'name', 'authorization_number']);

        $rawRels = DB::table('client_generator_wastes')
            ->where('client_id', $client->id)
            ->get();

        $assignedGeneratorIds = $rawRels->pluck('generator_id')->unique()->values();

        $wastesByGenerator = $rawRels->groupBy('generator_id')->map(fn($rows) => [
            'waste_ids'            => $rows->pluck('waste_id')->toArray(),
            'final_destination_id' => $rows->first()->final_destination_id,
        ]);

        $assignedGenerators = $generators->whereIn('id', $assignedGeneratorIds)->values();

        return view('content.clients.edit', compact(
            'client',
            'generators',
            'wastes',
            'finalDestinations',
            'assignedGenerators',
            'wastesByGenerator'
        ));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'company_name'   => 'required|string|max:255',
            'rfc'            => 'nullable|string|max:13',
            'contact_person' => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
            'activo'         => 'boolean',
        ]);

        $validated['activo']       = $request->boolean('activo');
        $validated['company_name'] = strtoupper($request->company_name);

        $client->update($validated);

        return redirect()->route('clients.edit', $client)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function saveGeneratorWastes(Request $request, Client $client)
    {
        $request->validate([
            'relationships'                       => 'array',
            'relationships.*.generator_id'        => 'required|exists:generators,id',
            'relationships.*.waste_ids'           => 'array',
            'relationships.*.waste_ids.*'         => 'exists:wastes,id',
            'relationships.*.final_destination_id'=> 'nullable|exists:final_destinations,id',
        ]);

        DB::table('client_generator_wastes')->where('client_id', $client->id)->delete();

        $rows = [];
        foreach ($request->relationships ?? [] as $rel) {
            foreach ($rel['waste_ids'] ?? [] as $wasteId) {
                $rows[] = [
                    'client_id'            => $client->id,
                    'generator_id'         => $rel['generator_id'],
                    'sub_generator_id'     => null,
                    'waste_id'             => $wasteId,
                    'final_destination_id' => $rel['final_destination_id'] ?: null,
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ];
            }
        }

        if ($rows) {
            DB::table('client_generator_wastes')->insert($rows);
        }

        return response()->json(['success' => true, 'message' => 'Relaciones guardadas correctamente.']);
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json(['success' => true, 'message' => 'Cliente eliminado correctamente.']);
    }

    public function getData()
    {
        $clients = Client::all();

        return response()->json([
            'data' => $clients->map(fn($c) => [
                'id'             => $c->id,
                'company_name'   => $c->company_name,
                'rfc'            => $c->rfc ?? '—',
                'contact_person' => $c->contact_person ?? '—',
                'email'          => $c->email ?? '—',
                'activo'         => $c->activo,
            ])
        ]);
    }
}
