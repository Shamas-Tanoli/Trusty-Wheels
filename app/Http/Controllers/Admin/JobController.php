<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\ServiceJob;
use App\Models\ServiceJobPassenger;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{

    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'driver_id'     => 'required|exists:users,id',
            'vehicle_id'    => 'required|exists:service_vehicles,id',
            'date'          => 'required|date',
            'passenger_ids' => 'required|array',
            'passenger_ids.*' => 'exists:booking_passengers,id|distinct',
        ]);

        DB::transaction(function () use ($validatedData) {


            $job = ServiceJob::create([
                'driver_id'  => $validatedData['driver_id'],
                'vehicle_id' => $validatedData['vehicle_id'],
                'status'     => 'active',
                'job_date'   => $validatedData['date'],
                'service_time_id' => 1
            ]);


            foreach ($validatedData['passenger_ids'] as $pid) {
                ServiceJobPassenger::create([
                    'service_job_id' => $job->id,
                    'passenger_id'   => $pid,
                    'status'         => 'active',
                ]);
            }


            $job->load([
                'driver',
                'vehicle',
                'passengers.passenger.user'
            ]);

            $job->load([
                'driver',
                'vehicle',
                'passengers.passenger.user',
                'servicetime',

            ]);





            $driverUser = Driver::find($validatedData['driver_id']);
          
            $driverToken = $driverUser->user?->fcm_token;

            if ($driverToken) {
                $this->firebase->sendToToken(
                    $driverToken,
                    'New Job Assigned',
                    'A new service job has been assigned to you',
                    [
                        'job' => json_encode($job),
                        'type' => 'JOB_ASSIGNED'

                    ]
                );
            }



            DB::table('notifications')->insert([
                'user_id' => $driverToken = $driverUser->user->id,
                'title'   => 'New Job Assigned',
                'body'    => 'A new service job has been assigned to you',
                'data'    => json_encode(['job' => $job]),
                'type'    => 'JOB_ASSIGNED',
                'user_type' => 'driver',
            ]);




            foreach ($job->passengers as $jobPassenger) {

                $user  = $jobPassenger->passenger->user ?? null;
                $token = $user?->fcm_token;

                if (!$token) {
                    continue;
                }

                $this->firebase->sendToToken(
                    $token,
                    'Booking Confirmed',
                    'your booking has done',
                    [
                        'job_id'    => $job->id,
                        'date'      => $job->job_date,
                        'driver'    => $job->driver->name ?? null,
                        'vehicle'   => $job->vehicle->registration_no ?? null,
                        'passenger' => json_encode([
                            'id'   => $jobPassenger->passenger->id,
                            'name' => $jobPassenger->passenger->name,
                        ])
                    ]
                );


                DB::table('notifications')->insert([
                    'user_id' => $user?->id,
                    'title'   => 'Booking Confirmed',
                    'body'    => 'your booking has done',
                    'data'    => json_encode(['job' => $job]),
                    'type'    => 'JOB_ASSIGNED',
                    'user_type' => 'customer',
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Job created & notifications sent to driver and passengers successfully!',
        ]);
    }




    public function list()
    {
        return view('admin.content.pages.job.list');
    }


    public function create()
    {
        return view('admin.content.pages.job.add');
    }
}
