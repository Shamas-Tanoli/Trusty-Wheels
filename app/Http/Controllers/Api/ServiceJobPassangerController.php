<?php

namespace App\Http\Controllers\Api;

use App\Models\ServiceJob;
use Illuminate\Http\Request;
use App\Models\ServiceJobTrack;
use App\Services\FirebaseService;
use App\Models\ServiceJobPassenger;
use App\Http\Controllers\Controller;
use App\Models\ServiceJobPassengerTrack;

class ServiceJobPassangerController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

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
            'driver:id,name',
            'vehicle:id,number_plate',
            'jobTrack',
            'tripTrack',
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

 public function updatePickupTripOne(Request $request)
{
    
    $request->validate([
        'service_job_id' => 'required|exists:service_jobs,id',
        'passenger_id'   => 'required|exists:booking_passengers,id',
        'status'         => 'required|in:picked,pending',
    ]);

    try {
       
        $serviceJobPassenger = ServiceJobPassenger::where('service_job_id', $request->service_job_id)
            ->where('passenger_id', $request->passenger_id)
            ->first();

        if (!$serviceJobPassenger) {
            return response()->json([
                'status' => false,
                'message' => 'Passenger is not assigned to this service job.'
            ], 404);
        }

        
        $passengerTrack = ServiceJobPassengerTrack::where(
            'service_job_passengers_id',
            $serviceJobPassenger->id
        )->first();

        if (!$passengerTrack) {
            return response()->json([
                'status' => false,
                'message' => 'Passenger track not found.'
            ], 404);
        }

       
        $passengerTrack->pickup_trip_one = $request->status;
        $passengerTrack->save();

        
        return response()->json([
            'status' => true,
            'message' => 'Pickup status updated successfully.',
            'data' => [
                'service_job_id' => $request->service_job_id,
                'passenger_id'   => $request->passenger_id,
                'status'         => $request->status,
            ]
        ]);

    } catch (\Exception $e) {
     
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong while updating pickup status.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function updateDropoffTripOne(Request $request)
{
    
    $request->validate([
        'service_job_id' => 'required|exists:service_jobs,id',
        'passenger_id'   => 'required|exists:booking_passengers,id',
        'status'         => 'required|in:picked,pending',
    ]);

    try {
       
        $serviceJobPassenger = ServiceJobPassenger::where('service_job_id', $request->service_job_id)
            ->where('passenger_id', $request->passenger_id)
            ->first();

        if (!$serviceJobPassenger) {
            return response()->json([
                'status' => false,
                'message' => 'Passenger is not assigned to this service job.'
            ], 404);
        }

        
        $passengerTrack = ServiceJobPassengerTrack::where(
            'service_job_passengers_id',
            $serviceJobPassenger->id
        )->first();

        if (!$passengerTrack) {
            return response()->json([
                'status' => false,
                'message' => 'Passenger track not found.'
            ], 404);
        }

       
        $passengerTrack->dropoff_trip_one = $request->status;
        $passengerTrack->save();

        
        return response()->json([
            'status' => true,
            'message' => 'Pickup status updated successfully.',
            'data' => [
                'service_job_id' => $request->service_job_id,
                'passenger_id'   => $request->passenger_id,
                'status'         => $request->status,
            ]
        ]);

    } catch (\Exception $e) {
     
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong while updating pickup status.',
            'error' => $e->getMessage()
        ], 500);
    }
}



    

    public function updatePickupTripTwo(Request $request)
    {
        $request->validate([
            'track_id' => 'required|exists:service_job_passenger_track,id',
            'status'   => 'required|in:picked,pending'
        ]);

        $track = ServiceJobPassengerTrack::findOrFail($request->track_id);
        $track->pickup_trip_two = $request->status;
        $track->save();

        return response()->json([
            'status' => true,
            'data' => [
                'track_id' => $track->id,
                'pickup_trip_two' => $track->pickup_trip_two,
            ],
            'message' => 'Pickup status updated successfully.',
        ]);
    }
    public function updateDropoffTripTwo(Request $request)
    {
        $request->validate([
            'track_id' => 'required|exists:service_job_passenger_track,id',
            'status'   => 'required|in:picked,pending',
        ]);

        $track = ServiceJobPassengerTrack::findOrFail($request->track_id);
        $track->dropoff_trip_two = $request->status;
        $track->save();

        return response()->json([
            'status' => true,
            'data' => [
                'track_id' => $track->id,
                'dropoff_trip_two' => $track->dropoff_trip_two,
            ],
            'message' => 'Dropoff status updated successfully.',
        ]);
    }
}
