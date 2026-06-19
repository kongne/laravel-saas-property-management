<?php

use App\Http\Controllers\Api\LeaseApiController;
use App\Http\Controllers\Api\MaintenanceApiController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\PropertyApiController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\TenantApiController;
use App\Http\Controllers\Api\UnitApiController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login']);
Route::get('/locations/districts', [LocationController::class, 'districts']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard/stats', [App\Http\Controllers\Api\DashboardApiController::class, 'stats']);
    Route::get('/search', [SearchController::class, 'search'])->name('api.search');

    Route::name('api.')->middleware('role:admin,landlord')->group(function () {
        Route::apiResource('properties', PropertyApiController::class);
        Route::apiResource('units', UnitApiController::class);
        Route::apiResource('tenants', TenantApiController::class);
        Route::apiResource('leases', LeaseApiController::class);
    });

    Route::name('api.')->group(function () {
        Route::get('/payments', [PaymentApiController::class, 'index']);
        Route::post('/payments', [PaymentApiController::class, 'store'])->middleware('role:admin,landlord');
        Route::get('/payments/{payment}', [PaymentApiController::class, 'show']);
        Route::put('/payments/{payment}', [PaymentApiController::class, 'update'])->middleware('role:admin,landlord');
        Route::delete('/payments/{payment}', [PaymentApiController::class, 'destroy'])->middleware('role:admin,landlord');

        Route::get('/maintenance', [MaintenanceApiController::class, 'index']);
        Route::post('/maintenance', [MaintenanceApiController::class, 'store']);
        Route::get('/maintenance/{maintenanceRequest}', [MaintenanceApiController::class, 'show']);
        Route::put('/maintenance/{maintenanceRequest}', [MaintenanceApiController::class, 'update'])->middleware('role:admin,landlord');
        Route::delete('/maintenance/{maintenanceRequest}', [MaintenanceApiController::class, 'destroy'])->middleware('role:admin,landlord');
    });

    Route::post('/notifications/subscribe', [NotificationController::class, 'subscribe'])->name('notifications.subscribe');
    Route::post('/notifications/unsubscribe', [NotificationController::class, 'unsubscribe'])->name('notifications.unsubscribe');
    Route::get('/notifications/subscriptions', [NotificationController::class, 'subscriptions'])->name('notifications.subscriptions');
});
