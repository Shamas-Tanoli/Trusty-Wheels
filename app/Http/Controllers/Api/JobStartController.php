<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceJob;
use App\Models\ServiceJobPassenger;
use Illuminate\Support\Facades\DB;

class JobStartController extends Controller
{
    
    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
            'driver_id'      => 'required|exists:users,id',
            'vehicle_id'     => 'required|exists:service_vehicles,id',
            'date'           => 'required|date',
            'passenger_ids'  => 'required|array',
            'passenger_ids.*'=> 'exists:booking_passengers,id|distinct',
        ]);

        DB::transaction(function () use ($validatedData) {

           
            $job = ServiceJob::create([
                'driver_id'      => $validatedData['driver_id'],
                'vehicle_id'     => $validatedData['vehicle_id'],
                'status'         => 'active',
                'job_date'       => $validatedData['date'],
                'service_time_id' => 1,
            ]);

            
            foreach ($validatedData['passenger_ids'] as $pid) {
                ServiceJobPassenger::create([
                    'service_job_id' => $job->id,
                    'passenger_id'   => $pid,
                    'status'         => 'active',
                ]);
            }

           
            $job->load(['driver', 'vehicle', 'passengers.passenger.user']);

            
           

        });

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Job created & notifications sent successfully!',
        ]);
    }
}
