<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ServiceTime;

class ServiceController extends Controller
{
     public function index(){
        $services = Service::get();
     
        return response()->json([
            'success' => true,
            'data' => $services
        ]);
     }

     public function serviceTimeByService($id)
    {
        $service = Service::with(['serviceTimes'])->find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        return response()->json([
             'success' => true,
            'service' => [
                'id' => $service->id,
                'name' => $service->name,
                'servicetime' => $service->serviceTimes->map(function ($time) {
                    return [
                        'id' => $time->id,
                        'timing' => $time->timing
                    ];
                })
            ]
        ]);
    }
    public function plansByServiceTime($id)
{
    $serviceTime = ServiceTime::with(['plans'])->find($id);

    if (!$serviceTime) {
        return response()->json([
            'success' => false,
            'message' => 'Service Time not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'service_time' => [
            'id' => $serviceTime->id,
            'timing' => $serviceTime->timing,
            'plans' => $serviceTime->plans->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'price' => $plan->price,
                    'area_from_id' => $plan->area_from_id,
                    'area_from_name' => $plan->areaFrom->name,
                    'area_to_id' => $plan->area_to_id,
                    'area_to_name' => $plan->areaTo->name,
                    'status' => $plan->status,
                ];
            })
        ]
    ]);
}
}
