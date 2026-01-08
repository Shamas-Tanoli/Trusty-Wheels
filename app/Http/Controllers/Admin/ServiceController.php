<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.content.pages.service.index');
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:services,name',
        ]);

        Service::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Service added successfully',
        ], 201);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, string $id)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $id,
        ]);



        $service = Service::findOrFail($id);
        $service->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully.',
        ], 200);
    }

    /**
     * Datatable list
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = Service::select('id', 'name', 'created_at')
                ->orderByDesc('created_at');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y h:i A');
                })
                ->addColumn('actions', function ($row) {
                    return '
                    <button class="edit-btn btn btn-icon btn-text-secondary rounded-pill"
                        data-id="'.$row->id.'"
                        data-name="'.$row->name.'"
                        data-bs-toggle="modal"
                        data-bs-target="#editPermissionModal">
                        <i class="ti ti-edit ti-md"></i>
                    </button>

                    <button class="delete-confirm btn btn-icon btn-text-secondary rounded-pill"
                        data-id="'.$row->id.'">
                        <i class="ti ti-trash ti-md"></i>
                    </button>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }


     public function getservice(Request $request)
    {
        return response()->json(
            Service::select('id', 'name')
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
