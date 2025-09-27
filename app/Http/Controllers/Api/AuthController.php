<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function Register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();
            if ($validated['password']) Hash::make($validated['password']);
            $user = User::create($validated);
            return response()->json(['message' => 'User registered successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred during registration.'], 500);
        }
    }
    public function Login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();
            if (!Auth::attempt($validated->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid login details'], 401);
            }
            $user = User::where('email', $validated['email'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['access_token' => $token, 'token_type' => 'Bearer'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred during login.'], 500);
        }
    }
    public function Logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred during logout.'], 500);
        }
    }
}
