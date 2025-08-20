<?php

namespace App\Http\Controllers\API;
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
        // return User::all();
        return $users = User::with('students')->get();

        // $users = User::with([
        //     'students' => function($query) {
        //         $query->select('student_id', 'name', 'image', 'major', 'batch');
        //     }
        // ])->get();

        // return $users;

        // $flatusers = $users->map(function ($user){
        //     return [
        //         'user_id' => $user->user_id,
        //         'student_id' => $user->student_id,
        //         'uni_id' => $user->uni_id,
        //         'name' => $user->students->name,
        //         'email' => $user->email,
        //         'image' => $user->students->image,
        //         'major' => $user->students->major,
        //         'batch' => $user->students->batch,
        //         'password_hash' => $user->password_hash,
        //         'reset_token' => $user->reset_token,
        //         'reset_token_expiry' => $user->reset_token_expiry,
        //         'reset_token_used' => $user->reset_token_used,
        //         'created_at' => $user->created_at


        //     ];
        // });

        // return response()->json($flatusers);
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
            'uni_id' => 'required|exists:students,uni_id|unique:users,uni_id',
            'email' => 'required|exists:students,email',
            'password' => 'required|min:6',
        ]);
        $user = User::create([
            'student_id' => $validated['student_id'],
            'uni_id' => $validated['uni_id'],
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
        // return User::findOrFail($id);
        return User::with('students')->findOrFail($id);
        // $users = User::with([
        //     'students' => function($query) {
        //         $query->select('student_id', 'name', 'image', 'major', 'batch');
        //     }
        // ])->findOrFail($id);

        // return $users;

        // $flatusers =  [
        //         'user_id' => $users->user_id,
        //         'student_id' => $users->student_id,
        //         'uni_id' => $users->uni_id,
        //         'name' => $users->students->name,
        //         'email' => $users->email,
        //         'image' => $users->students->image,
        //         'major' => $users->students->major,
        //         'batch' => $users->students->batch,
        //         'password_hash' => $users->password_hash,
        //         'reset_token' => $users->reset_token,
        //         'reset_token_expiry' => $users->reset_token_expiry,
        //         'reset_token_used' => $users->reset_token_used,
        //         'created_at' => $users->created_at


        //     ];

        // return response()->json($flatusers);
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
