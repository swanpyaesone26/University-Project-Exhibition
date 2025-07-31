<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Registration::all();
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
            'student_id' => 'required|exists:students,student_id',
            'email' => 'required|email',
            'purpose' => 'required|string',
            'otp_code' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'attempts' => 'nullable|integer',
            'is_used' => 'nullable|boolean',
        ]);
        $registration = Registration::create($validated);
        return response()->json($registration, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Registration::findOrFail($id);
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
        $registration = Registration::findOrFail($id);

        $validated = $request->validate([
            'purpose' => 'sometimes|string',
            'otp_code' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'attempts' => 'nullable|integer',
            'is_used' => 'nullable|boolean',
        ]);

        $registration->update($validated);
        return response()->json($registration);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Registration::destroy($id);
        return response()->json(['message' => 'Registration deleted']);
    }
}
