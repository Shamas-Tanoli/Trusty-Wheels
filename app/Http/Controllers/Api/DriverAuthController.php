<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverAuthController extends Controller
{
//     public function login(Request $request)
// {
//     $request->validate([
//         'email'     => 'required|email',
//         'password'  => 'required',
//         'fcm_token' => 'nullable|string'
//     ]);

    
//     $user = User::where('email', $request->email)
//                 ->where('role', 'driver')
//                 ->first();

//     if (!$user || !Hash::check($request->password, $user->password)) {
//         return response()->json([
//             'message' => 'Invalid credentials',
//         ], 401);
//     }

    
//     $driver = Driver::where('verification_status', 'active')
//                     ->where('user_id', $user->id)
//                     ->first();

//     if (!$driver) {
//         return response()->json([
//             'message' => 'Driver not active or not found',
//         ], 403);
//     }

  
//     if ($request->has('fcm_token')) {
//         $user->fcm_token = $request->fcm_token;
//         $user->save();
//     }

    
//     $tokenName = $user->role . '_token';
//     $token = $user->createToken($tokenName)->plainTextToken;

//     return response()->json([
//         'message'     => 'Driver login successful',
//         'driver'      => $user,
//         'driver_info' => $driver,
//         'token'       => $token,
//     ]);
// }


public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)
                ->where('role', 'driver')
                ->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    $driver = Driver::where('verification_status', 'active')
                    ->where('user_id', $user->id)
                    ->first();

    if (!$driver) {
        return response()->json([
            'message' => 'Driver not active'
        ], 403);
    }

    // ✅ SECURE 6-digit OTP
    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // $user->otp = $otp;
    $user->otp = "000000";
    $user->otp_expires_at = Carbon::now()->addMinutes(5);
    $user->save();

    return response()->json([
        'message' => 'OTP sent successfully'
    ]);
}


    public function logout(Request $request)
    {
      
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'driver Logout successfully'
        ], 200);
    }



    public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp'   => 'required'
    ]);

    $user = User::where('email', $request->email)
                ->where('role', 'driver')
                ->first();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    if ( (int)$user->otp !==  (int)$request->otp) {
        return response()->json(['message' => 'Invalid OTP'], 401);
    }

    if (now()->gt($user->otp_expires_at)) {
        return response()->json(['message' => 'OTP expired'], 401);
    }

    
    // $user->otp = null;
    // $user->otp_expires_at = null;
    $user->save();

    
    $tokenName = $user->role . '_token';
    $token = $user->createToken($tokenName)->plainTextToken;

    $driver = Driver::where('user_id', $user->id)->first();

    return response()->json([
        'message'     => 'Login successful',
        'driver'      => $user,
        'driver_info' => $driver,
        'token'       => $token,
    ]);
}
}
