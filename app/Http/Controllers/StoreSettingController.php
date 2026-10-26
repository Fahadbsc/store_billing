<?php

namespace App\Http\Controllers;

use App\Models\StoreSetting;
use Illuminate\Http\Request;

class StoreSettingController extends Controller
{
    public function show()
    {
        return response()->json(['store' => StoreSetting::first()]);
    }

    public function store(Request $request)
    {
        if (StoreSetting::exists()) {
            return response()->json(['message' => 'Store details have already been set up.'], 409);
        }

        $data = $request->validate([
            'store_name' => 'required|string|max:150',
            'address' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'gst_number' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
        ]);

        $store = StoreSetting::create($data);

        return response()->json([
            'message' => 'Store details saved successfully.',
            'store' => $store,
        ], 201);
    }
}