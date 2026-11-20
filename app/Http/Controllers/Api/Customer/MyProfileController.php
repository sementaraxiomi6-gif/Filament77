<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class MyProfileController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'auth:api',
        ];
    }


    /**
     * Display customer profile
     */
    public function index()
    {
        // get profile customer login
        $profile = Customer::query()
            ->where('id', Auth::guard('api')->user()->id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Data Profil',
            'data' => $profile,
        ], 200);
    }


    /**
     * Update customer profile
     */
    public function update(Request $request)
    {
        // validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:customers,email,' . Auth::guard('api')->user()->id,
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        // validation error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ], 422);
        }


        // get customer profile
        $profile = Customer::query()
            ->where('id', Auth::guard('api')->user()->id)
            ->first();


        // update image
        if ($request->file('image')) {

            // delete old image
            if ($profile->image) {
                Storage::disk('public')->delete('customers/' . $profile->image);
            }


            // upload new image
            $image = $request->file('image');

            $image->storeAs(
                'customers',
                $image->hashName(),
                'public'
            );

            $profile->image = $image->hashName();
        }


        // update name and email
        $profile->name = $request->name;
        $profile->email = $request->email;


        // update password
        if ($request->password) {
            $profile->password = Hash::make($request->password);
        }


        // save changes
        $profile->save();


        return response()->json([
            'success' => true,
            'message' => 'Update Profil Berhasil',
            'data' => $profile,
        ], 200);
    }

}