<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::with('students')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,student_id|unique:users,student_id',
            'email' => 'required|exists:students,email',
            'password' => 'required|min:6',
        ]);
        $user = User::create([
            'student_id' => $validated['student_id'],
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
        ]);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::with('students')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'password' => 'nullable|min:6',
            'reset_token' => 'nullable|string',
            'reset_token_expiry' => 'nullable|date',
            'reset_token_used' => 'nullable|boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password_hash'] = Hash::make($validated['password']);
            unset($validated['password']);
        }

        $user->update($validated);
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
