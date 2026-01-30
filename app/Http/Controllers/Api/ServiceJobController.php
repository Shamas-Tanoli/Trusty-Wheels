<?php

namespace App\Http\Controllers\Api;

use App\Models\ServiceJob;
use Illuminate\Http\Request;
use App\Models\ServiceJobTrack;
use App\Models\BookingPassenger;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceJobTripTrack;
use App\Http\Controllers\Controller;
use App\Models\ServiceJobPassengerTrack;
use Illuminate\Foundation\Exceptions\Renderer\Exception;

class ServiceJobController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function tripOne(Request $request)
    {

        $request->validate([
            'service_job_id' => 'required|exists:service_jobs,id',
            'status'         => 'required|in:completed,pending,ongoing',
        ]);



        $serviceJob = ServiceJob::with('servicetime.service')->find($request->service_job_id);
        if (!$serviceJob) {
            return response()->json(['status' => false, 'message' => 'Service job not found'], 404);
        }


        $serviceJobTrack = ServiceJobTrack::where('service_job_id', $request->service_job_id)->first();
        if (!$serviceJobTrack) {
            return response()->json(['status' => 'error', 'message' => 'Service job track not found'], 404);
        }

        $jobPassengers = ServiceJobPassengerTrack::where('service_job_track_id', $serviceJobTrack->id)
            ->where('status', 'active')
            ->get();


        $allPickedAndDropped = $jobPassengers->every(function ($passenger) {
            return $passenger->pickup_trip_one === 'picked' && $passenger->dropoff_trip_one === 'dropped';
        });





        $serviceJobTripTrack = ServiceJobTripTrack::where('service_job_track_id', $serviceJobTrack->id)->first();
        if (!$serviceJobTripTrack) {
            return response()->json(['status' => 'error', 'message' => 'Service job trip track not found'], 404);
        }


        try {

            if ($allPickedAndDropped) {
                $serviceJobTripTrack->trip_one_status = $request->status;
                $serviceJobTripTrack->save();

                $service = $serviceJob->servicetime->service;

                if ($service->name === 'single trip') {
                    $serviceJob->status = 'completed';
                    $serviceJob->save();
                }
            }
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating trip one status.',
                'error' => $e->getMessage()
            ], 500);
        }



        return response()->json([
            'status' => true,
            'message' => $allPickedAndDropped ? 'Trip one status updated' : 'Not all passengers completed trip one',
            'trip_one_status' => $serviceJobTripTrack->trip_one_status,
            'service_job_status' => $serviceJob->status,
        ]);
    }


    public function tripTwo(Request $request)
    {
        $request->validate([
            'service_job_id' => 'required|exists:service_jobs,id',
            'status'         => 'required|in:completed,pending,ongoing',
        ]);

        $serviceJob = ServiceJob::with('servicetime.service')->find($request->service_job_id);
        if (!$serviceJob) {
            return response()->json(['status' => false, 'message' => 'Service job not found'], 404);
        }


        $serviceJobTrack = ServiceJobTrack::where('service_job_id', $request->service_job_id)->first();
        if (!$serviceJobTrack) {
            return response()->json(['status' => 'error', 'message' => 'Service job track not found'], 404);
        }



        $jobPassengers = ServiceJobPassengerTrack::where('service_job_track_id', $serviceJobTrack->id)
            ->where('status', 'active')
            ->get();


        $allPickedAndDropped = $jobPassengers->every(function ($passenger) {
            return $passenger->pickup_trip_two === 'picked' && $passenger->dropoff_trip_two === 'dropped';
        });



        $serviceJobTripTrack = ServiceJobTripTrack::where('service_job_track_id', $serviceJobTrack->id)->first();
        if (!$serviceJobTripTrack) {
            return response()->json(['status' => 'error', 'message' => 'Service job trip track not found'], 404);
        }

        try {
            if ($allPickedAndDropped) {
                $serviceJobTripTrack->trip_two_status = $request->status;
                $serviceJobTripTrack->save();

                $service = $serviceJob->servicetime->service;
                $isMultiTrip = $service->name !== 'single trip';
                
                    if ($isMultiTrip && $serviceJobTripTrack->trip_two_status === 'completed') {
                        $serviceJob->status = 'completed';
                        $serviceJob->save();
                    }
            }
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating trip one status.',
                'error' => $e->getMessage()
            ], 500);
        }



        return response()->json([
            'status' => true,
            'message' => $allPickedAndDropped ? 'Trip two status updated' : 'Not all passengers completed trip two',
            'trip_two_status' => $serviceJobTripTrack->trip_two_status,
            'service_job_status' => $serviceJob->status,
        ]);
    }



    public function createJobTracking(Request $request)
    {
        $request->validate([
            'service_job_id' => 'required|exists:service_jobs,id',
        ]);


        $alreadyExists = ServiceJobTrack::where('service_job_id', $request->service_job_id)
            ->where('status', 'ongoing')
            ->exists();

        if ($alreadyExists) {
            return response()->json([
                'status' => false,
                'message' => 'Job tracking already started for this service job',
            ], 409);
        }

        $serviceJob = ServiceJob::with([
            'passengers.passenger.user',
            'driver',
            'vehicle'
        ])->findOrFail($request->service_job_id);

        DB::transaction(function () use ($serviceJob) {


            $jobTrack = ServiceJobTrack::create([
                'service_job_id' => $serviceJob->id,
                'status' => 'ongoing',
            ]);


            ServiceJobTripTrack::create([
                'service_job_track_id' => $jobTrack->id,
                'driver_id' => $serviceJob->driver_id,
                'vehicle_id' => $serviceJob->vehicle_id,
                'trip_one_status' => 'ongoing',
                // 'trip_two_status' => 'pending',

            ]);


            foreach ($serviceJob->passengers as $passenger) {
                ServiceJobPassengerTrack::create([
                    'service_job_track_id' => $jobTrack->id,
                    'service_job_passengers_id' => $passenger->id,
                    'status' => $passenger->status,
                    'pickup_trip_one' => 'pending',
                    'dropoff_trip_one' => 'pending',
                    'pickup_trip_two' => 'pending',
                    'dropoff_trip_two' => 'pending',
                ]);
            }
        });


        $notifiedCustomerIds = [];

        foreach ($serviceJob->passengers as $jobPassenger) {

            $customer = $jobPassenger->passenger->customer ?? null;

            if (!$customer) {
                continue;
            }


            if (in_array($customer->id, $notifiedCustomerIds)) {
                continue;
            }

            $notifiedCustomerIds[] = $customer->id;

            $user  = $customer->user ?? null;
            $token = $user?->fcm_token;

            if (!$token) {
                continue;
            }

            $this->firebase->sendToToken(
                $token,
                'Dear Passenger',
                "Your driver has started the route for today. Please be ready at your pickup point.\n\nThank you for choosing our service.",
                [
                    'service_job_id' => $serviceJob->id,
                    'status'   => 'ongoing',
                    'driver'   => $serviceJob->driver->name ?? null,
                    'vehicle'  => $serviceJob->vehicle->registration_no ?? null,
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Job started & customers notified successfully',
        ]);
    }
}
