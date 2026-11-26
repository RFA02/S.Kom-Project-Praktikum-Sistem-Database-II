<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\ProductionRecordController;
use App\Http\Controllers\SurplusListingController;
use App\Http\Controllers\ReservationController;

// Authentication API

Route::post('/register', [AuthController::class, 'registerApi'])->middleware('throttle:10,1');
Route::post('/login', [AuthController::class, 'loginApi'])->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/logout', [AuthController::class, 'logoutApi']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Fitur khusus akun Business (foods, production-records, surplus-listings)
    Route::middleware('role:business')->group(function () {

        // Food Management API
        Route::get('/foods', [FoodController::class, 'index']);
        Route::post('/foods', [FoodController::class, 'store']);
        Route::post('/foods/{id}/image', [FoodController::class, 'uploadImage']);
        Route::post('/foods/{id}/restore', [FoodController::class, 'restore']);
        Route::get('/foods/{id}', [FoodController::class, 'show']);
        Route::put('/foods/{id}', [FoodController::class, 'update']);
        Route::delete('/foods/{id}', [FoodController::class, 'destroy']);

        // Production Records API
        Route::get('/production-records', [ProductionRecordController::class, 'index']);
        Route::post('/production-records', [ProductionRecordController::class, 'store']);
        Route::get('/production-records/{id}', [ProductionRecordController::class, 'show']);
        Route::put('/production-records/{id}', [ProductionRecordController::class, 'update']);
        Route::delete('/production-records/{id}', [ProductionRecordController::class, 'destroy']);

        // Surplus Listings API
        Route::get('/surplus-listings', [SurplusListingController::class, 'index']);
        Route::post('/surplus-listings', [SurplusListingController::class, 'store']);
        Route::get('/surplus-listings/{id}', [SurplusListingController::class, 'show']);
        Route::put('/surplus-listings/{id}', [SurplusListingController::class, 'update']);
        Route::delete('/surplus-listings/{id}', [SurplusListingController::class, 'destroy']);

    });

    // Reservations API
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

});
