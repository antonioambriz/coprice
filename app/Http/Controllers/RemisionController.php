<?php

namespace App\Http\Controllers;

use App\Models\Remision;
use App\Models\Generator;
use App\Models\Withdrawal;
use App\Models\WithdrawalItem;
use Illuminate\Http\Request;

class RemisionController extends Controller
{
    public function index()
    {
        return view('content.remisions.index');
    }

    public function create()
    {
        $generators = Generator::orderBy('company_name')->get();
        return view('content.remisions.create', compact('generators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'remision_number'  => 'required|string|max:100|unique:remisions,remision_number',
            'generator_id'     => 'required|exists:generators,id',
            'sub_generator_id' => 'nullable|exists:sub_generators,id',
            'emission_date'    => 'nullable|date',
            'status'           => 'required|in:BORRADOR,ENVIADA,PAGADA,CANCELADA',
            'notes'            => 'nullable|string',
            'withdrawal_ids'   => 'nullable|array',
            'withdrawal_ids.*' => 'exists:withdrawals,id',
        ]);

        $withdrawalIds = $validated['withdrawal_ids'] ?? [];
        unset($validated['withdrawal_ids']);

        $validated['total'] = WithdrawalItem::whereIn('withdrawal_id', $withdrawalIds)->sum('subtotal');

        $remision = Remision::create($validated);

        if (!empty($withdrawalIds)) {
            Withdrawal::whereIn('id', $withdrawalIds)->update(['remision_id' => $remision->id]);
        }

        return redirect()->route('remisions.index')
            ->with('success', 'Remisión creada y retiros vinculados exitosamente.');
    }

    public function show(Remision $remision)
    {
        $remision->load(['generator', 'subGenerator', 'withdrawals.transporter', 'withdrawals.items.waste']);
        return view('content.remisions.show', compact('remision'));
    }

    public function edit(Remision $remision)
    {
        $generators = Generator::orderBy('company_name')->get();
        return view('content.remisions.edit', compact('remision', 'generators'));
    }

    public function update(Request $request, Remision $remision)
    {
        $validated = $request->validate([
            'remision_number'  => 'required|string|max:100|unique:remisions,remision_number,' . $remision->id,
            'generator_id'     => 'required|exists:generators,id',
            'sub_generator_id' => 'nullable|exists:sub_generators,id',
            'emission_date'    => 'nullable|date',
            'status'           => 'required|in:BORRADOR,ENVIADA,PAGADA,CANCELADA',
            'notes'            => 'nullable|string',
            'withdrawal_ids'   => 'nullable|array',
            'withdrawal_ids.*' => 'exists:withdrawals,id',
        ]);

        $withdrawalIds = $validated['withdrawal_ids'] ?? [];
        unset($validated['withdrawal_ids']);

        // Desvincular retiros removidos
        Withdrawal::where('remision_id', $remision->id)
            ->whereNotIn('id', $withdrawalIds)
            ->update(['remision_id' => null]);

        // Vincular seleccionados
        if (!empty($withdrawalIds)) {
            Withdrawal::whereIn('id', $withdrawalIds)->update(['remision_id' => $remision->id]);
        }

        $validated['total'] = WithdrawalItem::whereIn('withdrawal_id', $withdrawalIds)->sum('subtotal');

        $remision->update($validated);

        return redirect()->route('remisions.index')
            ->with('success', 'Remisión actualizada correctamente.');
    }

    public function destroy(Remision $remision)
    {
        Withdrawal::where('remision_id', $remision->id)->update(['remision_id' => null]);
        $remision->delete();
        return response()->json(['success' => true]);
    }

    public function getData()
    {
        $remisions = Remision::with(['generator', 'subGenerator'])
            ->withCount('withdrawals')
            ->get();

        return response()->json([
            'data' => $remisions->map(fn ($r) => [
                'id'               => $r->id,
                'remision_number'  => $r->remision_number,
                'generator_name'   => $r->generator->company_name ?? '—',
                'sub_generator'    => $r->subGenerator->name ?? '—',
                'emission_date'    => $r->emission_date?->format('d/m/Y') ?? '—',
                'withdrawals_count'=> $r->withdrawals_count,
                'total'            => number_format($r->total, 2),
                'status'           => $r->status,
            ])
        ]);
    }

    public function getWithdrawals(Generator $generator, Remision $remision = null)
    {
        $query = Withdrawal::with(['transporter', 'items'])
            ->where('generator_id', $generator->id)
            ->where(function ($q) use ($remision) {
                $q->whereNull('remision_id');
                if ($remision) {
                    $q->orWhere('remision_id', $remision->id);
                }
            })
            ->orderBy('reception_date', 'desc');

        return response()->json($query->get()->map(fn ($w) => [
            'id'               => $w->id,
            'folio_interno'    => $w->folio_interno,
            'reception_date'   => $w->reception_date?->format('d/m/Y') ?? '—',
            'transporter_name' => $w->transporter->company_name ?? '—',
            'subtotal'         => number_format($w->items->sum('subtotal'), 2),
            'linked'           => $remision ? $w->remision_id === $remision->id : false,
        ]));
    }
}
