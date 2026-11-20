<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        // Mengambil data notifikasi Midtrans
        $notification = json_decode($request->getContent(), true);

        if (
            !is_array($notification) ||
            !isset(
                $notification['order_id'],
                $notification['status_code'],
                $notification['gross_amount'],
                $notification['signature_key'],
                $notification['transaction_status']
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid notification payload'
            ], 400);
        }

        // Membuat signature untuk validasi
        $signatureKey = hash(
            'sha512',
            $notification['order_id'] .
            $notification['status_code'] .
            $notification['gross_amount'] .
            config('midtrans.server_key')
        );

        // Memvalidasi signature dari Midtrans
        if (!hash_equals($signatureKey, $notification['signature_key'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid signature'
            ], 403);
        }

        // Mencari transaksi berdasarkan invoice
        $transaction = Transaction::where(
            'invoice',
            $notification['order_id']
        )->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        $transactionStatus = $notification['transaction_status'];
        $fraudStatus = $notification['fraud_status'] ?? null;

        // Memperbarui status transaksi
        if ($transactionStatus === 'settlement') {

            $transaction->status = 'success';

        } elseif ($transactionStatus === 'capture') {

            $transaction->status = $fraudStatus === 'accept'
                ? 'success'
                : 'pending';

        } elseif ($transactionStatus === 'pending') {

            $transaction->status = 'pending';

        } elseif ($transactionStatus === 'deny') {

            $transaction->status = 'failed';

        } elseif ($transactionStatus === 'expire') {

            $transaction->status = 'expired';

        } elseif ($transactionStatus === 'cancel') {

            $transaction->status = 'failed';

        } else {

            return response()->json([
                'success' => true,
                'message' => 'Notification status ignored'
            ]);
        }

        // Menyimpan perubahan status ke database
        $transaction->save();

        return response()->json([
            'success' => true,
            'message' => 'Notification processed successfully'
        ]);
    }
}
