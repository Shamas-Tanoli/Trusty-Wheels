<?php

namespace App\Http\Controllers\Admin;

use App\Models\Town;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class TownController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return view('admin.content.pages.town.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        
         $validatedData = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255|unique:towns,name',
        ]);

      


        Town::create($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Town added successfully',
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
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255|unique:towns,name,'.$id,
        ]);

        $town = Town::findOrFail($id);
        $town->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Town updated successfully',
        ], 200);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $location = Town::findOrFail($id);
        $location->delete();
        return response()->json([
            'success' => true,
            'message' => 'Town deleted successfully.'
        ], 200);
    }

    public function list(Request $request){
         if ($request->ajax()) {
            $query = Town::with('city')->select('id', 'name', 'city_id', 'created_at')
                ->orderByDesc('created_at');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y h:i A'); // Format: 15-02-2025 08:27 PM
                })
                ->addColumn('city_name', function ($row) {
                    return $row->city ? $row->city->name : 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    return '<button class="edit-btn btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1" data-name="' . $row->name . '" data-id="' . $row->id . '" 
                    data-make-name="' . $row->city->name . '" data-make-id="' . $row->city_id . '"  data-bs-target="#editPermissionModal" data-bs-toggle="modal" data-bs-dismiss="modal"><i class="ti ti-edit ti-md"></i></button>
                
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


     public function gettowns(Request $request)
    {
        return response()->json(
            Town::select('id', 'name')
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
