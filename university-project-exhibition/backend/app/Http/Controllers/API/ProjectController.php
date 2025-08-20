<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Project::with(['users', 'users.students'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'project_name' => 'required|string',
            'project_detail' => 'nullable|string',
            'project_date' => 'nullable|date',
            'project_link' => 'nullable|string',
            'project_images' => 'nullable',
            'liked' => 'nullable|boolean',
            'popularity' => 'nullable|integer',
        ]);

        $data = $request->all();

        // Handle images
        if ($request->hasFile('project_images')) {
            // Case 1: user uploads actual files
            $images = [];
            foreach ($request->file('project_images') as $file) {
                $images[] = $file->store('project_images', 'public');
            }
            $data['project_images'] = $images;

        } elseif (is_array($request->project_images)) {
            // Case 2: user sends JSON array of strings
            $data['project_images'] = $request->project_images;
        } else {
            $data['project_images'] = [];
        }

        $project = Project::create($data);

        return response()->json($project, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Project::with(['users', 'users.students'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $data = $request->all();

        // Handle images
        if ($request->hasFile('project_images')) {
            // Case 1: user uploads actual files
            $images = [];
            foreach ($request->file('project_images') as $file) {
                $images[] = $file->store('project_images', 'public');
            }
            $data['project_images'] = $images;
            
        } elseif (is_array($request->project_images)) {
            // Case 2: user sends JSON array of strings
            $data['project_images'] = $request->project_images;
        } else {
            $data['project_images'] = [];
        }


        $project->update($data);

        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
