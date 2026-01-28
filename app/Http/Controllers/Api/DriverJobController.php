<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Driver;
use App\Models\ServiceJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DriverJobController extends Controller
{

    public function getDriverJobDetails($userid, $jobId)
    {
      

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
            'jobTrack.passengerTracks',
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
public function getDriverJobs(Request $request, $driverId, $serviceTimeId = null)
{
    
    if ($serviceTimeId) {
        $request->merge(['service_time_id' => $serviceTimeId]);

        $request->validate([
            'service_time_id' => 'nullable'
        ]);
    }

    // Driver user check
    $driverUser = \App\Models\User::find($driverId);
    if (!$driverUser) {
        return response()->json([
            'status' => 'error',
            'message' => 'Driver not found'
        ], 404);
    }

    
    $driver = \App\Models\Driver::where('user_id', $driverId)->first();
    if (!$driver) {
        return response()->json([
            'status' => 'error',
            'message' => 'Driver profile not found'
        ], 404);
    }

   
    $jobs = \App\Models\ServiceJob::with([
            'vehicle',
            'passengers.passenger',
            'serviceTime',
        ])
        ->where('driver_id', $driver->id)
        ->when($serviceTimeId, function ($query) use ($serviceTimeId) {
            $query->where('service_time_id', $serviceTimeId);
        })
        ->orderBy('id', 'desc')
        ->get();

    return response()->json([
        'status' => 'success',
        'driver' => [
            'id'    => $driverUser->id,
            'name'  => $driverUser->name,
            'email' => $driverUser->email,
        ],
        'jobs' => $jobs
    ]);
}

}
