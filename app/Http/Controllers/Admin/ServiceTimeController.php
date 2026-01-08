<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceTime;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ServiceTimeController extends Controller
{
    public function index()
    {
        $services = Service::select('id', 'name')->get();
        return view('admin.content.pages.service_time.index', compact('services'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,id',
            'timing'     => 'required|string|max:255|unique:service_times,timing,NULL,id,service_id,' . $request->service_id,
        ]);

        ServiceTime::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Service time added successfully',
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,id',
            'timing'     => 'required|string|max:255|unique:service_times,timing,' . $id . ',id,service_id,' . $request->service_id,
        ]);

        $serviceTime = ServiceTime::findOrFail($id);
        $serviceTime->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Service time updated successfully',
        ], 200);
    }

    public function destroy(string $id)
    {
        $serviceTime = ServiceTime::findOrFail($id);
        $serviceTime->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service time deleted successfully.',
        ], 200);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {

            $query = ServiceTime::with('service')
                ->select('id', 'service_id', 'timing', 'created_at')
                ->orderByDesc('created_at');

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('service_name', function ($row) {
                    return $row->service ? $row->service->name : 'N/A';
                })

                ->addColumn('service_time', function ($row) {
                    return $row->timing;
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y h:i A');
                })

                ->addColumn('actions', function ($row) {
                    return '
                <button class="edit-btn btn btn-icon btn-text-secondary rounded-pill"
                    data-id="' . $row->id . '"
                    data-make-id="' . $row->service_id . '"
                    data-make-name="' . $row->service->name . '"
                    data-name="' . $row->timing . '"
                    data-bs-toggle="modal"
                    data-bs-target="#editPermissionModal">
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

    public function getServiceTimes(Request $request)
    {


        return response()->json(
            ServiceTime::select('id', 'timing as name')
                ->when(
                    $request->filled('search'),
                    fn($query) =>
                    $query->where('timing', 'like', "%{$request->search}%")
                )
                ->limit(10)
                ->get()
        );
    }
}
