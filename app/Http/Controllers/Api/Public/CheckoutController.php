<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Shipping;

use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    /**
     * CHECKOUT CUSTOMER
     */
    public function store(Request $request)
    {
        // Validasi data checkout
        $validated = $request->validate([
            'province_name' => 'required|string',
            'city_name' => 'required|string',
            'district_name' => 'required|string',
            'subdistrict_name' => 'required|string',
            'zip_code' => 'required|integer',
            'full_address' => 'required|string',

            'shipping_courier' => 'required|string',
            'shipping_service' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        // Mengambil customer yang sedang login
        $customer = Auth::guard('api')->user();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer belum login',
            ], 401);
        }

        // Mengambil data cart customer
        $carts = Cart::with('product')
            ->where('customer_id', $customer->id)
            ->get();

        if ($carts->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang masih kosong',
            ], 400);
        }

        // Menghitung total harga dan berat
        $total = 0;
        $weight = 0;

        foreach ($carts as $cart) {
            if (!$cart->product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan',
                ], 404);
            }

            $total += $cart->product->price * $cart->qty;
            $weight += $cart->product->weight * $cart->qty;
        }

        // Menghitung total keseluruhan
        $shippingCost = (int) $validated['shipping_cost'];
        $grandTotal = $total + $shippingCost;

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
        Config::$is3ds = (bool) config('midtrans.is_3ds', true);

        if (
            empty(Config::$serverKey) ||
            Config::$serverKey === 'paste_server_key_midtrans_disini'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Midtrans Server Key belum dikonfigurasi',
            ], 500);
        }

        try {
            $result = DB::transaction(function () use (
                $customer,
                $validated,
                $carts,
                $total,
                $weight,
                $shippingCost,
                $grandTotal
            ) {

                // Membuat nomor invoice
                $invoice = 'INV-' . date('YmdHis') . '-'
                    . strtoupper(Str::random(5));

                // Membuat transaksi
                $transaction = Transaction::create([
                    'customer_id' => $customer->id,
                    'province_name' => $validated['province_name'],
                    'city_name' => $validated['city_name'],
                    'district_name' => $validated['district_name'],
                    'subdistrict_name' => $validated['subdistrict_name'],
                    'zip_code' => (int) $validated['zip_code'],
                    'full_address' => $validated['full_address'],
                    'invoice' => $invoice,
                    'weight' => $weight,
                    'total' => $grandTotal,
                    'status' => 'pending',
                    'snap_token' => null,
                ]);

                // Menyiapkan detail produk untuk Midtrans
                $itemDetails = [];

                foreach ($carts as $cart) {
                    $product = $cart->product;

                    $price = (int) $product->price;
                    $qty = (int) $cart->qty;

                    // Menyimpan detail transaksi
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'qty' => $qty,
                        'price' => $price,
                    ]);

                    $itemDetails[] = [
                        'id' => (string) $product->id,
                        'price' => $price,
                        'quantity' => $qty,
                        'name' => mb_substr($product->title, 0, 50),
                    ];
                }

                // Menambahkan ongkos kirim
                if ($shippingCost > 0) {
                    $itemDetails[] = [
                        'id' => 'SHIPPING',
                        'price' => $shippingCost,
                        'quantity' => 1,
                        'name' => 'Ongkos Kirim',
                    ];
                }

                // Menyimpan data pengiriman
                Shipping::create([
                    'transaction_id' => $transaction->id,
                    'shipping_courier' => $validated['shipping_courier'],
                    'shipping_service' => $validated['shipping_service'],
                    'shipping_cost' => $shippingCost,
                ]);

                // Data customer untuk Midtrans
                $customerDetails = [
                    'first_name' => $customer->name,
                    'email' => $customer->email,
                ];

                // Parameter pembayaran Midtrans
                $params = [
                    'transaction_details' => [
                        'order_id' => $invoice,
                        'gross_amount' => $grandTotal,
                    ],
                    'customer_details' => $customerDetails,
                    'item_details' => $itemDetails,
                ];

                // Membuat Snap Token
                $snapToken = Snap::getSnapToken($params);

                // Menyimpan Snap Token
                $transaction->update([
                    'snap_token' => $snapToken,
                ]);

                // Menghapus cart setelah Snap Token berhasil dibuat
                Cart::where('customer_id', $customer->id)->delete();

                return [
                    'transaction' => $transaction,
                    'snap_token' => $snapToken,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Checkout berhasil',
                'data' => [
                    'invoice' => $result['transaction']->invoice,
                    'total' => $result['transaction']->total,
                    'snap_token' => $result['snap_token'],
                ],
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Checkout gagal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
