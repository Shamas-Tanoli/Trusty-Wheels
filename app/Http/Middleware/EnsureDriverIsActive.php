<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDriverIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->role === 'driver') {
            $driverDetail = DB::table('driver_detail')->where('user_id', $user->id)->first();

            if (!$driverDetail) {
                return response()->json(['success' => true, 'message' => 'Please upload your complete details first. Once your details are complete, your request will be sent for the verification process, making you eligible for booking.']);
            }
            

            if ($driverDetail->status === 'inactive') {
                return response()->json(['success' => true,'message' => 'Your account is under verification.']);
            }
        }

        return $next($request);
    }
}
