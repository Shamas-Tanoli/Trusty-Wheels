<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Delete a user by ID
     */
   public function destroy(Request $request)
{
    // Get the currently authenticated user
    $user = $request->user(); // or auth()->user()

    
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated'
        ], 401);
    }

    // Optionally, prevent admin from deleting themselves
    if ($user->role === 'admin') {
        return response()->json([
            'success' => false,
            'message' => 'Admin user cannot delete their own account'
        ], 403);
    }

    $user->delete();

    return response()->json([
        'success' => true,
        'message' => 'Your account has been deleted successfully'
    ]);
}

}
