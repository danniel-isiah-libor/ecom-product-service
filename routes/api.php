<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('token')->group(function () {
    Route::post('/test', function () {
        return response()->json(['message' => 'Token is valid']);
    });

    Route::post('/products', ProductController::class)
        ->name('products.list');

    Route::resource('/cart', CartController::class);
});
