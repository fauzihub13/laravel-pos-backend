<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Login
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

// API Products
Route::apiResource('/api-products', App\Http\Controllers\Api\ProductController::class)->middleware('auth:sanctum');

// API Categories
Route::apiResource('/api-categories', App\Http\Controllers\Api\CategoryController::class)->middleware('auth:sanctum');
