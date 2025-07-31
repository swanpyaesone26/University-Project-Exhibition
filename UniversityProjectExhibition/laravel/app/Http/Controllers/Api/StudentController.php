<?php

namespace App\Http\Controllers\Api;

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
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:students',
            'major' => 'sometimes|required',
            'batch' => 'sometimes|required',
            'image' => 'nullable'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validated['image']= $path;
        }

        return Student::create($validated);
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
        return Student::destroy($id);
        return response()->json(['message' => 'Student deleted']);
    }
}
