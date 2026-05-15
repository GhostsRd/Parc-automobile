<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

     Route::get('/voiture', function () {
        return view('voiture'); // Nom du fichier blade
    })->name('voiture');

    Route::resource('vehicles', VehicleController::class);
    Route::resource('maintenances', MaintenanceController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('drivers', DriverController::class);
});

