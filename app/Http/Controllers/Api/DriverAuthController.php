<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class DriverAuthController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'email'     => 'required|email',
        'password'  => 'required',
        'fcm_token' => 'nullable|string'
    ]);

    
    $user = User::where('email', $request->email)
                ->where('role', 'driver')
                ->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    
    $driver = Driver::where('verification_status', 'active')
                    ->where('user_id', $user->id)
                    ->first();

    if (!$driver) {
        return response()->json([
            'message' => 'Driver not active or not found',
        ], 403);
    }

  
    if ($request->has('fcm_token')) {
        $user->fcm_token = $request->fcm_token;
        $user->save();
    }

    
    $tokenName = $user->role . '_token';
    $token = $user->createToken($tokenName)->plainTextToken;

    return response()->json([
        'message'     => 'Driver login successful',
        'driver'      => $user,
        'driver_info' => $driver,
        'token'       => $token,
    ]);
}


    public function logout(Request $request)
    {
        // current access token delete
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'driver Logout successfully'
        ], 200);
    }
}
