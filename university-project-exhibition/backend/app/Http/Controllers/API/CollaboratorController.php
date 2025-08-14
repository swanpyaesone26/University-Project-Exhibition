<?php

namespace App\Http\Controllers\API;

use App\Models\Collaborator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollaboratorController extends Controller
{
    public function index()
    {
        return Collaborator::all();
        // return Collaborator::with('projects')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:collaborators',
            'major' => 'required',
            'batch' => 'required',
            'image' => 'nullable|string'
        ]);

        return Collaborator::create($validated);
    }

    public function show($id)
    {
        return Collaborator::findOrFail($id);
        // return Collaborator::with('projects')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $collaborator = Collaborator::findOrFail($id);
        $collaborator->update($request->all());
        return $collaborator;
    }

    public function destroy($id)
    {
        Collaborator::findOrFail($id)->delete();
        return response()->json(['message' => 'Collaborator Deleted successfully']);
    }
}