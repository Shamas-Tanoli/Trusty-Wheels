<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function getUserNotifications(Request $request)
    {
      
        $request->validate([
            'user_id'   => 'required|integer',
            'user_type' => 'required|string',
        ]);

        $userId   = $request->input('user_id');
        $userType = $request->input('user_type');

        
        $notifications = DB::table('notifications')
            ->where('user_id', $userId)
            ->where('user_type', $userType)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status'        => 'success',
            'notifications' => $notifications
        ]);
    }
}
