<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerInquiry;

class CustomerInquiryController extends Controller
{
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:50',
            'pickup_address' => 'required|string',
            'dropoff_address' => 'required|string',
            'num_of_passenger' => 'required|integer|min:1',
            'message' => 'nullable|string',
        ]);

        // Save data
        $inquiry = CustomerInquiry::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'pickup_address' => $request->pickup_address,
            'dropoff_address' => $request->dropoff_address,
            'num_of_passenger' => $request->num_of_passenger,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Inquiry added successfully',
            'data' => $inquiry
        ], 201);
    }
}