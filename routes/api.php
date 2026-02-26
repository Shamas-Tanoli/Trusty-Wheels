<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\JobStartController;
use App\Http\Controllers\Api\DriverJobController;
use App\Http\Controllers\Api\DriverAuthController;
use App\Http\Controllers\Api\ServiceJobController;
use App\Http\Controllers\Api\VehicleListController;
use App\Http\Controllers\Api\NotificationController;
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
    Route::post('/customer/booking/apply/promo', [BookingController::class, 'applyPromo']);


    Route::post('/customer/feedback', [FeedbackController::class, 'store']);
    Route::get('/customer/feedback/', [FeedbackController::class, 'approvedFeedback']);
});






Route::post('/driver/login', [DriverAuthController::class, 'login']);
Route::post('/driver/verify-otp', [DriverAuthController::class, 'verifyOtp']);


// Driver Only APIs
Route::middleware(['auth:sanctum', 'role:driver'])->group(function () {
    Route::post('/driver/logout', [DriverAuthController::class, 'logout']);
    Route::get('driver/{driver}/jobs/{timeid?}', [DriverJobController::class, 'getDriverJobs']);
    Route::get('driver/{driver}/jobsdetail/{job}', [DriverJobController::class, 'getDriverJobDetails']);
    Route::post('driver/service/jobs', [ServiceJobController::class, 'createJobTracking']);




    Route::post('driver/service-job/passenger-tracks', [ServiceJobPassangerController::class, 'getServiceJobPassengerTracks']);

    // Route::post('driver/passenger/trip/one/dropoff/status', [ServiceJobPassangerController::class, 'updateDropoffTripOne']);
    // Route::post('driver/passenger/trip/two/pickup/status', [ServiceJobPassangerController::class, 'updatePickupTripTwo']);
    // Route::post('driver/passenger/trip/two/dropoff/status', [ServiceJobPassangerController::class, 'updateDropoffTripTwo']);

    Route::post(
        'driver/passenger/trip/status/update',
        [
            ServiceJobPassangerController::class,
            'updatePassengerTripStatus'
        ]
    );

    // Route::post('driver/job/trip/one/status', [ServiceJobController::class, 'tripOne']);
    // Route::post('driver/job/trip/two/status', [ServiceJobController::class, 'tripTwo']);

    Route::post('driver/job/trip/status', [ServiceJobController::class, 'updateTripStatus']);


    Route::post('driver/job/status', [JobStartController::class, 'status']);
});

Route::post('start/job', [JobStartController::class, 'store']);




// Customer + Driver APIs
Route::middleware(['auth:sanctum', 'role:customer,driver'])->group(function () {
    Route::post('/customer/logout', [AuthController::class, 'logout']);


     Route::get('driver/profile', [ProfileController::class, 'driverProfile']);
    Route::get('/customer/profile', [ProfileController::class, 'customerProfile']);

   
     Route::delete('/users/delete', [UserController::class, 'destroy']);
    Route::get('/listing/vehicles', [VehicleListController::class, 'index']);
    Route::post('/listing/vehicles/inquiries', [InquiryController::class, 'store']);

    Route::get('/notifications', [NotificationController::class, 'getUserNotifications']);


   
});
