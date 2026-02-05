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

    public function updatePassengerTripStatus(Request $request)
{
    $request->validate([
        'service_job_id' => 'required|exists:service_jobs,id',
        'passenger_id'   => 'required|exists:booking_passengers,id',
        'trip'           => 'required|in:one,two',
        'type'           => 'required|in:pickup,dropoff',
        'status'         => 'required|in:pickup,dropoff',
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

        /**
         * Dynamic column name
         * pickup_trip_one
         * dropoff_trip_one
         * pickup_trip_two
         * dropoff_trip_two
         */
        $column = "{$request->type}_trip_{$request->trip}";

        // Status validation based on type
        if ($request->type === 'pickup' && !in_array($request->status, ['pickup', 'pending'])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid pickup status.'
            ], 422);
        }

        if ($request->type === 'dropoff' && !in_array($request->status, ['dropoff', 'pending'])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid dropoff status.'
            ], 422);
        }

        // Update dynamically
        $passengerTrack->$column = $request->status;
        $passengerTrack->save();

        return response()->json([
            'status'  => true,
            'message' => ucfirst($request->type) . " trip {$request->trip} updated successfully.",
            'data'    => [
                'service_job_id' => $request->service_job_id,
                'passenger_id'   => $request->passenger_id,
                'trip'           => $request->trip,
                'type'           => $request->type,
                'status'         => $request->status,
            ]
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage()
        ], 500);
    }
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
            'message' => 'Pickup trip one updated successfully.',
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
        'status'         => 'required|in:droped,pending',
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
            'message' => 'Pickup trip one updated successfully.',
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

       
        $passengerTrack->dropoff_trip_two = $request->status;
        $passengerTrack->save();

        
        return response()->json([
            'status' => true,
            'message' => 'Pickup trip two updated successfully.',
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
public function updateDropoffTripTwo(Request $request)
{
    
    $request->validate([
        'service_job_id' => 'required|exists:service_jobs,id',
        'passenger_id'   => 'required|exists:booking_passengers,id',
        'status'         => 'required|in:droped,pending',
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

       
       $passengerTrack->dropoff_trip_two = $request->status;
        $passengerTrack->save();

        
        return response()->json([
            'status' => true,
            'message' => 'Dropoff trip two status updated successfully.',
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



    

    
    
}
