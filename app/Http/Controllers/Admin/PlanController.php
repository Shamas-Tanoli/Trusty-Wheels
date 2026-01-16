<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Validator;


class PlanController extends Controller
{
    public function update(Request $request, Plan $plan)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'price'           => 'required|numeric',
            'area_from_id'    => 'required|exists:towns,id',
            'area_to_id'      => 'required|exists:towns,id',
            'servicetime_id' => 'required|exists:service_times,id',
            'status'          => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $plan->update([
            'name'            => $request->name,
            'price'           => $request->price,
            'area_from_id'    => $request->area_from_id,
            'area_to_id'      => $request->area_to_id,
            'status'          => $request->status,
            'service_time_id' => $request->servicetime_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plan updated successfully',
        ]);
    }

    public function index()
    {

        return view('admin.content.pages.plan.index');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name'         => 'required|string|max:255',
            'price'        => 'required|numeric',
            'area_from_id' => 'required|exists:towns,id',
            'area_to_id'   => 'required|exists:towns,id',
            'servicetime_id'   => 'required|exists:service_times,id',

        ]);

        
        Plan::create([
            'name'         => $request->name,
            'price'        => $request->price,
            'area_from_id' => $request->area_from_id,
            'area_to_id'   => $request->area_to_id,
            'status'       => 'active',
            'service_time_id'       => $request->servicetime_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plan added successfully',
        ], 201);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {

            $query = Plan::with(['areaFrom', 'areaTo'])
                ->select('plans.*');

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('area_from', function ($row) {
                    return optional($row->areaFrom)->name ?? 'N/A';
                })

                ->addColumn('area_to', function ($row) {
                    return optional($row->areaTo)->name ?? 'N/A';
                })

                ->editColumn('status', function ($row) {
                    return ucfirst($row->status);
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y h:i A');
                })

                ->addColumn('actions', function ($row) {
                    return '
    <button class="edit-btn btn btn-icon btn-text-secondary rounded-pill"
        data-id="' . $row->id . '"
        data-name="' . $row->name . '"
        data-servicetimeid="' . $row->service_time_id . '"
        data-servicetimename="' . optional($row->serviceTime)->timing . '"
        data-price="' . $row->price . '"
        data-area-from-id="' . $row->area_from_id . '"
        data-area-from="' . optional($row->areaFrom)->name . '"
        data-area-to-id="' . $row->area_to_id . '"
        data-area-to="' . optional($row->areaTo)->name . '"
        data-status="' . $row->status . '"
        data-bs-toggle="modal"
        data-bs-target="#editPlanModal">
        <i class="ti ti-edit ti-md"></i>
    </button>


     <button class="delete-confirm btn btn-icon btn-text-secondary rounded-pill"
                        data-id="' . $row->id . '">
                        <i class="ti ti-trash ti-md"></i>
                    </button>';
                })


                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Plan deleted successfully.'
        ]);
    }
}
