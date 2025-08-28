<?php

namespace App\Http\Controllers\API;

use App\Models\Collaborator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollaboratorController extends Controller
{
    public function index()
    {
        return Collaborator::with(['projects', 'users'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|string'
        ]);

        $collaborator = Collaborator::create($validated);

        return response()->json($collaborator, 201);
    }

    public function show($id)
    {
        $collaborator = Collaborator::with(['projects', 'users'])->find($id);

        if (!$collaborator) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($collaborator);
    }

    public function update(Request $request, $id)
    {
        $collaborator = Collaborator::find($id);

        if (!$collaborator) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'role' => 'nullable|string'
        ]);

        $collaborator->update($validated);

        return response()->json($collaborator);
    }

    public function destroy($id)
    {
        $collaborator = Collaborator::find($id);

        if (!$collaborator) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $collaborator->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}