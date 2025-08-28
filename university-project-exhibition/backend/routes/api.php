<?php
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\API\CollaboratorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollaboratorProjectController;
use App\Http\Controllers\StudentBulkController;
use App\Http\Controllers\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Meilisearch\Client;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Protected routes
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout',[AuthController::class,'logout']);

});

//Api route for student
Route::apiResource('students', StudentController::class);

// Test route for debugging
Route::post('/test', function(Request $request) {
    return response()->json([
        'message' => 'Test endpoint working',
        'data' => $request->all(),
        'headers' => $request->headers->all()
    ]);
});

//Api route for user
Route::apiResource('users', UserController::class);

//Api route for project
Route::apiResource('projects', ProjectController::class);

//Api route for registrations
Route::apiResource('registrations', RegistrationController::class);

//Api route for collaborators
Route::apiResource('collaborators', CollaboratorController::class);

////Api route for collaboratorsprojet
Route::post('/collaborator-project', [CollaboratorProjectController::class, 'store']);
Route::delete('/collaborator-project/{project}/{collaborator}', [CollaboratorProjectController::class, 'destroy']);
Route::get('/project/{project}/collaborators', [CollaboratorProjectController::class, 'collaboratorsByProject']);
Route::get('/collaborator/{collaborator}/projects', [CollaboratorProjectController::class, 'projectsByCollaborator']);
Route::post('/students/bulk', [StudentBulkController::class, 'storeMany']);
Route::get('/search', [SearchController::class, 'search']);
Route::get('/search/students', [SearchController::class, 'searchStudents']);
Route::get('/search/users', [SearchController::class, 'searchUsers']);
Route::get('/search/projects', [SearchController::class, 'searchProjects']);
Route::get('/search/registrations', [SearchController::class, 'searchRegistrations']);
Route::get('/search/collaborators', [SearchController::class, 'searchCollaborators']);


Route::get('/setup-student-search', function () {
    $client = new Client(env('MEILISEARCH_HOST'));
    $client->index('students')->updateSettings([
        'searchableAttributes' => ['name', 'name_no_space', 'email', 'major', 'batch'],
        'filterableAttributes' => ['batch'],
        'typoTolerance' => ['enabled' => true],
    ]);
    return 'Student search settings updated!';
});

Route::get('/setup-project-search', function () {
    $client = new Client(env('MEILISEARCH_HOST'));
    $client->index('projects')->updateSettings([
        'searchableAttributes' => ['project_name', 'project_name_no_space', 'project_detail'],
        'typoTolerance' => ['enabled' => true],
    ]);

    return response()->json(['message' => 'Project search settings updated!']);
});

