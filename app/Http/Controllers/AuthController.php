<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|max:50",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8|max:20|confirmed"
        ]);
        User::create([
            'name' => $request->name,
            'username' => $request->name . rand(1,1000000),
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
        ]);
    }

    public function login (Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        $attemptUser = Auth::attempt($credentials);
        if (!$attemptUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                "type" => "Bearer",
                'token' => $user->createToken('auth_token')->plainTextToken,
            ]
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            Auth::user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }
        return response()->json(['message' => 'User not found'], 404);
    }
    public function profile()
    {
        $user = User::find(Auth::id());
        return response()->json([
            'status' => 'success',
            'message' => 'User detail!',
            'user' => new UserResource($user)
        ]);
    }
}
