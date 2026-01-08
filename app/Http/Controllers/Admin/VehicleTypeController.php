<?php

namespace App\Http\Controllers\Admin;

use App\Models\VehicleType;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VehicleTypeController extends Controller
{
    public function index()
    {
        return view('admin.content.pages.vehicaltype.index');
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_types,name',
        ]);

        $location = VehicleType::create($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Vehicle Type added successfully',
        ], 201);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = VehicleType::select(['id', 'name', 'created_at'])->orderByDesc('created_at');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y h:i A'); // Format: 15-02-2025 08:27 PM
                })
                ->addColumn('actions', function ($row) {
                    return '<button class="edit-btn btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1" data-name="' . $row->name . '"  data-id="' . $row->id . '" data-bs-target="#editPermissionModal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="ti ti-edit ti-md"></i></button>
                
                <button  class=" delete-confirm btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1" data-id="' . $row->id . '" >
                <i class="ti ti-trash ti-md"></i>
                </button>';
                })
                ->filter(function ($query) use ($request) {
                    if ($search = $request->input('search.value')) {
                        $query->where('name', 'like', "%{$search}%");
                    }
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function update(Request $request, int $id)
    {



        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_types,name',
        ]);

        $location = VehicleType::findOrFail($id);
        $location->update($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Vehicle Type updated successfully'
        ], 200);
    }

    public function destroy(int $id)
    {
        $location = VehicleType::findOrFail($id);
        $location->delete();
        return response()->json([
            'success' => true,
            'message' => 'Vehicle Type deleted successfully.'
        ], 200);
    }

    public function getVehicleTypes(Request $request)
    {
        return response()->json(
            VehicleType::select('id', 'name')
                ->when($request->filled('search'),
                    fn($query) =>
                    $query->where('name', 'like', "%{$request->search}%")
                )
                ->limit(10)
                ->get()
        );
    }
}
