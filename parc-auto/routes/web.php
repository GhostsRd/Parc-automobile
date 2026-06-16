<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\VehicleMileageController;
use App\Http\Controllers\VehicleDocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FuelController;
use App\Http\Controllers\GpsController;

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
    Route::resource('documents', VehicleDocumentController::class);
    Route::resource('mileage', VehicleMileageController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('fuel', FuelController::class);

    Route::get('/gps', [GpsController::class, 'index'])->name('gps.index');
    Route::get('/gps/history/{vehicle}', [GpsController::class, 'history'])->name('gps.history');
    Route::post('/gps/ping', [GpsController::class, 'storePing']);

    Route::get('/mission', function () {
        return view('mission'); // Nom du fichier blade
    })->name('mission');
});

