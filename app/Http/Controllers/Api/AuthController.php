<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

   public function register(Request $request)
{ 

    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'contact'  => 'required|string',
        'password' => 'required|min:6'
    ]);

    DB::beginTransaction();

    try {
       
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'customer'
           
        ]);

        
        $customer = Customer::create([
            'user_id' => $user->id,
            'name'    => $request->name,
            'contact' => $request->contact,
            'address' => 'ummy', 
            
        ]);

        $token = $user->createToken('customer_token')->plainTextToken;

        DB::commit();

        return response()->json([
            'status'  => true,
            'message' => 'Customer registered successfully',
            'data' => [
                'user'     => $user,
                'customer' => $customer,
                'token'    => $token
            ]
        ], 
        201);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'Customer Registration failed',
            'error' => $e->getMessage()
        ], 500);
    }
}



   public function login(Request $request)
{
    $request->validate([
        'email'     => 'required|email',
        'password'  => 'required',
        'fcm_token' => 'nullable|string',
    ]);

    $user = User::where('email', $request->email) ->where('role', 'customer')->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    
    if ($request->has('fcm_token')) {
        $user->fcm_token = $request->fcm_token;
        $user->save();
    }

    
    $tokenName = $user->role . '_token';

    $token = $user->createToken($tokenName)->plainTextToken;

    return response()->json([
        'message' => 'Customer Login successful',
        'user'    => $user,
        'token'   => $token,
    ]);
}


    public function logout(Request $request)
    {
        
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Customer Logout successfully'
        ], 200);
    }
}
