<?php

namespace App\Http\Controllers;

use App\Models\Generator;
use App\Models\SubGenerator;
use App\Models\Transporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneratorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['data' => Generator::all()]);
        }

        return view('content.generators.index');
    }

    public function create()
    {
        $transporters = Transporter::orderBy('company_name')->get();
        return view('content.generators.create', compact('transporters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name'                          => 'required|string|max:255',
            'authorization'                         => 'nullable|string|max:13',
            'address'                               => 'nullable|string',
            'preferred_transporter_id'              => 'nullable|exists:transporters,id',
            'sub_generators.*.name'                 => 'required_if:has_sub_generators,1|string|max:255',
            'sub_generators.*.assumed_weight'       => 'nullable|numeric|min:0',
            'sub_generators.*.report_frequency'     => 'nullable|in:sporadic,weekly,monthly',
        ]);

        DB::transaction(function () use ($request) {
            $generator = Generator::create([
                'company_name'             => $request->company_name,
                'authorization'            => $request->authorization,
                'address'                  => $request->address,
                'has_sub_generators'       => $request->boolean('has_sub_generators'),
                'requires_manifest'        => $request->boolean('requires_manifest'),
                'preferred_transporter_id' => $request->preferred_transporter_id ?: null,
            ]);

            if ($request->boolean('has_sub_generators') && $request->filled('sub_generators')) {
                foreach ($request->sub_generators as $sg) {
                    $generator->subGenerators()->create([
                        'name'              => $sg['name'],
                        'assumed_weight'    => $sg['assumed_weight'] ?? null,
                        'report_frequency'  => $sg['report_frequency'] ?? 'sporadic',
                        'requires_manifest' => !empty($sg['requires_manifest']),
                        'status'            => true,
                    ]);
                }
            }
        });

        return redirect()->route('generators.index')->with('success', 'Generador creado correctamente.');
    }

    public function show(Generator $generator)
    {
        //
    }

    public function edit(Generator $generator)
    {
        $transporters = Transporter::orderBy('company_name')->get();
        return view('content.generators.edit', compact('generator', 'transporters'));
    }

    public function update(Request $request, Generator $generator)
    {
        $request->validate([
            'company_name'                          => 'required|max:255',
            'authorization'                         => 'nullable|max:13',
            'address'                               => 'nullable',
            'preferred_transporter_id'              => 'nullable|exists:transporters,id',
            'sub_generators.*.name'                 => 'required_if:has_sub_generators,1|string|max:255',
            'sub_generators.*.assumed_weight'       => 'nullable|numeric|min:0',
            'sub_generators.*.report_frequency'     => 'nullable|in:sporadic,weekly,monthly',
        ]);

        DB::transaction(function () use ($request, $generator) {
            $generator->update([
                'company_name'             => $request->company_name,
                'authorization'            => $request->authorization,
                'address'                  => $request->address,
                'has_sub_generators'       => $request->boolean('has_sub_generators'),
                'requires_manifest'        => $request->boolean('requires_manifest'),
                'preferred_transporter_id' => $request->preferred_transporter_id ?: null,
            ]);

            if ($request->filled('sub_generators')) {
                foreach ($request->sub_generators as $sg) {
                    if (!empty($sg['_delete']) && !empty($sg['id'])) {
                        SubGenerator::find($sg['id'])?->delete();
                        continue;
                    }

                    $data = [
                        'name'              => $sg['name'] ?? '',
                        'assumed_weight'    => $sg['assumed_weight'] ?? null,
                        'report_frequency'  => $sg['report_frequency'] ?? 'sporadic',
                        'requires_manifest' => !empty($sg['requires_manifest']),
                        'status'            => !empty($sg['status']),
                    ];

                    if (!empty($sg['id'])) {
                        SubGenerator::find($sg['id'])?->update($data);
                    } else {
                        $generator->subGenerators()->create($data);
                    }
                }
            }
        });

        return redirect()->route('generators.index')->with('success', 'Generador actualizado correctamente.');
    }

    public function destroy(Generator $generator)
    {
        $generator->delete();
        return response()->json(['success' => true]);
    }
}
