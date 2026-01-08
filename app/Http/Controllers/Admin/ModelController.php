<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ModelController extends Controller
{

    public function index()
    {

        return view('admin.content.pages.model.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'make_id' => 'required|exists:makes,id',
            'name' => 'required|string|max:255|unique:vehicle_models,name',
        ]);
        VehicleModel::create($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Model added successfully',
        ], 201);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = VehicleModel::with('make')->select('id', 'name', 'make_id', 'created_at')
                ->orderByDesc('created_at');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y h:i A'); // Format: 15-02-2025 08:27 PM
                })
                ->addColumn('make_name', function ($row) {
                    return $row->make ? $row->make->name : 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    return '<button class="edit-btn btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1" data-name="' . $row->name . '" data-id="' . $row->id . '" 
                    data-make-name="' . $row->make->name . '" data-make-id="' . $row->make_id . '"  data-bs-target="#editPermissionModal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="ti ti-edit ti-md"></i></button>
                
                    <button  class="delete-confirm btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1" data-id="' . $row->id . '" >
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
            'make_id' => 'required|exists:makes,id',
            'name' => 'required|string|max:255|unique:vehicle_models,name',
        ]);

        $location = VehicleModel::findOrFail($id);
        $location->update($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Model updated successfully'
        ], 200);
    }

    public function destroy(int $id)
    {
        $location = VehicleModel::findOrFail($id);
        $location->delete();
        return response()->json([
            'success' => true,
            'message' => 'Model deleted successfully.'
        ], 200);
    }

    public function geModel(Request $request)
    {
        return response()->json(
            VehicleModel::select('id', 'name')
                ->when(
                    $request->filled('search'),
                    fn($query) =>
                    $query->where('name', 'like', "%{$request->search}%")
                )
                ->limit(10)
                ->get()
        );
    }

    public function ModelByMake(Request $request)
    {
        return response()->json(
            VehicleModel::select('id', 'name')
                ->when($request->filled('id'), function ($query) use ($request) {
                    $query->where('make_id', $request->id);
                })
                ->when($request->filled('search'), function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                })
                ->limit(10)
                ->get()
        );
    }
}
