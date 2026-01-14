<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ServiceJobPassenger;
use App\Http\Controllers\Controller;

class ServiceJobPassangerController extends Controller
{
    public function getChildrenWithJobs(Request $request)
    {
        $customer = $request->user(); // current logged-in customer

        // Fetch all passengers of this customer who are in any service job
        $children = ServiceJobPassenger::whereHas('passenger', function($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })
        ->with([
            'passenger.booking',
            'passenger.plan',
            'serviceJob.driver',
            'serviceJob.vehicle',
        ])
        ->get()
        ->map(function($sjp) {
            return [
                'passenger_id'   => $sjp->passenger->id,
                'name'           => $sjp->passenger->name,
                'pickup_time'    => $sjp->passenger->pickup_time,
                'dropoff_time'   => $sjp->passenger->dropoff_time,
                'pickup_location'=> $sjp->passenger->pickup_location,
                'dropoff_location'=> $sjp->passenger->dropoff_location,
                'service_job'    => [
                    'id'         => $sjp->serviceJob->id,
                    'date'       => $sjp->serviceJob->job_date,
                    'driver'     => $sjp->serviceJob->driver->name ?? null,
                    'vehicle'    => $sjp->serviceJob->vehicle->number_plate ?? null,
                    'status'     => $sjp->serviceJob->status,
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'children' => $children,
        ]);
    }
}
