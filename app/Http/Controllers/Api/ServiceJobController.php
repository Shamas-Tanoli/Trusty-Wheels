<?php

namespace App\Http\Controllers\Api;

use App\Models\ServiceJob;
use Illuminate\Http\Request;
use App\Models\ServiceJobTrack;
use App\Models\BookingPassenger;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceJobTripTrack;
use App\Http\Controllers\Controller;
use App\Models\ServiceJobPassengerTrack;

class ServiceJobController extends Controller
{
   public function createJobTracking(Request $request)
{
    $request->validate([
        'service_job_id' => 'required|exists:service_jobs,id',
    ]);

    // 🔍 Check: already exists or not
    $alreadyExists = ServiceJobTrack::where('service_job_id', $request->service_job_id)
        ->where('status', 'ongoing')
        ->exists();

    if ($alreadyExists) {
        return response()->json([
            'status' => false,
            'message' => 'Job tracking already started for this service job',
        ], 409);
    }

    $serviceJob = ServiceJob::with('passengers')->findOrFail($request->service_job_id);

    DB::transaction(function () use ($serviceJob) {

        // 1️⃣ Create Job Track
        $jobTrack = ServiceJobTrack::create([
            'service_job_id' => $serviceJob->id,
            'status' => 'ongoing',
        ]);

        // 2️⃣ Create Trip Track
        ServiceJobTripTrack::create([
            'service_job_track_id' => $jobTrack->id,
            'driver_id' => $serviceJob->driver_id,
            'vehicle_id' => $serviceJob->vehicle_id,
            'trip_one_status' => 'pending',
            'trip_two_status' => 'pending',
        ]);

        // 3️⃣ Passenger Tracks
        foreach ($serviceJob->passengers as $passenger) {
            ServiceJobPassengerTrack::create([
                'service_job_track_id' => $jobTrack->id,
                'service_job_passengers_id' => $passenger->id,
                'status' => 'pending',
                'pickup_trip_one' => 'pending',
                'dropoff_trip_one' => 'pending',
                'pickup_trip_two' => 'pending',
                'dropoff_trip_two' => 'pending',
            ]);
        }
    });

    return response()->json([
        'status' => true,
        'message' => 'Job Started Successfully',
    ]);
}

}
