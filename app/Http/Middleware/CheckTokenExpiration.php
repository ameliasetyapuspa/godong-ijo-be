<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTokenExpiration
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the token has expired
        if ($user->currentAccessToken() && $user->currentAccessToken()->expires_at < now()) {
            $user->currentAccessToken()->delete(); // Delete the expired token
            return response()->json(['error' => 'Token has expired. Please log in again.'], 401);
        }

        return $next($request);
    }
}
