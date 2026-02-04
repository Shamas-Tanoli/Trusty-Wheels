<?php

namespace App\Http\Controllers\Api;

use App\Models\ServiceJob;
use Illuminate\Http\Request;
use App\Models\ServiceJobTrack;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceJobPassenger;
use App\Http\Controllers\Controller;

class JobStartController extends Controller
{
    public function status(Request $request)
    {


        $request->validate([
            'service_job_id' => 'required|exists:service_jobs,id',
            'status' => 'required|in:ongoing,pending,completed',
        ]);
        $passengerTrack = ServiceJobTrack::where('service_job_id', $request->service_job_id)
            ->update(['status' => $request->status]);
            
        return response()->json([
            'status' => false,
            'message' => 'status change',
            'status' => $request->status
        ], 200);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'driver_id'      => 'required|exists:users,id',
            'vehicle_id'     => 'required|exists:service_vehicles,id',
            'date'           => 'required|date',
            'passenger_ids'  => 'required|array',
            'passenger_ids.*' => 'exists:booking_passengers,id|distinct',
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
