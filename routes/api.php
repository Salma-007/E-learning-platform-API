<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']); 
    Route::get('/{id}', [CategoryController::class, 'show']); 
    Route::post('/', [CategoryController::class, 'store']); 
    Route::put('/{id}', [CategoryController::class, 'update']); 
    Route::delete('/{id}', [CategoryController::class, 'destroy']); 
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/test', function () {
    return response()->json(['message' => 'Hello, world!']);
});