<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next, ...$roles)
    {
         $user = $request->user('sanctum');


        
        if (! $user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // ✅ Multiple roles check (customer, driver, etc)
        if (! in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Unauthorized role'
            ], 403);
        }

        return $next($request);
    }
    


 
}
