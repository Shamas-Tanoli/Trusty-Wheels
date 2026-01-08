<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\DriverAuthController;
use App\Http\Controllers\Api\ServiceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/customer/register', [AuthController::class, 'register']);
Route::post('/customer/login', [AuthController::class, 'login']);


  

Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
       Route::get('/plans', [PlanController::class, 'index']); 
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/service/{id}/service-time', [ServiceController::class, 'serviceTimeByService']); 
    Route::get('/service-time/{id}/plans', [ServiceController::class, 'plansByServiceTime']); 
    Route::post('/customer/booking', [BookingController::class, 'store']); 
    Route::get('/area/service/time/plan', [BookingController::class, 'allThing']);
    Route::get('/area', [BookingController::class, 'area']);
    Route::post('/plan/by-criteria', [BookingController::class, 'areaToAreaFromServiceTimePlan']);
});                                                 
 




Route::post('/driver/login', [DriverAuthController::class, 'login']);
// Driver Only APIs
Route::middleware(['auth:sanctum', 'role:driver'])->group(function () {
    Route::post('/driver/logout', [DriverAuthController::class, 'logout']);
});

// Customer + Driver APIs
Route::middleware(['auth:sanctum', 'role:customer,driver'])->group(function () {
    Route::post('/customer/logout', [AuthController::class, 'logout']);


});


