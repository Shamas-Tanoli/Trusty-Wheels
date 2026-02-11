<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    // ✅ Driver Profile API
    public function driverProfile(Request $request)
    {
        $user = $request->user();

        // Check if user is a driver
        if ($user->role !== 'driver') {
            return response()->json([
                'success' => false,
                'message' => 'User is not a driver'
            ], 403);
        }

        // Fetch driver details
        $driver = $user->driverDetail()->first();
       
        

        return response()->json([
            'success' => true,
            'driver' => $user,
            'driver_detail' => $driver
        ]);
    }

    
    public function customerProfile(Request $request)
    {
        $user = $request->user();

        
        if ($user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'User is not a customer'
            ], 403);
        }

        $customer = $user->customer()->first();


        return response()->json([
            'success' => true,
            'user' => $user,
            'customer' => $customer
        ]);
    }
}
