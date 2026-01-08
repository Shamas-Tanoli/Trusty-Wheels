<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.content.pages.city.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
         if ($request->ajax()) {
            $query = City::select(['id', 'name', 'created_at'])->orderByDesc('created_at');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y h:i A'); // Format: 15-02-2025 08:27 PM
                })
                ->addColumn('actions', function ($row) {
                    return '<button class="edit-btn btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1" data-name="' . $row->name . '"  data-id="' . $row->id . '" data-bs-target="#editPermissionModal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="ti ti-edit ti-md"></i></button>
                
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        City::create($request->only('name'));

        return response()->json([
            'success' => true,
            'message' => 'City added successfully',
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      
         $validatedData = $request->validate([
            // 'name' => 'required|string|max:255|unique:cities,name,' . $id,
            'name' => 'required',
        ]);

        $location = City::findOrFail($id);
        $location->update($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'City updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $location = City::findOrFail($id);
        $location->delete();
        return response()->json([
            'success' => true,
            'message' => 'City deleted successfully.'
        ], 200);
    }

     public function getcities(Request $request)
    {
        return response()->json(
            City::select('id', 'name')
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
