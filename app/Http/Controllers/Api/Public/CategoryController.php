<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Categories',
            'data' => $categories
        ], 200);
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $category->load('products');

        return response()->json([
            'success' => true,
            'message' => 'Detail Category',
            'data' => $category
        ], 200);
    }
}