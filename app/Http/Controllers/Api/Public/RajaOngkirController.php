<?php

namespace App\Http\Controllers\Api\Public;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    /**
     * SEARCH DESTINATION
     */
    public function searchDestination(Request $request)
    {
        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key')
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
            'search' => $request->search,
            'limit' => 100,
            'offset' => 0
        ]);

        // Menampilkan respons asli dari RajaOngkir
        return response()->json([
            'status' => $response->status(),
            'response' => $response->body()
        ]);
    }

    /**
     * CHECK ONGKIR
     */
    public function checkOngkir(Request $request)
    {
        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key')
        ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [

            'origin' => $request->origin,
            'destination' => $request->destination,
            'weight' => $request->weight,
            'courier' => $request->courier

        ]);

        return response()->json($response->json(), $response->status());
    }
}
