<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'student_id' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
        ]);
        $user = User::create([
            'student_id' => $request->student_id,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
        // return response()->json(['message' => 'User registered']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('email',$request->email)->first();
        if(! $user || !Hash::check($request->password,$user->password_hash)){
            return response()->json(['message' => 'Invalid crendentials'], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logged out']);
    }
}
