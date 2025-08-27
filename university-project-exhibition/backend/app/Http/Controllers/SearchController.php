<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Project;
use App\Models\Collaborator;
use App\Models\Registration;
class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->input('q'));

        if (!$query) {
            return response()->json(['message' => 'No search query provided'], 400);
        }

        return response()->json([
            'students' => Student::search($query)->get(),
            'users' => User::search($query)->get(),
            'registrations' => Registration::search($query)->get(),
            'projects' => Project::search($query)->get(),
            'collaborators' => Collaborator::search($query)->get(),
        ]);

    }

    public function searchStudents(Request $request)
    {
        $query = trim($request->input('q'));
        $batch = $request->input('batchq');

        if (!$query && !$batch) {
            return response()->json(['message' => 'No search query provided'], 400);
        }

        // Start search with query (if any)
        $builder = Student::search($query ?? '');

        // Apply batch filter if provided
        if ($batch) {
            $builder->where('batch', $batch);
        }

        $students = $builder->get();

        if ($students->isEmpty()) {
            return response()->json(['message' => 'No search found']);
        }

        return response()->json([
            'students' => $students,
        ]);
    }


    public function searchUsers(Request $request)
    {
        $query = trim($request->input('q'));

        if (!$query) {
            return response()->json(['message' => 'No search query provided'], 400);
        }
        
        $users = User::search($query)->get();
        $users->load('students');
        
        if ($users->isEmpty()){
            return response()->json('No search found');
        }

        return response()->json([
            // 'users' => User::search($query)->get(),
            'users' => $users,
    
            
        ]);

    }

    public function searchProjects(Request $request)
    {
        $query = trim($request->input('q'));

        if (!$query) {
            return response()->json(['message' => 'No search query provided'], 400);
        }
        
        $results = Project::search($query)->get();
        $results->load(['users','users.students']);
        
        if ($results->isEmpty()){
            return response()->json('No search found');
        }

        return response()->json([
            'projects' => $results
            
        ]);

    }

    public function searchRegistrations(Request $request)
    {
        $query = trim($request->input('q'));

        if (!$query) {
            return response()->json(['message' => 'No search query provided'], 400);
        }
        
        $results = Registration::search($query)->get();
        
        if ($results->isEmpty()){
            return response()->json('No search found');
        }

        return response()->json([
            'students' => $results,
        ]);

    }

    public function searchCollaborators(Request $request)
    {
        $query = trim($request->input('q'));

        if (!$query) {
            return response()->json(['message' => 'No search query provided'], 400);
        }
        
        $results = Collaborator::search($query)->get();
        
        if ($results->isEmpty()){
            return response()->json('No search found');
        }

        return response()->json([
            'students' => $results,
            
        ]);

    }


}
