<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavorisController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ReponseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (no auth required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (auth required via Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Questions
    Route::get('/questions', [QuestionController::class, 'index']);
    Route::post('/questions', [QuestionController::class, 'store']);
    Route::get('/questions/{id}', [QuestionController::class, 'show']);
    Route::put('/questions/{id}', [QuestionController::class, 'update']);
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);

    // Reponses
    Route::post('/questions/{questionId}/reponses', [ReponseController::class, 'store']);

    // Favoris
    Route::get('/favoris', [FavorisController::class, 'index']);
    Route::post('/questions/{questionId}/favoris', [FavorisController::class, 'store']);
    Route::delete('/favoris/{id}', [FavorisController::class, 'destroy']);

    // Admin
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});
