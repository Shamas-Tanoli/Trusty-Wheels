<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthController extends Controller
{

public function googleLogin(Request $request)
{
    $request->validate([
        'id_token' => 'required|string',
         'fcm_token' => 'nullable|string',
    ]);

    try {

        // Google se user verify karo
        $response = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $request->id_token
        ]);

        if ($response->failed()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Google token'
            ], 401);
        }

        $googleUser = $response->json();

        $email = $googleUser['email'];
        $name  = $googleUser['name'] ?? 'Google User';

        // Check user exist
        $user = User::where('email', $email)->first();

        if (! $user) {

            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => bcrypt(Str::random(16)),
                'role'     => 'customer',
                'login_from'     => 'google',
                'fcm_token'     => $request->fcm_token,
            ]);

            Customer::create([
                'user_id' => $user->id,
                'name'    => $name,
                'contact' => '',
                'address' => ''
            ]);
        }

        // Token generate
        $token = $user->createToken('customer_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Google login successful',
            'user' => $user,
            'token' => $token
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => false,
            'message' => 'Google login failed',
            'error' => $e->getMessage()
        ], 500);
    }
}


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
        'login_from' => 'nullable|string',
    ]);

    $user = User::where('email', $request->email) ->where('role', 'customer')->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    
    if ($request->has('login_from')) {
        $user->login_from = $request->login_from;
        $user->save();
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
