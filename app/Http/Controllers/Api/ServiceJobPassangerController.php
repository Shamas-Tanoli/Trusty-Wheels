<?php

namespace App\Http\Controllers\Api;

use App\Models\ServiceJob;
use Illuminate\Http\Request;
use App\Models\ServiceJobPassenger;
use App\Http\Controllers\Controller;

class ServiceJobPassangerController extends Controller
{
    public function getChildrenWithJobs(Request $request)
    {
        $customer = $request->user();


        $children = ServiceJobPassenger::whereHas('passenger', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
            ->with([
                'passenger.booking',
                'passenger.plan',
                'serviceJob.driver',
                'serviceJob.vehicle',
            ])
            ->get()
            ->map(function ($sjp) {
                return [
                    'passenger_id'   => $sjp->passenger->id,
                    'name'           => $sjp->passenger->name,
                    'pickup_time'    => $sjp->passenger->pickup_time,
                    'dropoff_time'   => $sjp->passenger->dropoff_time,
                    'pickup_location' => $sjp->passenger->pickup_location,
                    'dropoff_location' => $sjp->passenger->dropoff_location,
                    'service_job'    => [
                        'id'         => $sjp->serviceJob->id,
                        'date'       => $sjp->serviceJob->job_date,
                        'driver'     => $sjp->serviceJob->driver->name ?? null,
                        'vehicle'    => $sjp->serviceJob->vehicle->number_plate ?? null,
                        'job_status'     => $sjp->serviceJob->status,
                        'status'     => $sjp->status,
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'children' => $children,
        ]);
    }

    public function getServiceJobPassengerTracks(Request $request)
    {
        $request->validate([
            'service_job_id' => 'required|exists:service_jobs,id',
        ]);

        $serviceJob = ServiceJob::with([
            // Job basic info
            'driver:id,name',
            'vehicle:id,number_plate',

            // Job Track
            'jobTrack',

            // Trip Track
            'tripTrack',

            // Passenger Tracks with deep relations
            'passengerTracks.passenger.passenger.user'
        ])->findOrFail($request->service_job_id);

       

        return response()->json([
            'status' => true,
            'data' => [
                'service_job' => [
                    'id' => $serviceJob->id,
                    'status' => $serviceJob->status,
                    'job_date' => $serviceJob->job_date,
                ],

                'driver' => $serviceJob->driver,
                'vehicle' => $serviceJob->vehicle,

                'job_track' => $serviceJob->jobTrack,
                'trip_track' => $serviceJob->tripTrack,

                'passenger_tracks' => $serviceJob->passengerTracks->map(function ($track) {
                    return [
                        'track_id' => $track->id,
                        'status' => $track->status,
                        'pickup_trip_one' => $track->pickup_trip_one,
                        'dropoff_trip_one' => $track->dropoff_trip_one,
                        'pickup_trip_two' => $track->pickup_trip_two,
                        'dropoff_trip_two' => $track->dropoff_trip_two,

                        'passenger' => [
                            'id' => $track->passenger->passenger->id ?? null,
                            'name' => $track->passenger->passenger->name ?? null,
                        ],

                        'customer' => [
                            'id' => $track->passenger->passenger->user->id ?? null,
                            'name' => $track->passenger->passenger->user->name ?? null,
                        ],
                    ];
                }),
            ]
        ]);
    }
}
