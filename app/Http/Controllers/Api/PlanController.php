<?php

namespace App\Http\Controllers\Api;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with(['areaFrom', 'areaTo'])->get();
        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }
}
