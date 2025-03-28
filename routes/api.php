<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/login', [AuthController::class, 'login']);

    Route::get('/orders/ready', [OrderController::class, 'getReadyOrders']);
    Route::put('/orders/{order}/deliver', [OrderController::class, 'deliverOrder']);

