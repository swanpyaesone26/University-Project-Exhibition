<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return Student::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'uni_id' => 'required|integer|unique:students,uni_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'major' => 'required|string|max:255',
            'batch' => 'required|string|max:255',
            'image' => 'nullable|string'
        ]);

        // Handle name_no_space field
        $validated['name_no_space'] = strtolower(str_replace(' ', '', $validated['name']));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = $path;
        }

        $student = Student::create($validated);
        
        return response()->json([
            'message' => 'Student created successfully',
            'student' => $student
        ], 201);
    }

    public function show($id)
    {
        return Student::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update($request->all());
        return $student;
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return response()->json(['message' => 'Student deleted successfully']);
    }
}
