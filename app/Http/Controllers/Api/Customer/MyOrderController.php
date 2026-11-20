<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;

class MyOrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:api',
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get transactions
        $transactions = Transaction::query()
            ->with(['customer', 'shipping', 'transactionDetails.product'])
            ->where('customer_id', Auth::guard('api')->user()->id)
            ->latest()
            ->paginate(5);


        return response()->json([
            'success' => true,
            'message' => 'List Pesanan',
            'data' => $transactions,
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $snap_token)
    {
        // get transaction
        $transaction = Transaction::query()
            ->with(['customer', 'shipping', 'transactionDetails.product'])
            ->where('snap_token', $snap_token)
            ->where('customer_id', Auth::guard('api')->user()->id)
            ->first();


        if ($transaction) {

            return response()->json([
                'success' => true,
                'message' => 'Detail Pesanan',
                'data' => $transaction,
            ], 200);

        }


        return response()->json([
            'success' => false,
            'message' => 'Detail Pesanan Tidak Ditemukan',
        ], 404);
    }
}