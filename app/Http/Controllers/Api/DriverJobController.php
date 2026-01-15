<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverJobController extends Controller
{

    public function getDriverJobDetails($userid, $jobId){

        $driver = \App\Models\User::find($userid);
        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver not found'
            ], 404);
        }

        $driverModel = \App\Models\Driver::where('user_id', $driver->id)->first();
        if (!$driverModel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Driver profile not found'
            ], 404);
        }

        
        $job = \App\Models\ServiceJob::with([
            'vehicle',
            'passengers.passenger'
        ])
        ->where('id', $jobId)
        ->where('driver_id', $driverModel->id)
        ->first();

        if (!$job) {
            return response()->json([
                'status' => 'error',
                'message' => 'Job not found for this driver'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'driver' => [
                'id' => $driver->id,
                'name' => $driver->name,
                'email' => $driver->email,
            ],
            'job' => $job
        ]);

    }

    public function getDriverJobs($driverId)
{
    
    $driver = \App\Models\User::find($driverId);
    if (!$driver) {
        return response()->json([
            'status' => 'error',
            'message' => 'Driver not found'
        ], 404);
    }

    $driverid = \App\Models\Driver::where('user_id', $driverId)->first();

   
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
