<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
            'review' => 'required',
            'product_id' => 'required',
            'transaction_detail_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah pernah memberikan rating untuk produk ini',
                'data' => $validator->errors(),
            ], 422);
        }

        $rating = Rating::create([
            'rating' => $request->rating,
            'review' => $request->review,
            'product_id' => $request->product_id,
            'transaction_detail_id' => $request->transaction_detail_id,
            'customer_id' => auth()->guard('api')->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rating Berhasil Ditambahkan',
            'data' => $rating,
        ], 201);
    }
}