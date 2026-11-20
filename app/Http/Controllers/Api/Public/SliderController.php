<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();

        return response()->json([
            'success' => true,
            'message' => 'List Sliders',
            'data' => $sliders
        ], 200);
    }
}
