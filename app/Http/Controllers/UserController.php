<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized. Please log in.'], 401);
    }

    $user = Auth::user();

    if ($user->role->name !== 'administrator') {
        return response()->json(['error' => 'Unauthorized. Only administrators can register new users.'], 403);
    }

    // Wrap validation in a try-catch block
    try {
        $validatedData = $request->validate([
            'username' => 'required|string|unique:users,username|max:50',
            'password' => 'required|string|min:6',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Return JSON response with error details
        return response()->json(['errors' => $e->errors()], 422);
    }

    // Create a new user
    $newUser = User::create([
        'role_id' => $user->role_id,
        'username' => $validatedData['username'],
        'password' => bcrypt($validatedData['password']),
        'isActive' => true,
    ]);

    return response()->json(['message' => 'User registered successfully.', 'user' => $newUser], 201);
}


    public function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user->generateToken();
        return response()->json(['token' => $user->token]);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $user->clearToken();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
{
    $user = auth()->user();

    return response()->json([
        'user' => $user,
    ], 200);
}

}
