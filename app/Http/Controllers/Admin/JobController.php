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
            'driver_id'        => 'required|exists:users,id',
            'vehicle_id'       => 'required|exists:service_vehicles,id',
            'date'             => 'required|date',
            'passenger_ids'    => 'required|array',
            'passenger_ids.*'  => 'exists:booking_passengers,id|distinct',
        ]);

        DB::transaction(function () use ($validatedData) {

            // create job
            $job = ServiceJob::create([
                'driver_id'  => $validatedData['driver_id'],
                'vehicle_id' => $validatedData['vehicle_id'],
                'status'     => 'active',
                'job_date'   => $validatedData['date'],
            ]);

            // add passengers
            foreach ($validatedData['passenger_ids'] as $pid) {
                ServiceJobPassenger::create([
                    'service_job_id' => $job->id,
                    'passenger_id'   => $pid,
                    'status'         => 'active',
                ]);
            }

            // ✅ load complete job with passengers
            $job->load([
                'driver',
                'vehicle',
                'passengers.passenger'
            ]);

            // 🔥 send notification here
            $token = $job->driver?->fcm_token;

            if ($token) {
                $this->firebase->sendToToken(
                    $token,
                    'New Job Assigned',
                    'You have a new service job',
                    [
                        'job_detail' => $job,
                        'job_id' => $job->id,
                        'date'   => $job->job_date,
                        'passengers' => $job->passengers->map(function ($p) {
                            return [
                                'id' => $p->passenger->id,
                                'name' => $p->passenger->name,
                            ];
                        }),
                    ]
                );
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Job created & notification sent successfully!',
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
