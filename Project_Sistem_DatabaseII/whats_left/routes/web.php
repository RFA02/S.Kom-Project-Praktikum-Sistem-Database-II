<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessDashboardController;
use App\Http\Controllers\BusinessAnalyticsController;
use App\Http\Controllers\ProductionRecordController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\SurplusListingController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\PasswordResetController;

// Landing Page
Route::get('/', [ConsumerController::class, 'landing'])->name('home');

// Consumer Explore and Food Details
Route::get('/explore', [ConsumerController::class, 'explore'])->name('consumer.explore');
Route::get('/consumer/foods/{id}', [ConsumerController::class, 'foodDetails'])->name('consumer.food-details');

// Auth Routes untuk pengunjung yang belum login
Route::middleware('guest')->group(function () {
    // Login Page
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    // Login Process
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.authenticate');

    // Register Page
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    // Register Process
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:10,1')
        ->name('register.store');

    // Reset Password
    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])
        ->middleware('throttle:5,1')
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])
        ->middleware('throttle:5,1')
        ->name('password.update');
});

// Routes untuk pengguna yang sudah login
Route::middleware('auth')->group(function () {

    // Logout Process
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');
        Route::put('/admin/businesses/{id}/verification', [AdminController::class, 'updateVerification'])
            ->name('admin.businesses.verification');
    });

    // Consumer Routes
    Route::middleware('role:customer')->group(function () {
        Route::get('/consumer/my-reservations', [ConsumerController::class, 'myReservations'])
            ->name('consumer.my-reservations');
        Route::get('/consumer/profile', [ConsumerController::class, 'profile'])
            ->name('consumer.profile');
        Route::put('/consumer/profile', [ConsumerController::class, 'updateProfile'])
            ->name('consumer.profile.update');
        Route::get('/consumer/notifications', [ConsumerController::class, 'notifications'])
            ->name('consumer.notifications');
        Route::post('/consumer/reservations', [ReservationController::class, 'store'])
            ->name('consumer.reservations.store');
        Route::put('/consumer/reservations/{id}/cancel', [ReservationController::class, 'update'])
            ->name('consumer.reservations.cancel');
    });

    // Business Routes
    Route::middleware('role:business')->group(function () {

        // Business Dashboard
        Route::get('/business/dashboard', [BusinessDashboardController::class, 'index'])
            ->name('business.dashboard');

        // Business Notifications
        Route::get('/business/notifications', [BusinessDashboardController::class, 'notifications'])
            ->name('business.notifications');

        // Business Food Management
        Route::get('/business/food-management', [FoodController::class, 'page'])
            ->name('business.food-management');
        Route::post('/business/food-management', [FoodController::class, 'store'])
            ->name('business.food-management.store');
        Route::post('/business/food-management/{id}/image', [FoodController::class, 'uploadImage'])
            ->name('business.food-management.image');
        Route::post('/business/food-management/{id}/restore', [FoodController::class, 'restore'])
            ->name('business.food-management.restore');
        Route::put('/business/food-management/{id}', [FoodController::class, 'update'])
            ->name('business.food-management.update');
        Route::delete('/business/food-management/{id}', [FoodController::class, 'destroy'])
            ->name('business.food-management.destroy');

        // Business Surplus Listings
        Route::get('/business/surplus-listings', [SurplusListingController::class, 'page'])
            ->name('business.surplus-listings');
        Route::post('/business/surplus-listings', [SurplusListingController::class, 'store'])
            ->name('business.surplus-listings.store');
        Route::put('/business/surplus-listings/{id}', [SurplusListingController::class, 'update'])
            ->name('business.surplus-listings.update');
        Route::delete('/business/surplus-listings/{id}', [SurplusListingController::class, 'destroy'])
            ->name('business.surplus-listings.destroy');

        // Business Reservations Management
        Route::get('/business/reservations-management', [ReservationController::class, 'businessPage'])
            ->name('business.reservations-management');
        Route::put('/business/reservations-management/{id}/status', [ReservationController::class, 'update'])
            ->name('business.reservations-management.status');

        // Production Records
        Route::get('/business/production-records', [ProductionRecordController::class, 'page'])
            ->name('business.production-records');
        Route::post('/business/production-records', [ProductionRecordController::class, 'store'])
            ->name('business.production-records.store');
        Route::put('/business/production-records/{id}', [ProductionRecordController::class, 'update'])
            ->name('business.production-records.update');
        Route::delete('/business/production-records/{id}', [ProductionRecordController::class, 'destroy'])
            ->name('business.production-records.destroy');

        // Business Reservations (legacy route name retained)
        Route::get('/business/reservations', [ReservationController::class, 'businessPage'])
            ->name('business.reservations');

        // Business Analytics
        Route::get('/business/analytics', [BusinessAnalyticsController::class, 'index'])
            ->name('business.analytics');

    });

});
