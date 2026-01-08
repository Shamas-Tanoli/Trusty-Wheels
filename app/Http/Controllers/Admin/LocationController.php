<?php

namespace App\Http\Controllers\Admin;


use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends Controller
{
    public function index()
    {

        return view('admin.content.pages.location.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
        ]);

        Location::create($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Location added successfully',
        ], 201);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = Location::select(['id', 'name', 'created_at'])->orderByDesc('created_at');

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
            'name' => 'required|string|max:255|unique:locations,name',
        ]);

        $location = Location::findOrFail($id);
        $location->update($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully'
        ], 200);
    }

    public function destroy(int $id)
    {
        $location = Location::findOrFail($id);
        $location->delete();
        return response()->json([
            'success' => true,
            'message' => 'Location deleted successfully.'
        ], 200);
    }

    public function getLocation(Request $request)
    {
        return response()->json(
            Location::select('id', 'name')
                ->when(
                    $request->filled('search'),
                    fn($query) =>
                    $query->where('name', 'like', "%{$request->search}%")
                )
                ->limit(10)
                ->get()
        );
    }
}
