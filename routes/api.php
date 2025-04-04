<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/chat/send', [MessageController::class, 'sendMessage']);
    Route::get('/chat/messages/{user_id}', [MessageController::class, 'getMessages']);
    Route::patch('/chat/read/{message_id}', [MessageController::class, 'markMessageAsRead']);
    Route::get('/online', [MessageController::class, 'setOnline']);
    Route::get('/offline', [MessageController::class, 'setOffline']);
    Route::post('/logout', [AuthController::class, 'logout']);
});