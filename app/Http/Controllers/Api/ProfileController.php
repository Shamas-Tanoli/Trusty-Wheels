<?php

namespace App\Http\Controllers;

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
        $driverInfo = $user->driverJobs()->with('booking')->get();
        $documents = $user->driverDetail->driver->documents ?? null;

        return response()->json([
            'success' => true,
            'user' => $user,
            'driver_detail' => $driver,
            'documents' => $documents,
        ]);
    }

    // ✅ Customer Profile API
    public function customerProfile(Request $request)
    {
        $user = $request->user();

        // Check if user is a customer
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
