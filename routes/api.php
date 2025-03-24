<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\StatesController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\Api\V2\RoleController;
use App\Http\Controllers\Api\V2\BadgeController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V2\Auth\AuthController;
use App\Http\Controllers\Api\V2\Auth\UserController;
use App\Http\Controllers\Api\V2\PermissionController;


Route::prefix('v2')->group(function () {

    Route::post('/badges', [BadgeController::class, 'createBadge']);
    Route::put('/badges/{id}', [BadgeController::class, 'updateBadge']);
    Route::delete('/badges/{id}', [BadgeController::class, 'deleteBadge']);

    Route::get('/students/{id}/badges', [BadgeController::class, 'getaUserBadges']);

    Route::post('/courses/{id}/videos', [CourseController::class, 'addVideoToCourse']);
    Route::get('/courses/{id}/videos', [CourseController::class, 'listVideosOfCourse']);
    Route::get('/videos/{id}', [CourseController::class, 'getVideo']);
    Route::put('/videos/{id}', [CourseController::class, 'updateVideo']);
    Route::delete('/videos/{id}', [CourseController::class, 'deleteVideo']);

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name("logout");

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    }); 

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('/', [PermissionController::class, 'store']); 
        Route::put('/{id}', [PermissionController::class, 'update']); 
        Route::get('/{id}', [PermissionController::class, 'show']); 
        Route::delete('/{id}', [PermissionController::class, 'destroy']);
    }); 

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/edit', [UserController::class, 'update']);
        Route::post('/users/{user}', [UserController::class, 'updateUser'])->middleware('role:admin');
        Route::post('/logout', [AuthController::class, 'logout'])->name("logout");
    });
    
    Route::get('/students/{id}/courses', [StudentController::class, 'enrolledCourses']);
    
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/mentors/{id}/courses', [MentorController::class, 'mentorCourses']);
        Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->middleware('role:student');
        Route::get('/courses/{course}/enrollments', [EnrollmentController::class, 'index']);
        Route::get('/stats/courses', [StatesController::class, 'courseStatistics'])->middleware('role:admin');
        Route::get('/stats/categories', [StatesController::class, 'categorieStatistics'])->middleware('role:admin');
        Route::get('/stats/tags', [StatesController::class, 'tagStatistics'])->middleware('role:admin');
    
    });

    Route::delete('/videos/{id}', [VideoController::class, 'deleteVideo']);

    Route::prefix('courses')->group(function () {
        Route::post('/{id}/videos', [VideoController::class, 'addVideoToCourse']);
        Route::get('/{id}/videos', [VideoController::class, 'listVideosOfCourse']);
    });
});

Route::prefix('v1')->group(function () {

    Route::prefix('tags')->group(function () {
        Route::get('/', [TagController::class, 'index']); 
        Route::get('/{id}', [TagController::class, 'show']); 
        Route::post('/', [TagController::class, 'store']); 
        Route::put('/{id}', [TagController::class, 'update']); 
        Route::delete('/{id}', [TagController::class, 'destroy']); 
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']); 
        Route::get('/{id}', [CategoryController::class, 'show']); 
        Route::post('/', [CategoryController::class, 'store']); 
        Route::put('/{id}', [CategoryController::class, 'update']); 
        Route::delete('/{id}', [CategoryController::class, 'destroy']); 
    });

    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index']); 
        Route::get('/{id}', [CourseController::class, 'show']); 

        Route::middleware('auth:sanctum')->group(function () 
        {
            Route::post('/', [CourseController::class, 'store'])->middleware('role:mentor'); 
        });

        Route::put('/{id}', [CourseController::class, 'update']); 
        Route::delete('/{id}', [CourseController::class, 'destroy']); 
    });


});




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/test', function () {
    return response()->json(['message' => 'Hello, world!']);
});