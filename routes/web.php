<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);
});

Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'store']);
    Route::post('/{order}/pay', [OrderController::class, 'pay']);
    Route::post('/{order}/cancel', [OrderController::class, 'cancel']);
    Route::get('/user/{user}', [OrderController::class, 'index']);
    Route::get('/{order}', [OrderController::class, 'get']);
});
