<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AmenityController extends Controller
{
    public function index()
    {

        return view('admin.content.pages.amenity.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:amenities,name',
        ]);

         Amenity::create($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Amenity added successfully',
        ], 201);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = Amenity::select(['id', 'name', 'created_at'])->orderByDesc('created_at');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y h:i A'); // Format: 15-02-2025 08:27 PM
                })
                ->addColumn('actions', function ($row) {
                    return '<button class="edit-btn btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1" data-name="'.$row->name.'"  data-id="' . $row->id . '" data-bs-target="#editPermissionModal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="ti ti-edit ti-md"></i></button>
                
                <button  class=" delete-confirm btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1" data-id="' . $row->id . '" >
                <i class="ti ti-trash ti-md"></i>
                </button>
                
                       ';
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

    public function update(Request $request,int $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:amenities,name',
        ]);
        
        $location = Amenity::findOrFail($id);
        $location->update($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Amenity updated successfully'
        ], 200);

    }

    public function destroy(int $id)
    {
        $location = Amenity::findOrFail($id);
        $location->delete();
        return response()->json([
            'success' => true,
            'message' => 'Amenity deleted successfully.'
        ], 200);
    }
}
