<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\ServiceController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('queues/stats', [QueueController::class, 'stats']);

    Route::apiResource('services', ServiceController::class);
    Route::post('queues/{service:id}', [QueueController::class, 'store']);
    Route::get('queues/history', [QueueController::class, 'history']);
    Route::get('queues/{queue:id}', [QueueController::class, 'show']);

    Route::get('patients', [PatientController::class, 'index']);
    Route::get('queues', [QueueController::class, 'index']);
    Route::put('queues/{queue:id}/change-status', [QueueController::class, 'changeStatus']);
});