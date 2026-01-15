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

        $serviceJob = ServiceJob::findOrFail($request->service_job_id);

        DB::transaction(function() use ($serviceJob) {

            
            $jobTrack = ServiceJobTrack::create([
                'service_job_id' => $serviceJob->id,
                'status' => 'ongoing', 
            ]);

           
            ServiceJobTripTrack::create([
                'service_job_track_id' => $jobTrack->id,
                'driver_id' => $serviceJob->driver_id,
                'vehicle_id' => $serviceJob->vehicle_id,
                'trip_one_status' => 'pending',  
                'trip_two_status' => 'pending',       
            ]);

           
            $passengerIds = $serviceJob->passengers->pluck('passenger_id'); 

            foreach($passengerIds as $pid) {
                $bookingPassenger = BookingPassenger::find($pid);

                if($bookingPassenger) {
                    ServiceJobPassengerTrack::create([
                        'service_job_track_id' => $jobTrack->id,
                        'service_job_passengers_id' => $pid,
                        'status' => 'pending',              // picked / drop/absent
                        'pickup_trip_one' =>'pending',  
                        'dropoff_trip_one' => 'pending',  
                        'pickup_trip_two' =>'pending',  
                        'dropoff_trip_two' => 'pending',  
                    ]);
                }
            }
        });

        return response()->json([
            'status' => true,
            'message' => 'Job Started Successfully',
        ]);
    }
}
