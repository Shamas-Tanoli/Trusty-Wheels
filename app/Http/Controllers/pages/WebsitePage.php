<?php

namespace App\Http\Controllers\pages;

use App\Models\Make;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebsitePage extends Controller
{
  public function home()
  {
    $vehicles = Vehicle::with([
      'make',
      'vehicleModel',
      'location',
      'vehicleImages' => function ($query) {
        $query->where('image_order', 1);
      }
    ])
      ->whereIn('status', ['available'])
      ->orderBy('id', 'desc')
      ->limit(6)
      ->get();




    $arrivingVehicles = Vehicle::with([
      'make',
      'vehicleModel',
      'location',
      'vehicleImages' => function ($query) {
        $query->where('image_order', 1);
      }
    ])
      ->where('status', 'arriving_soon')
      ->orderBy('id', 'desc') // last entry first
      ->limit(6)
      ->get();

   $makes = Make::whereHas('vehicles') // sirf wo makes jinke saath vehicles hain
    ->select('name')
    ->limit(5)
    ->pluck('name');



    return view('frontend.pages.home', compact('vehicles', 'makes', 'arrivingVehicles'));
  }


  public function warranty()
  {




    return view('frontend.pages.warranty');
  }
  public function partexchange()
  {




    return view('frontend.pages.partexchange');
  }


  public function about()
  {
    return view('frontend.pages.about');
  }

  public function vehicle(Request $request)
  {
    $query = Vehicle::with([
      'make',
      'vehicleModel',
      'location',
      'vehicleImages' => function ($query) {
        $query->where('image_order', 1);
      }
    ])->where('status', 'sold');

    // Filters apply karna
    if ($request->filled('location_id')) {
      $query->where('location_id', $request->location_id);
    }

    if ($request->filled('make_id')) {
      $query->where('make_id', $request->make_id);
    }

    if ($request->filled('model_id')) {
      $query->where('vehicle_model_id', $request->model_id);
    }
    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }

    // Max price filter
    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    // Pagination
    $vehicles = $query->paginate(15);

    if ($request->ajax()) {
      return view('frontend.components.singlevehicle', compact('vehicles'))->render();
    }

    return view('frontend.pages.vehicle', compact('vehicles'));
  }

  public function arrivingvehicle(Request $request)
  {
    $query = Vehicle::with([
      'make',
      'vehicleModel',
      'location',
      'vehicleImages' => function ($query) {
        $query->where('image_order', 1);
      }
    ])->where('status', 'arriving_soon');

    // Filters apply karna
    if ($request->filled('location_id')) {
      $query->where('location_id', $request->location_id);
    }

    if ($request->filled('make_id')) {
      $query->where('make_id', $request->make_id);
    }

    if ($request->filled('model_id')) {
      $query->where('vehicle_model_id', $request->model_id);
    }

    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }

    
    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    // Pagination
    $vehicles = $query->paginate(15);

    if ($request->ajax()) {
      return view('frontend.components.singlevehicle', compact('vehicles'))->render();
    }

    return view('frontend.pages.arriving-vehicle', compact('vehicles'));
  }


  public function availablevehicle(Request $request)
  {
    $query = Vehicle::with([
      'make',
      'vehicleModel',
      'location',
      'vehicleImages' => function ($query) {
        $query->where('image_order', 1);
      }
    ])->where('status', 'available');

    // Filters apply karna
    if ($request->filled('location_id')) {
      $query->where('location_id', $request->location_id);
    }

    if ($request->filled('make_id')) {
      $query->where('make_id', $request->make_id);
    }

    if ($request->filled('model_id')) {
      $query->where('vehicle_model_id', $request->model_id);
    }

    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }

    // Max price filter
    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    // Pagination
    $vehicles = $query->paginate(15);

    if ($request->ajax()) {
      return view('frontend.components.singlevehicle', compact('vehicles'))->render();
    }

    return view('frontend.pages.available-vehicle', compact('vehicles'));
  }

  public function vehicleDetail(int $id)
  {
    $vehicle = Vehicle::with([
      'vehicleAmenities',
      'make',
      'vehicleModel',
      'vehicleType',
      'location',
      'vehicleImages' => fn($query) => $query->orderBy('image_order')
    ])->where('id', $id)->firstOrFail();

    return view('frontend.pages.vehicle-detail', compact('vehicle'));
  }

  public function contact()
  {
    return view('frontend.pages.contact');
  }
}
