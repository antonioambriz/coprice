<?php

namespace App\Http\Controllers;

use App\Models\Transporter;
use App\Models\WastePrice;
use App\Models\Waste;
use Illuminate\Http\Request;

class WastePriceController extends Controller
{
    public function forTransporter(Transporter $transporter)
    {
        $wastes = Waste::orderBy('description')->get();

        $prices = WastePrice::where('transporter_id', $transporter->id)
            ->pluck('price_per_ton', 'waste_id');

        return view('content.waste-prices.index', [
            'subject'     => $transporter,
            'subjectName' => $transporter->company_name,
            'backRoute'   => route('transporters.edit', $transporter),
            'saveRoute'   => route('waste-prices.saveTransporter', $transporter),
            'wastes'      => $wastes,
            'prices'      => $prices,
        ]);
    }

    public function saveTransporter(Request $request, Transporter $transporter)
    {
        foreach ($request->input('prices', []) as $wasteId => $value) {
            $price = ($value !== null && $value !== '') ? (float) $value : null;

            if ($price !== null && $price > 0) {
                WastePrice::updateOrCreate(
                    ['transporter_id' => $transporter->id, 'waste_id' => (int) $wasteId],
                    ['price_per_ton' => $price]
                );
            } else {
                WastePrice::where('transporter_id', $transporter->id)
                    ->where('waste_id', (int) $wasteId)
                    ->delete();
            }
        }

        return redirect()
            ->route('waste-prices.transporter', $transporter)
            ->with('success', 'Precios actualizados correctamente.');
    }
}
