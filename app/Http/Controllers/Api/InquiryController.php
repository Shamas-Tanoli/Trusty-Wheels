<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{
    
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email',
            'phone'      => 'required|string|max:20',
            'message'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ], 422);
        }

        $inquiry = Inquiry::create($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Inquiry submitted successfully',
            'data'    => $inquiry
        ], 201);
    }
}
