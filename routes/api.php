<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\OrderItemController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout/{user}', [AuthController::class, 'logout'])->middleware('auth:api');
Route::patch('/users/{user}', [UserController::class, 'update'])->middleware('auth:api');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('auth:api');

Route::middleware('auth:api')->group(fn()=> Route::apiResource('orders', OrderController::class));
Route::middleware('auth:api')->group(fn()=> Route::apiResource('products', ProductController::class));
Route::middleware('auth:api')->group(function(){
    Route::get('/order_items', [OrderItemController::class, 'index']);
    Route::post('/order_items/{orderId}/{productId}', [OrderItemController::class, 'store']);
    Route::get('/order_items/{orderItem}', [OrderItemController::class, 'show']);
    Route::patch('/order_items/{orderItem}', [OrderItemController::class, 'update']);
    Route::delete('/order_items/{orderItem}', [OrderItemController::class, 'destroy']);
});