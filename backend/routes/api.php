<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\DashboardController;

Route::middleware('throttle:5,1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'metrics']);
    Route::get('/dashboard/best-time', [DashboardController::class, 'bestCollectionTime']);

    // Loans
    Route::get('/loans', [LoanController::class, 'index']);
    Route::get('/loans/{id}', [LoanController::class, 'show']);
    Route::post('/loans', [LoanController::class, 'store'])->middleware('role:admin');

    // Collections
    Route::post('/collections', [CollectionController::class, 'store'])->middleware('role:field_agent');
});
