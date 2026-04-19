<?php

namespace App\Http\Controllers;

use App\Models\Manifest;
use App\Models\Generator;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class ManifestController extends Controller
{
    public function index()
    {
        return view('content.manifests.index');
    }

    public function create()
    {
        $generators = Generator::orderBy('company_name')->get();
        return view('content.manifests.create', compact('generators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'manifest_number'  => 'required|string|max:100|unique:manifests,manifest_number',
            'generator_id'     => 'required|exists:generators,id',
            'sub_generator_id' => 'nullable|exists:sub_generators,id',
            'period_start'     => 'nullable|date',
            'period_end'       => 'nullable|date|after_or_equal:period_start',
            'generated'        => 'boolean',
            'notes'            => 'nullable|string',
            'withdrawal_ids'   => 'nullable|array',
            'withdrawal_ids.*' => 'exists:withdrawals,id',
        ]);

        $validated['generated'] = $request->boolean('generated');
        $withdrawalIds = $validated['withdrawal_ids'] ?? [];
        unset($validated['withdrawal_ids']);

        $manifest = Manifest::create($validated);

        if (!empty($withdrawalIds)) {
            Withdrawal::whereIn('id', $withdrawalIds)->update(['manifest_id' => $manifest->id]);
        }

        return redirect()->route('manifests.index')
            ->with('success', 'Manifiesto creado y retiros vinculados exitosamente.');
    }

    public function show(Manifest $manifest)
    {
        $manifest->load(['generator', 'subGenerator', 'withdrawals.transporter', 'withdrawals.items.waste']);
        return view('content.manifests.show', compact('manifest'));
    }

    public function edit(Manifest $manifest)
    {
        $generators = Generator::orderBy('company_name')->get();
        return view('content.manifests.edit', compact('manifest', 'generators'));
    }

    public function update(Request $request, Manifest $manifest)
    {
        $validated = $request->validate([
            'manifest_number'  => 'required|string|max:100|unique:manifests,manifest_number,' . $manifest->id,
            'generator_id'     => 'required|exists:generators,id',
            'sub_generator_id' => 'nullable|exists:sub_generators,id',
            'period_start'     => 'nullable|date',
            'period_end'       => 'nullable|date|after_or_equal:period_start',
            'generated'        => 'boolean',
            'notes'            => 'nullable|string',
            'withdrawal_ids'   => 'nullable|array',
            'withdrawal_ids.*' => 'exists:withdrawals,id',
        ]);

        $validated['generated'] = $request->boolean('generated');
        $withdrawalIds = $validated['withdrawal_ids'] ?? [];
        unset($validated['withdrawal_ids']);

        $manifest->update($validated);

        // Desvincular retiros que ya no están seleccionados
        Withdrawal::where('manifest_id', $manifest->id)
            ->whereNotIn('id', $withdrawalIds)
            ->update(['manifest_id' => null]);

        // Vincular los seleccionados
        if (!empty($withdrawalIds)) {
            Withdrawal::whereIn('id', $withdrawalIds)->update(['manifest_id' => $manifest->id]);
        }

        return redirect()->route('manifests.index')
            ->with('success', 'Manifiesto actualizado correctamente.');
    }

    public function destroy(Manifest $manifest)
    {
        // Desvincular retiros antes de eliminar
        Withdrawal::where('manifest_id', $manifest->id)->update(['manifest_id' => null]);
        $manifest->delete();
        return response()->json(['success' => true]);
    }

    public function getData()
    {
        $manifests = Manifest::with(['generator', 'subGenerator'])
            ->withCount('withdrawals')
            ->get();

        return response()->json([
            'data' => $manifests->map(fn ($m) => [
                'id'                => $m->id,
                'manifest_number'   => $m->manifest_number,
                'generator_name'    => $m->generator->company_name ?? '—',
                'sub_generator'     => $m->subGenerator->name ?? '—',
                'period'            => $m->period_start && $m->period_end
                    ? $m->period_start->format('d/m/Y') . ' – ' . $m->period_end->format('d/m/Y')
                    : ($m->period_start ? $m->period_start->format('d/m/Y') : '—'),
                'withdrawals_count' => $m->withdrawals_count,
                'generated'         => $m->generated,
            ])
        ]);
    }

    /**
     * Retorna los retiros sin manifiesto de un generador,
     * más los ya vinculados al manifiesto actual (para edición).
     */
    public function getWithdrawals(Generator $generator, Manifest $manifest = null)
    {
        $query = Withdrawal::with('transporter')
            ->where('generator_id', $generator->id)
            ->where(function ($q) use ($manifest) {
                $q->whereNull('manifest_id');
                if ($manifest) {
                    $q->orWhere('manifest_id', $manifest->id);
                }
            })
            ->orderBy('reception_date', 'desc');

        return response()->json($query->get()->map(fn ($w) => [
            'id'               => $w->id,
            'folio_interno'    => $w->folio_interno,
            'reception_date'   => $w->reception_date?->format('d/m/Y') ?? '—',
            'transporter_name' => $w->transporter->company_name ?? '—',
            'linked'           => $manifest ? $w->manifest_id === $manifest->id : false,
        ]));
    }
}
