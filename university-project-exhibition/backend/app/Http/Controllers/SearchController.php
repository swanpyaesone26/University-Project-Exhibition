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

        if (!$query) {
            return response()->json(['message' => 'No search query provided'], 400);
        }
        
        $users = Student::search($query)->get();
        
        if ($results->isEmpty()){
            return response()->json('No search found');
        }

        return response()->json([
            'students' => Student::search($query)->get(),
            
        ]);

    }

    public function searchUsers(Request $request)
    {
        $query = trim($request->input('q'));

        if (!$query) {
            return response()->json(['message' => 'No search query provided'], 400);
        }
        
        $users = User::search($query)->get();
        $users->load(['students','projects']);
        
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
        
        if ($results->isEmpty()){
            return response()->json('No search found');
        }

        return response()->json([
            'projects' => Project::search($query)->get(),
            
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
            'students' => Registration::search($query)->get(),
            
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
            'students' => Collaborator::search($query)->get(),
            
        ]);

    }


}
