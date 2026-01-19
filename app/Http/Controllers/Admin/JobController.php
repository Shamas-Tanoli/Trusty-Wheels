<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceJob;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceJobPassenger;
use App\Http\Controllers\Controller;

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
        ]);

        // 2️⃣ Attach Passengers
        foreach ($validatedData['passenger_ids'] as $pid) {
            ServiceJobPassenger::create([
                'service_job_id' => $job->id,
                'passenger_id'   => $pid,
                'status'         => 'active',
            ]);
        }

        // 3️⃣ Load relations
        $job->load([
            'driver',
            'vehicle',
            'passengers.passenger.user'
        ]);

        // ===============================
        // 🔔 DRIVER NOTIFICATION
        // ===============================
        $driverUser = $job->driver; // User model
        $driverToken = $driverUser?->fcm_token;

        if ($driverToken) {
            $this->firebase->sendToToken(
                $driverToken,
                'New Job Assigned',
                'A new service job has been assigned to you',
                [
                    'job_id'  => $job->id,
                    'date'    => $job->job_date,
                    'vehicle' => $job->vehicle->registration_no ?? null,
                ]
            );
        }

        // ===============================
        // 🔔 PASSENGERS NOTIFICATION
        // ===============================
        foreach ($job->passengers as $jobPassenger) {

            $user  = $jobPassenger->passenger->user ?? null;
            $token = $user?->fcm_token;

            if (!$token) {
                continue;
            }

            $this->firebase->sendToToken(
                $token,
                'New Job Assigned',
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
