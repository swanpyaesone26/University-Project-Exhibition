<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Collaborator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollaboratorProjectController extends Controller
{
    // Attach collaborator to project
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,project_id',
            'collaborator_id' => 'required|exists:collaborators,collaborator_id',
        ]);

        $project = Project::findOrFail($request->project_id);
        // $project->collaborators()->attach($request->collaborator_id);
        // return response()->json(['message' => 'Collaborator added to project successfully']);

        $collaboratorId = $request->collaborator_id;

        // This will attach if not already attached, and ignore if it exists
        $project->collaborators()->syncWithoutDetaching([$collaboratorId]);
        
        return response()->json([
            'message' => 'Collaborator added to project successfully',
            'project' => $project->load('collaborators')
        ]);
    }

    // Detach collaborator from project
    public function destroy($projectId, $collaboratorId)
    {
        $project = Project::findOrFail($projectId);
        $project->collaborators()->detach($collaboratorId);

        return response()->json(['message' => 'Collaborator removed from project successfully']);
    }

    // Get all collaborators of a project
    public function collaboratorsByProject($projectId)
    {
        $project = Project::findOrFail($projectId);
        // $project = Project::with('collaborators')->findOrFail($projectId);
        return response()->json($project->collaborators);
    }

    // Get all projects of a collaborator
    public function projectsByCollaborator($collaboratorId)
    {
        $collaborator = Collaborator::findOrFail($collaboratorId);
        // $collaborator = Collaborator::with('projects')->findOrFail($collaboratorId);
        return response()->json($collaborator->projects);
    }
}
