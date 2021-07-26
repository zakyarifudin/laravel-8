<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'getAll']);
    Route::get('/{id}', [UserController::class, 'getOne']);
    Route::post('/', [UserController::class, 'add']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'delete']);
});

Route::get('user-auth', [AuthController::class, 'userAuth'])->middleware('auth:api');

Route::prefix('posts')->middleware('auth:api')->group(function () {
    Route::get('/', [PostController::class, 'getAll']);
    Route::get('/{id}', [PostController::class, 'getOne']);
    Route::post('/', [PostController::class, 'add']);
    Route::put('/{id}', [PostController::class, 'update']);
    Route::delete('/{id}', [PostController::class, 'delete']);
});
