<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Project::with('users')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'project_detail' => 'nullable|string',
            'project_date' => 'nullable|date',
            'project_link' => 'nullable|string',
            'project_image' => 'nullable',
            'collaborators' => 'nullable|string',
            'liked' => 'nullable|boolean',
            'popularity' => 'nullable|integer'
        ]);

        $data = $request->all();

        if ($request->hasFile('project_image')) {
            $data['project_image'] = $request->file('project_image')->store('project_images', 'public');
        }

        $project = Project::create($data);

        return response()->json($project, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Project::with('users')->findOrFail($id);
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
        $project = Project::findOrFail($id);

        $data = $request->all();

        if ($request->hasFile('project_image')) {
            $data['project_image'] = $request->file('project_image')->store('project_images', 'public');
        }

        $project->update($data);

        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
