<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverJobController extends Controller
{
    public function getDriverJobs($driverId)
{
    // Check if driver exists
    $driver = \App\Models\User::find($driverId);
    if (!$driver) {
        return response()->json([
            'status' => 'error',
            'message' => 'Driver not found'
        ], 404);
    }

    $driverid = \App\Models\Driver::where('user_id', $driverId)->first();

    // Get all jobs for this driver with related details
    $jobs = \App\Models\ServiceJob::with([
        'vehicle',
        'passengers.passenger'
    ])
    ->where('driver_id', $driverid->id)
    ->get();

    
    return response()->json([
        'status' => 'success',
        'driver' => [
            'id' => $driver->id,
            'name' => $driver->name,
            'email' => $driver->email,
        ],
        'jobs' => $jobs
    ]);
}

}
