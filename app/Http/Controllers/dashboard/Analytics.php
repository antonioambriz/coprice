<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\SubGenerator;
use App\Models\WithdrawalItem;
use App\Models\WastePrice;
use Illuminate\Support\Facades\DB;

class Analytics extends Controller
{
    public function index()
    {
        $petarWidget = $this->buildPetarWidget();

        return view('content.dashboard.dashboards-analytics', compact('petarWidget'));
    }

    private function buildPetarWidget(): array
    {
        $petar = SubGenerator::where('name', 'like', '%Petar%')->first();

        if (! $petar) {
            return ['found' => false];
        }

        // Retiros pendientes de remisionar agrupados por residuo, cliente y unidad
        $items = WithdrawalItem::select([
                'wastes.description as waste_name',
                'withdrawal_items.waste_id',
                'withdrawal_items.unit',
                'withdrawals.client_id',
                DB::raw('SUM(withdrawal_items.quantity) as total_qty'),
            ])
            ->join('withdrawals', 'withdrawal_items.withdrawal_id', '=', 'withdrawals.id')
            ->join('wastes', 'withdrawal_items.waste_id', '=', 'wastes.id')
            ->whereNull('withdrawals.deleted_at')
            ->whereNull('withdrawal_items.deleted_at')
            ->where('withdrawals.sub_generator_id', $petar->id)
            ->whereNull('withdrawals.remision_id')
            ->groupBy('withdrawal_items.waste_id', 'wastes.description', 'withdrawal_items.unit', 'withdrawals.client_id')
            ->orderBy('wastes.description')
            ->get();

        // Precios acordados indexados por (client_id, waste_id). Retiros legado
        // sin client_id simplemente no calzan ninguna llave aquí y caen al
        // precio base de Waste::default_price más abajo.
        $clientIds    = $items->pluck('client_id')->unique()->filter();
        $agreedPrices = WastePrice::whereIn('client_id', $clientIds)
            ->get()
            ->keyBy(fn($p) => $p->client_id . '_' . $p->waste_id);

        // Precios por defecto del residuo como fallback
        $defaultPrices = \App\Models\Waste::whereIn('id', $items->pluck('waste_id'))
            ->pluck('default_price', 'id');

        // Agrupar por residuo sumando toneladas e importe
        $rowsByWaste = [];
        $total = 0;

        foreach ($items as $item) {
            // Convertir cantidad a toneladas según unidad
            $tons = match (strtoupper($item->unit)) {
                'TON'   => (float) $item->total_qty,
                'KG'    => (float) $item->total_qty / 1000,
                default => (float) $item->total_qty / 1000,
            };

            $key     = $item->client_id . '_' . $item->waste_id;
            $price   = $agreedPrices[$key]->price_per_ton ?? $defaultPrices[$item->waste_id] ?? 0;
            $importe = $tons * $price;
            $total  += $importe;

            $wasteId = $item->waste_id;
            if (!isset($rowsByWaste[$wasteId])) {
                $rowsByWaste[$wasteId] = [
                    'waste'   => $item->waste_name,
                    'tons'    => 0,
                    'importe' => 0,
                ];
            }
            $rowsByWaste[$wasteId]['tons']    += $tons;
            $rowsByWaste[$wasteId]['importe'] += $importe;
        }

        return [
            'found'  => true,
            'name'   => $petar->name,
            'rows'   => array_values($rowsByWaste),
            'total'  => $total,
        ];
    }
}
