<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\WastePrice;
use App\Models\Waste;
use Illuminate\Http\Request;

class WastePriceController extends Controller
{
    public function forClient(Client $client)
    {
        $wastes = Waste::orderBy('description')->get();

        $prices = WastePrice::where('client_id', $client->id)
            ->pluck('price_per_ton', 'waste_id');

        return view('content.waste-prices.index', [
            'subject'     => $client,
            'subjectName' => $client->company_name,
            'backRoute'   => route('clients.edit', $client),
            'saveRoute'   => route('waste-prices.saveClient', $client),
            'wastes'      => $wastes,
            'prices'      => $prices,
        ]);
    }

    public function saveClient(Request $request, Client $client)
    {
        foreach ($request->input('prices', []) as $wasteId => $value) {
            $price = ($value !== null && $value !== '') ? (float) $value : null;

            if ($price !== null && $price > 0) {
                WastePrice::updateOrCreate(
                    ['client_id' => $client->id, 'waste_id' => (int) $wasteId],
                    ['price_per_ton' => $price]
                );
            } else {
                WastePrice::where('client_id', $client->id)
                    ->where('waste_id', (int) $wasteId)
                    ->delete();
            }
        }

        return redirect()
            ->route('waste-prices.client', $client)
            ->with('success', 'Precios actualizados correctamente.');
    }
}
