<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'ratings'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List Products',
            'data' => $products
        ], 200);
    }

    public function productPopular()
    {
        $products = Product::with(['category', 'ratings'])
            ->withCount('ratings')
            ->orderBy('ratings_count', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List Product Popular',
            'data' => $products
        ], 200);
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'ratings'])
            ->where('slug', $slug)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Product',
            'data' => $product
        ], 200);
    }
}