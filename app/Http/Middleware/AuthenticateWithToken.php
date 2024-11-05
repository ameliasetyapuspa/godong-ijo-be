<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticateWithToken
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Unauthorized or token expired'], 401);
        }

        $token = substr($authHeader, 7);

        // Find the user by token
        $user = User::where('token', $token)->first();

        if (!$user || !$user->isTokenValid()) {
            if ($user) {
                $user->clearToken(); // Clear the expired token
            }
            return response()->json(['error' => 'Unauthorized or token expired'], 401);
        }

        // Authenticate the user for this request
        Auth::login($user); // Use Auth facade to authenticate user

        return $next($request);
    }
}
