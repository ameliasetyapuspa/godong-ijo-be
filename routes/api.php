<?php
use App\Http\Controllers\UserController;

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth.token')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::delete('/logout', [UserController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']); 
});
