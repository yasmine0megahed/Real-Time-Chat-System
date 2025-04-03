<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    // Route::post('/chat/send', [ChatController::class, 'sendMessage']);
    // Route::get('/chat/messages/{user}', [ChatController::class, 'getMessages']);
    // Route::patch('/chat/read/{message}', [ChatController::class, 'markAsRead']);
    Route::post('/logout', [AuthController::class, 'logout']);
});