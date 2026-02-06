<?php

namespace App\Http\Controllers\Api;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleResource;

class VehicleListController extends Controller
{
    public function index()
{
    $vehicles = Vehicle::with([
        'make',
        'vehicleModel',
        'vehicleType',
        'location',
        'vehicleAmenities',
        'vehicleImages'
    ])->latest()->get();

    return VehicleResource::collection($vehicles);
}
}
