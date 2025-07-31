<?php
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Api route for user
Route::apiResource('users', UserController::class);

//Api route for project
Route::apiResource('projects', ProjectController::class);

//Api route for registrations
Route::apiResource('registrations', RegistrationController::class);


