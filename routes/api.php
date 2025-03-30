<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('token')->group(function () {
    Route::post('/test', function () {
        return response()->json(['message' => 'Token is valid']);
    });
});
