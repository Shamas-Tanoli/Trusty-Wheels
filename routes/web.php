<?php

use App\Models\Make;
use App\Models\Vehicle;
use App\Models\Location;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\pages\MiscError;

use App\Http\Controllers\pages\WebsitePage;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\MakeController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\TownController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ModelController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingTypeController;
use App\Http\Controllers\Admin\ServiceTimeController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\VehicleCheckController;
use App\Http\Controllers\Admin\ServiceVehicleController;
use App\Http\Controllers\Admin\BookingPassengerController;


Route::get('/run-migrations', function () {
    try {
        Artisan::call('migrate', [
            '--force' => true // production me bhi run ho jaye
        ]);
        $output = Artisan::output();
        return nl2br($output);
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});



Route::get('/', [WebsitePage::class, 'home'])->name('home');
Route::get('/vehicle', [WebsitePage::class, 'vehicle'])->name('vehicle');
Route::get('/available-vehicle', [WebsitePage::class, 'availablevehicle'])->name('available.vehicle');
Route::get('/arriving-vehicle', [WebsitePage::class, 'arrivingvehicle'])->name('arriving.vehicle');
Route::get('/about', [WebsitePage::class, 'about'])->name('about');
Route::get('/contact', [WebsitePage::class, 'contact'])->name('contact');
Route::get('/warranty', [WebsitePage::class, 'warranty'])->name('warranty');
Route::get('/partexchange', [WebsitePage::class, 'partexchange'])->name('partexchange');
Route::get('/vehicle/{id}', [WebsitePage::class, 'vehicleDetail'])->name('vehicle.detail');


Route::fallback(fn() => response()->view('frontend.pages.error', [], 404));




// Route::middleware(RedirectIfAuthenticated::class)->group(function () {

// });

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.store');







Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// authentication

// Route::middleware(['auth.role:admin'])->prefix('dashboard')->group(function () {

// });
Route::prefix('dashboard')->group(function () {
    // Location Routes
    Route::controller(LocationController::class)->prefix('location')->group(function () {
        Route::get('/', 'index')->name('Listings.location');
        Route::post('/create', 'store')->name('location.create');
        Route::get('/list', 'list')->name('location.list');
        Route::post('/{id}/edit', 'update')->name('location.update');
        Route::delete('/delete/{id}', 'destroy')->name('location.destroy');
    });

    



    Route::controller(MakeController::class)->prefix('make')->group(function () {
        Route::get('/', 'index')->name('Listings.make');
        Route::post('/create', 'store')->name('make.create');
        Route::get('/list', 'list')->name('make.list');
        Route::post('/{id}/edit', 'update')->name('make.update');
        Route::delete('/delete/{id}', 'destroy')->name('make.destroy');
    });

    // Model Routes
    Route::controller(ModelController::class)->prefix('model')->group(function () {
        Route::get('/', 'index')->name('Listings.model');
        Route::post('/create', 'store')->name('model.create');
        Route::get('/list', 'list')->name('model.list');
        Route::post('/{id}/edit', 'update')->name('model.update');
        Route::delete('/delete/{id}', 'destroy')->name('model.destroy');
    });

    // Amenity Routes
    Route::controller(AmenityController::class)->prefix('amenity')->group(function () {
        Route::get('/', 'index')->name('Listings.amenity');
        Route::post('/create', 'store')->name('amenity.create');
        Route::get('/list', 'list')->name('amenity.list');
        Route::post('/{id}/edit', 'update')->name('amenity.update');
        Route::delete('/delete/{id}', 'destroy')->name('amenity.destroy');
    });


    // Vehicle Type Routes
    Route::controller(VehicleTypeController::class)->prefix('vehicle-type')->group(function () {
        Route::get('/', 'index')->name('Listings.vehicle.type');
        Route::post('/create', 'store')->name('vehicle.type.store');
        Route::get('/list', 'list')->name('vehicle.type.list');
        Route::post('/{id}/edit', 'update')->name('vehicle.type.update');
        Route::delete('/delete/{id}', 'destroy')->name('vehicle.type.destroy');
    });
    // Vehicle 


    Route::controller(VehicleController::class)->prefix('vehicle')->group(function () {
        Route::get('/', 'index')->name('Listings.vehicle.list');
        Route::post('/create', 'store')->name('vehicle.store');
        Route::get('/add', 'add')->name('Listings.vehicle.add');
        Route::get('/{id}/edit', 'create')->name('Listings.vehicle.create');
        Route::post('/{id}/edit', 'update')->name('vehicle.update');
        Route::delete('/delete/{id}', 'destroy')->name('vehicle.destroy');
    });

    Route::resource('cities', CityController::class);
   

    Route::controller(TownController::class)->prefix('towns')->group(function () {
        Route::get('/', 'index')->name('town.show');
        Route::post('/store', 'store')->name('town.store');        
        Route::get('/list', 'list')->name('town.list');
        Route::delete('/delete/{id}', 'destroy')->name('town.destroy');
        Route::post('{id}/update', 'update')->name('town.update');

       
    });


    Route::controller(ServiceController::class)->prefix('service')->group(function () {
        Route::get('/', 'index')->name('service.show');
        Route::post('/store', 'store')->name('service.store');        
        Route::get('/list', 'list')->name('service.list');
        Route::post('{id}/update', 'update')->name('service.update');
        Route::delete('/delete/{id}', 'destroy')->name('service.destroy');

       
    });

    Route::controller(ServiceTimeController::class)->prefix('servicetime')->group(function () {
        Route::get('/', 'index')->name('servicetime.show');
        Route::post('/store', 'store')->name('servicetime.store');        
        Route::get('/list', 'list')->name('servicetime.list');
        Route::post('{id}/update', 'update')->name('servicetime.update');
        Route::delete('/delete/{id}', 'destroy')->name('servicetime.destroy');

       
    });


    Route::controller(DriverController::class)->prefix('driver')->group(function () {
        Route::get('/', 'listVew')->name('driver.listview');
        Route::get('/create', 'create')->name('driver.create');
        Route::post('/store', 'store')->name('driver.store'); 
        Route::get('/list', 'list')->name('driver.list');
        Route::get('/{driver}/edit', 'edit')->name('driver.edit');
        Route::post('/update', 'update')->name('driver.update');
        Route::delete('/delete/{id}', 'destroy')->name('driver.destroy');       
        Route::get('{id}/detail', 'detail')->name('driver.detail');       

       
    });


    
    Route::controller(PlanController::class)->prefix('plan')->group(function () {
        Route::get('/', 'index')->name('plan.show');
        Route::post('/store', 'store')->name('plan.store');        
        Route::get('/list', 'list')->name('plan.list');
        Route::post('{plan}/update', 'update')->name('plan.update');
        Route::delete('/delete/{id}', 'destroy')->name('plan.destroy');

       
    });
    Route::controller(BookingController::class)->prefix('booking')->group(function () {
        Route::get('/', 'index')->name('booking.show');
        Route::get('/list', 'list')->name('booking.list');
        Route::get('/detail/{id}', 'bookingdetail')->name('booking.detail');
        Route::post('/status/update', 'bookingstatus')->name('booking.status');
       

         Route::get('/notification', 'send')->name('booking.noti');
       
    });
Route::get('/notifications', [NotificationController::class, 'send'])->name('booking.noti');



Route::controller(ServiceVehicleController::class)->prefix('service-vehicle')->group(function () {
        Route::get('/', 'index')->name('service.vehicle.show');
        Route::post('/store', 'store')->name('service.vehicle.store');
        Route::get('/list', 'list')->name('service.vehicle.list');
        Route::delete('/delete/{id}', 'destroys')->name('service.vehicle.destroy');
        Route::post('/{id}/update', 'updatee')->name('service.vehicle.update');
        
       
    });


    Route::controller(VehicleCheckController::class)->prefix('vehicle-check')->group(function () {
        Route::get('/', 'index')->name('vehicle.check.show');
        Route::post('/store', 'store')->name('vehicle.check.store');
        Route::get('/list', 'list')->name('vehicle.check.list');
        Route::delete('/delete/{id}', 'destroy')->name('vehicle.check.destroy');
        Route::post('/{id}/update', 'update')->name('vehicle.check.update');
        
       
    });
    Route::controller(BookingTypeController::class)->prefix('booking-type')->group(function () {
        Route::get('/', 'index')->name('booking.type.show');
        Route::post('/store', 'store')->name('booking.type.store');
        Route::get('/list', 'list')->name('booking.type.list');
        Route::delete('/delete/{id}', 'destroy')->name('booking.type.destroy');
        Route::post('/{id}/update', 'update')->name('booking.type.update');
    });

   
    Route::controller(JobController::class)->prefix('job')->group(function () {
        Route::get('/', 'list')->name('job.show');
        Route::get('/create', 'create')->name('job.create');
        Route::post('/store', 'store')->name('job.store');  
    });



     Route::controller(JobController::class)->prefix('promo')->group(function () {
        Route::get('/', 'list')->name('promo.show');
        Route::get('/create', 'create')->name('promo.create');
        Route::post('/store', 'store')->name('promo.store');  
    });

});












// For Dropdown Select Options admin

Route::get('/get-passenger', [BookingPassengerController::class, 'getpassenger'])->name('select.passenger');
Route::get('/get-driver', [DriverController::class, 'getdriver'])->name('select.driver');
Route::get('/get-servicevehicle', [ServiceVehicleController::class, 'getservicevehicle'])->name('select.servicevehicle');
Route::get('/get-towns', [TownController::class, 'gettowns'])->name('select.towns');
Route::get('/get-service-time', [ServiceTimeController::class, 'getServiceTimes'])->name('select.servicetime');
Route::get('/get-service', [ServiceController::class, 'getservice'])->name('select.service');
Route::get('/get-cities', [CityController::class, 'getcities'])->name('select.cities');
Route::get('/get-location', [LocationController::class, 'getLocation'])->name('select.location');
Route::get('/get-vehicle-types', [VehicleTypeController::class, 'getVehicleTypes'])
    ->name('select.vehicle.types');
Route::get('/get-make', [MakeController::class, 'getMake'])->name('select.make');
Route::get('/get-model', [ModelController::class, 'getModel'])->name('select.model');
Route::get('/get-make-model', [ModelController::class, 'ModelByMake'])->name('select.make.model');
Route::get('/get-vehicle', [VehicleController::class, 'slectVehicle'])->name('select.vehicle');





// frontend dropdown
Route::get('frontend/locations', function () {
    return response()->json(Location::select('id', 'name')->get());
});
Route::get('frontend/make', function () {
    return response()->json(Make::select('id', 'name')->get());
});
Route::get('frontend/model', function () {
    return response()->json(VehicleModel::select('id', 'name')->get());
});
Route::get('/models/{makeId}', function ($makeId) {
    return response()->json(VehicleModel::where('make_id', $makeId)->select('id', 'name')->get());
});
Route::get('/frontend/makes/{locationId}', function ($locationId) {
    $makeIds = Vehicle::where('location_id', $locationId)
        ->groupBy('make_id')
        ->pluck('make_id');

    $makes = Make::whereIn('id', $makeIds)
        ->select('id', 'name')
        ->get();

    return response()->json($makes);
});










// Route::controller(VehicleTypeController::class)->prefix('vehicle-type')->name('vehicle.type.')
// ->group(function () {
//     Route::get('/', 'index')->name('index'); 
//     Route::post('/create', 'store')->name('store'); 
//     Route::get('/list', 'list')->name('list'); 
//     Route::post('/{id}/edit', 'update')->name('update'); 
//     Route::delete('/{id}/delete', 'destroy')->name('destroy'); 
// });