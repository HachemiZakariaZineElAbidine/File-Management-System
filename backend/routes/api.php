<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/files',                [FileController::class, 'index']);
    Route::post('/files',               [FileController::class, 'store']);
    Route::get('/files/{file}/download',[FileController::class, 'download']);
    Route::delete('/files/{file}',      [FileController::class, 'destroy']);
});
