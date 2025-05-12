<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/items', function(Request $request) {

    // استقبال البيانات
    $item = $request->all();


    return response()->json([
        'status' => 'success',
        'received' => $item
    ]);

});
