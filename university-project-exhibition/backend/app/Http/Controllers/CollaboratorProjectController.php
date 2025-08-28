<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CollaboratorProjectController extends Controller
{
    /**
     * Store a new collaborator-project relationship.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'project_id' => 'required|exists:projects,project_id',
            'user_id' => 'required|exists:users,user_id',
            'role' => 'string|nullable',
        ]);

        $project = Project::findOrFail($request->project_id);
        $user = User::findOrFail($request->user_id);

        // Check if relationship already exists
        if ($project->collaborators()->where('user_id', $user->user_id)->exists()) {
            return response()->json([
                'message' => 'User is already a collaborator on this project'
            ], 422);
        }

        // Attach user as collaborator to project
        $project->collaborators()->attach($user->user_id, ['role' => $request->role]);

        return response()->json([
            'message' => 'Collaborator successfully added to project',
            'project' => $project->load('collaborators'),
        ], 201);
    }

    /**
     * Remove a collaborator-project relationship.
     */
    public function destroy(Project $project, Collaborator $collaborator): JsonResponse
    {
        // Check if relationship exists
        if (!$project->collaborators()->where('user_id', $collaborator->user_id)->exists()) {
            return response()->json([
                'message' => 'User is not a collaborator on this project'
            ], 404);
        }

        // Detach user from project
        $project->collaborators()->detach($collaborator->user_id);

        return response()->json([
            'message' => 'Collaborator successfully removed from project'
        ], 200);
    }

    /**
     * Get all collaborators for a specific project.
     */
    public function collaboratorsByProject(Project $project): JsonResponse
    {
        $collaborators = $project->collaborators()->get();

        return response()->json([
            'project' => $project,
            'collaborators' => $collaborators
        ]);
    }

    /**
     * Get all projects for a specific collaborator.
     */
    public function projectsByCollaborator(Collaborator $collaborator): JsonResponse
    {
        $user = User::findOrFail($collaborator->user_id);
        $projects = $user->projects()->get();

        return response()->json([
            'collaborator' => $collaborator,
            'projects' => $projects
        ]);
    }
}
