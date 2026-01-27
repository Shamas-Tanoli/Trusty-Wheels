<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\DriverJobController;
use App\Http\Controllers\Api\DriverAuthController;
use App\Http\Controllers\Api\ServiceJobController;
use App\Http\Controllers\Api\ServiceJobPassangerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/customer/register', [AuthController::class, 'register']);
Route::post('/customer/login', [AuthController::class, 'login']);




Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    Route::get('/plans', [PlanController::class, 'index']);

    Route::get('/service/{id}/service-time', [ServiceController::class, 'serviceTimeByService']);
    Route::get('/service-time/{id}/plans', [ServiceController::class, 'plansByServiceTime']);

    Route::get('/area/service/time/plan', [BookingController::class, 'allThing']);
    Route::get('/area', [BookingController::class, 'area']);
    Route::post('/plan/by-criteria', [BookingController::class, 'areaToAreaFromServiceTimePlan']);

    Route::get('/services', [ServiceController::class, 'index']);
    Route::post('/customer/booking', [BookingController::class, 'store']);
    Route::get('/bookingtype', [BookingController::class, 'bookingtype']);
    Route::get('/customer/bookings/all', [BookingController::class, 'customerbooking']);
    Route::post('/passenger/status', [BookingController::class, 'passengerStatus']);
    Route::get('/customer/children', [ServiceJobPassangerController::class, 'getChildrenWithJobs']);
    
    Route::post('/customer/booking/{id}/passenger/add', [BookingController::class, 'addChildren']);


});

// booking me child add krny hen 




Route::post('/driver/login', [DriverAuthController::class, 'login']);



// Driver Only APIs
Route::middleware(['auth:sanctum', 'role:driver'])->group(function () {
    Route::post('/driver/logout', [DriverAuthController::class, 'logout']);
Route::get('driver/{driver}/jobs/{timeid?}',[DriverJobController::class, 'getDriverJobs']);
    Route::get('driver/{driver}/jobs/{job}', [DriverJobController::class, 'getDriverJobDetails']);
    Route::post('driver/service/jobs', [ServiceJobController::class, 'createJobTracking']);

    

    
    Route::post('driver/service-job/passenger-tracks', [ServiceJobPassangerController::class, 'getServiceJobPassengerTracks']);

    Route::post('service-job-passenger-track/pickup-trip-one', [ServiceJobPassangerController::class, 'updatePickupTripOne']);

    Route::post('service-job-passenger-track/dropoff-trip-one', [ServiceJobPassangerController::class, 'updateDropoffTripOne']);
    Route::post('service-job-passenger-track/pickup-trip-two', [ServiceJobPassangerController::class, 'updatePickupTripTwo']);
    Route::post('service-job-passenger-track/dropoff-trip-two', [ServiceJobPassangerController::class, 'updateDropoffTripTwo']);
    
});




// Customer + Driver APIs
Route::middleware(['auth:sanctum', 'role:customer,driver'])->group(function () {
    Route::post('/customer/logout', [AuthController::class, 'logout']);
});
