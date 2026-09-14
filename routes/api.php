<?php

use App\Http\Controllers\Api\V1\Appointments\ShowAppointmentController;
use App\Http\Controllers\Api\V1\Appointments\StoreAppointmentController;
use App\Http\Controllers\Api\V1\Appointments\UpdateAppointmentController;
use App\Http\Controllers\Api\V1\Customers\IndexCustomerController;
use App\Http\Controllers\Api\V1\Customers\ShowCustomerController;
use App\Http\Controllers\Api\V1\Customers\StoreCustomerController;
use App\Http\Controllers\Api\V1\Customers\UpdateCustomerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('/customers', IndexCustomerController::class)->name('customers.index');
    Route::get('/customers/{customer}', ShowCustomerController::class)->name('customers.show');
    Route::post('/customers', StoreCustomerController::class)->name('customers.store');
    Route::put('/customers/{customer}', UpdateCustomerController::class)->name('customers.update');

    Route::get('/appointments/{appointment}', ShowAppointmentController::class)->name('appointments.show');
    Route::post('/jobs/{job}/appointments', StoreAppointmentController::class)->name('appointments.store');
    Route::put('/appointments/{appointment}', UpdateAppointmentController::class)->name('appointments.update');
});
