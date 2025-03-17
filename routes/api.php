<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V2\RoleController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V2\Auth\AuthController;
use App\Http\Controllers\Api\V2\PermissionController;


Route::prefix('v2')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    
    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('/', [PermissionController::class, 'store']); 
        Route::put('/{id}', [PermissionController::class, 'update']); 
        Route::get('/{id}', [PermissionController::class, 'show']); 
        Route::delete('/{id}', [PermissionController::class, 'destroy']);
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
        Route::post('/', [CourseController::class, 'store']); 
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