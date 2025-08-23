<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'uni_id' => 'required|unique:users,uni_id',
            // 'student_id' => 'required|unique:users',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
        ]);
            // Find the student by uni_id
        $student = Student::where('uni_id', $request->uni_id)->firstOrFail();

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $user = User::create([
            'student_id' => $student->student_id,   // link to student
            'uni_id' => $student->uni_id,   // store uni_id also
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
        ]);
        
        // $user = User::create([
        //     'uni_id' => $request->uni_id,
        //     'email' => $request->email,
        //     'password_hash' => Hash::make($request->password),
        // ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
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
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logged out']);
    }
}
