<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\Generator;
use App\Models\SubGenerator;
use App\Models\Transporter;
use App\Models\Waste;
use App\Models\FinalDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BitacoraExport;
use App\Exports\SamaExport;
use Yajra\DataTables\Facades\DataTables;

class WithdrawalController extends Controller
{
    public function index()
    {
        return view('content.withdrawals.index');
    }

    public function create()
    {
        $generators        = Generator::orderBy('company_name')->get();
        $transporters      = Transporter::orderBy('company_name')->get();
        $wastes            = Waste::orderBy('description')->get();
        $finalDestinations = FinalDestination::where('activo', true)->orderBy('name')->get();
        // Folio: YYMMDD + consecutivo del día (2 dígitos)
        $hoy         = now()->format('ymd');
        $consecutivo = Withdrawal::whereDate('created_at', now()->toDateString())->count() + 1;
        $nuevoFolio  = $hoy . str_pad($consecutivo, 2, '0', STR_PAD_LEFT);

        return view('content.withdrawals.create', compact('generators', 'transporters', 'wastes', 'nuevoFolio', 'finalDestinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'folio_interno'           => 'required|unique:withdrawals,folio_interno',
            'reception_date'          => 'required|date',
            'generator_id'            => 'required|exists:generators,id',
            'sub_generator_id'        => 'nullable|exists:sub_generators,id',
            'transporter_id'          => 'required|exists:transporters,id',
            'ticket_externo'          => 'nullable|string',
            'folio_salida'            => 'nullable|string',
            'departure_date'          => 'nullable|date',
            'observaciones'           => 'nullable|string',
            'treatment_stage'         => 'nullable|string',
            'final_destination_id'    => 'nullable|exists:final_destinations,id',
            'is_estimated_weight'          => 'boolean',
            'requires_manifest'            => 'boolean',
            'requires_transport_equipment' => 'boolean',
            'transport_equipment_id'       => 'nullable|exists:transport_equipments,id',
            'payment_status'               => 'required|in:PENDIENTE,PAGADO',
            'items'                   => 'required|array|min:1',
            'items.*.waste_id'        => 'required|exists:wastes,id',
            'items.*.quantity'        => 'required|numeric|min:0',
            'items.*.unit'            => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $items = $validated['items'];
                unset($validated['items']);

                $validated['user_id'] = auth()->id();
                $withdrawal = Withdrawal::create($validated);

                foreach ($items as $item) {
                    if (!empty($item['container_capacity']) && !empty($item['container_unit'])) {
                        $item['container_capacity'] = $item['container_capacity'] . ' ' . $item['container_unit'];
                    }
                    unset($item['container_unit']);
                    $withdrawal->items()->create($item);
                }
            });

            return redirect()->route('withdrawals.index')->with('success', 'Retiro registrado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar el retiro: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load(['generator', 'subGenerator', 'transporter', 'manifest', 'finalDestination', 'items.waste']);
        return view('content.withdrawals.show', compact('withdrawal'));
    }

    public function edit(Withdrawal $withdrawal)
    {
        $withdrawal->load(['items.waste', 'generator', 'subGenerator', 'transporter', 'transportEquipment']);

        $generators        = Generator::orderBy('company_name')->get();
        $transporters      = Transporter::orderBy('company_name')->get();
        $wastes            = Waste::orderBy('description')->get();
        $finalDestinations = FinalDestination::where('activo', true)->orderBy('name')->get();

        return view('content.withdrawals.edit', compact(
            'withdrawal', 'generators', 'transporters', 'wastes', 'finalDestinations'
        ));
    }

    public function update(Request $request, Withdrawal $withdrawal)
    {
        $validated = $request->validate([
            'folio_interno'                => 'required|unique:withdrawals,folio_interno,' . $withdrawal->id,
            'reception_date'               => 'required|date',
            'generator_id'                 => 'required|exists:generators,id',
            'sub_generator_id'             => 'nullable|exists:sub_generators,id',
            'transporter_id'               => 'required|exists:transporters,id',
            'ticket_externo'               => 'nullable|string',
            'folio_salida'                 => 'nullable|string',
            'departure_date'               => 'nullable|date',
            'observaciones'                => 'nullable|string',
            'treatment_stage'              => 'nullable|string',
            'final_destination_id'         => 'nullable|exists:final_destinations,id',
            'is_estimated_weight'          => 'boolean',
            'requires_manifest'            => 'boolean',
            'requires_transport_equipment' => 'boolean',
            'transport_equipment_id'       => 'nullable|exists:transport_equipments,id',
            'payment_status'               => 'required|in:PENDIENTE,PAGADO',
            'items'                        => 'required|array|min:1',
            'items.*.waste_id'             => 'required|exists:wastes,id',
            'items.*.quantity'             => 'required|numeric|min:0',
            'items.*.unit'                 => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($validated, $withdrawal) {
                $items = $validated['items'];
                unset($validated['items']);

                $withdrawal->update($validated);

                $withdrawal->items()->delete();
                foreach ($items as $item) {
                    if (!empty($item['container_capacity']) && !empty($item['container_unit'])) {
                        $item['container_capacity'] = $item['container_capacity'] . ' ' . $item['container_unit'];
                    }
                    unset($item['container_unit']);
                    $withdrawal->items()->create($item);
                }
            });

            return redirect()->route('withdrawals.show', $withdrawal)->with('success', 'Retiro actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el retiro: ' . $e->getMessage())->withInput();
        }
    }

    public function getData()
    {
        $query = Withdrawal::query()
            ->select([
                'withdrawals.id',
                'withdrawals.reception_date as fecha',
                'withdrawals.folio_interno',
                'generators.company_name as generator_name',
                'sub_generators.name as sub_generator_name',
                'transporters.company_name as transporter_name',
                'manifests.manifest_number as manifest',
                'withdrawals.payment_status as status',
                'users.name as user_name',
            ])
            ->leftJoin('generators', 'generators.id', '=', 'withdrawals.generator_id')
            ->leftJoin('sub_generators', 'sub_generators.id', '=', 'withdrawals.sub_generator_id')
            ->leftJoin('transporters', 'transporters.id', '=', 'withdrawals.transporter_id')
            ->leftJoin('manifests', 'manifests.id', '=', 'withdrawals.manifest_id')
            ->leftJoin('users', 'users.id', '=', 'withdrawals.user_id');

        return DataTables::of($query)
            ->editColumn('generator_name', fn ($w) => $w->generator_name ?? 'N/A')
            ->editColumn('transporter_name', fn ($w) => $w->transporter_name ?? 'N/A')
            ->editColumn('manifest', fn ($w) => $w->manifest ?? 'S/M')
            ->editColumn('user_name', fn ($w) => $w->user_name ?? '—')
            ->editColumn('sub_generator_name', fn ($w) => $w->sub_generator_name ?? '—')
            ->addColumn('activo', fn () => true)
            ->filterColumn('fecha', function ($query, $keyword) {
                $query->whereRaw('withdrawals.reception_date like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->whereRaw('withdrawals.payment_status like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('generator_name', function ($query, $keyword) {
                $query->whereRaw('generators.company_name like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('transporter_name', function ($query, $keyword) {
                $query->whereRaw('transporters.company_name like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('manifest', function ($query, $keyword) {
                $query->whereRaw('manifests.manifest_number like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('user_name', function ($query, $keyword) {
                $query->whereRaw('users.name like ?', ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function destroy(Withdrawal $withdrawal)
    {
        $withdrawal->delete();
        return response()->json(['success' => true]);
    }

    public function exportExcel()
    {
        if (Withdrawal::count() === 0) {
            return back()->with('error', 'No hay retiros registrados para exportar.');
        }

        return Excel::download(new BitacoraExport, 'bitacora_retiros.xlsx');
    }

    public function exportSama(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to'   => 'required|date|after_or_equal:from',
        ]);

        $from     = $request->from;
        $to       = $request->to;
        $filename = 'SAMA_' . str_replace('-', '', $from) . '_' . str_replace('-', '', $to) . '.xlsx';

        return Excel::download(new SamaExport($from, $to), $filename);
    }

    /**
     * Devuelve los sub-generadores de un generador (para carga dinámica en formulario).
     */
    public function getSubGenerators(Generator $generator)
    {
        return response()->json(
            $generator->subGenerators()->where('status', true)->orderBy('name')->get(['id', 'name', 'assumed_weight', 'requires_manifest'])
        );
    }

    /**
     * Devuelve los equipos de transporte activos de un transportista (para carga dinámica en formulario).
     */
    public function getTransportEquipments(Transporter $transporter)
    {
        return response()->json(
            $transporter->transportEquipments()->where('activo', true)->orderBy('description')->get(['id', 'description', 'plate_number'])
        );
    }
}
