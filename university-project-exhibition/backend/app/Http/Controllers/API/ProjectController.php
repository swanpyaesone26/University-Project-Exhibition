<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects with collaborators
     */
    public function index()
    {
        return Project::with('users')->get();
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'project_detail' => 'nullable|string',
            'project_link' => 'nullable|string',
            'project_images' => 'nullable',
            'liked' => 'nullable|boolean',
            'popularity' => 'nullable|integer',
            'user_id' => 'required|exists:users,user_id',   // project owner
            'collaborators' => 'array',
            'collaborators.*.user_id' => 'required|exists:users,user_id',
        ]);

        $data = $request->only(['project_name', 'project_detail', 'project_link', 'project_images','liked','popularity']);
        $data['user_id'] = $validated['user_id'];

        // Handle images
        if ($request->hasFile('project_images')) {
            $images = [];
            foreach ($request->file('project_images') as $file) {
                $images[] = $file->store('project_images', 'public');
            }
            $data['project_images'] = $images;
        } elseif (is_array($request->project_images)) {
            $data['project_images'] = $request->project_images;
        } else {
            $data['project_images'] = [];
        }

        // Create project
        $project = Project::create($data);

        // Attach owner as collaborator with role "Owner"
        $project->users()->attach($validated['user_id'], ['role' => 'Owner']);

        // Attach collaborators
        if (!empty($validated['collaborators'])) {
            foreach ($validated['collaborators'] as $collab) {
                $project->users()->attach($collab['user_id'], ['role' => 'Collaborator']);
            }
        }

        return response()->json($project->load('users'), 201);
    }

    /**
     * Display a single project by ID
     */
    public function show($id)
    {
        $project = Project::with('users')->findOrFail($id);
        return response()->json($project);
    }

    /**
     * Update a project
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'project_name' => 'sometimes|string|max:255',
            'project_detail' => 'nullable|string',
            'project_link' => 'nullable|string',
            'project_images' => 'nullable',
            'liked' => 'nullable|boolean',
            'popularity' => 'nullable|integer',
            'collaborators' => 'array',
            'collaborators.*.user_id' => 'required|exists:users,user_id',
        ]);

        $data = $request->only(['project_name', 'project_detail', 'project_link', 'project_images','liked','popularity']);

        // Handle images
        if ($request->hasFile('project_images')) {
            $images = [];
            foreach ($request->file('project_images') as $file) {
                $images[] = $file->store('project_images', 'public');
            }
            $data['project_images'] = $images;
        } elseif (is_array($request->project_images)) {
            $data['project_images'] = $request->project_images;
        }

        // Update project
        $project->update($data);

        // Sync collaborators (reset & add again)
        if (!empty($validated['collaborators'])) {
            $syncData = [];
            foreach ($validated['collaborators'] as $collab) {
                $syncData[$collab['user_id']] = ['role' => 'Collaborator'];
            }
            // Keep the Owner
            $syncData[$project->user_id] = ['role' => 'Owner'];
            $project->users()->sync($syncData);
        }

        return response()->json($project->load('users'));
    }

    /**
     * Remove a project
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        // Detach collaborators before delete
        $project->users()->detach();

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}