<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectCreateController extends Controller
{

    public function index()
    {
        return ProjectCreateController::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'project_detail' => 'nullable|string',
            'project_link' => 'nullable|string',
            'project_images' => 'nullable',
            'liked' => 'nullable',
            'popularity' => 'nullable',
            'user_id' => 'required|exists:users,user_id',   // project owner
            'collaborators' => 'array',
            'collaborators.*.user_id' => 'required|exists:users,user_id',
        ]);

        $data = $request->only(['project_name', 'project_detail', 'project_link', 'project_images','liked','popularity']);

        $data['user_id'] = $validated['user_id'];  // add this line


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

        // 1. Create project
        $project = Project::create($data);

        // 2. Attach owner as collaborator with role "Owner"
        $project->users()->attach($validated['user_id'], ['role' => 'Owner']);

        // 3. Attach other collaborators
        if (!empty($validated['collaborators'])) {
            foreach ($validated['collaborators'] as $collab) {
                $project->users()->attach($collab['user_id'], ['role' => 'Collaborator']);
            }
        }

        // 4. Return response with collaborators included
        return response()->json(
            $project->load('users'),
            201
        );
    }
}