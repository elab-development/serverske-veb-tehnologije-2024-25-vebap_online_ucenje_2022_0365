<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ExternalEduController;
use App\Http\Controllers\LessonController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/search', [CourseController::class, 'searchCourses']);
Route::get('/courses/{id}', [CourseController::class, 'show']);

Route::get('/lessons', [LessonController::class, 'index']);
Route::get('/lessons/{id}', [LessonController::class, 'show']);

Route::get('/enrollments', [EnrollmentController::class, 'index']);
Route::get('/enrollments/{id}', [EnrollmentController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/ext/openlibrary/search', [ExternalEduController::class, 'openLibrarySearch']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('courses', CourseController::class)
        ->only(['store', 'update', 'destroy']);
    Route::resource('lessons', LessonController::class)
        ->only(['store', 'update', 'destroy']);
    Route::resource('enrollments', EnrollmentController::class)
        ->only(['store', 'destroy']);

    Route::put('/users/update-role', [AuthController::class, 'updateRole']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
