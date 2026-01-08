<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ServiceVehicle;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ServiceVehicleController extends Model
{
 public function updatee(Request $request, $id)
{
    $vehicle = ServiceVehicle::find($id);

    if (!$vehicle) {
        return response()->json([
            'success' => false,
            'message' => 'Vehicle not found!'
        ], 404);
    }

    // Validation
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'number_plate' => 'required|string|max:50|unique:service_vehicles,number_plate,' . $vehicle->id,
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Image upload
    if ($request->hasFile('image')) {
        if ($vehicle->image && file_exists(public_path($vehicle->image))) {
            unlink(public_path($vehicle->image));
        }

        $folderPath = public_path('assets/img/servicevehicle');
        
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $image = $request->file('image');
        $imageName = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move($folderPath, $imageName);
        $vehicle->image = 'assets/img/servicevehicle/' . $imageName;
    }

    // Update other fields
    $vehicle->name = $request->name;
    $vehicle->number_plate = $request->number_plate;
    $vehicle->save();

    return response()->json([
        'success' => true,
        'message' => 'Vehicle updated successfully!',
        'data' => $vehicle
    ]);
}




    public function destroys($id)
{
    $vehicle = ServiceVehicle::find($id);

    if (!$vehicle) {
        return response()->json([
            'success' => false,
            'message' => 'Vehicle not found!'
        ], 404);
    }

    // Agar image exist karti hai to delete kar do
    if ($vehicle->image && file_exists(public_path($vehicle->image))) {
        unlink(public_path($vehicle->image));
    }

    $vehicle->delete();

    return response()->json([
        'success' => true,
        'message' => 'Vehicle deleted successfully!'
    ]);
}



    public function list(Request $request)
    {
        if ($request->ajax()) {

            $query = ServiceVehicle::select('id', 'name', 'number_plate', 'image', 'created_at')
                ->orderByDesc('created_at');

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('vehicle_name', function ($row) {
                    $img = $row->image ? '<img src="' . asset($row->image) . '" alt="vehicle" style="width:40px; height:40px; object-fit:cover; border-radius:50%; margin-right:5px;">' : '';
                    return $img . $row->name;
                })

                ->addColumn('number_plate', function ($row) {
                    return $row->number_plate;
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y h:i A');
                })

                ->addColumn('actions', function ($row) {
                    return '
                    <button class="edit-btn btn btn-icon btn-text-secondary rounded-pill"
                        data-id="' . $row->id . '"
                        data-name="' . $row->name . '"
                        data-number_plate="' . $row->number_plate . '"
                        data-image="' . ($row->image ? asset($row->image) : '') . '"
                        data-bs-toggle="modal"
                        data-bs-target="#editVehicleModal">
                        <i class="ti ti-edit ti-md"></i>
                    </button>

                    <button class="delete-confirm btn btn-icon btn-text-secondary rounded-pill"
                        data-id="' . $row->id . '">
                        <i class="ti ti-trash ti-md"></i>
                    </button>';
                })

                ->rawColumns(['vehicle_name', 'actions'])
                ->make(true);
        }
    }

    
   public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'number_plate' => 'required|string|max:50|unique:service_vehicles,number_plate',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // 2. Image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Folder path
            $folderPath = public_path('assets/img/servicevehicle');

            // Agar folder exist nahi karta to create karo
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }

            // Image name
            $imageName = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();

            // Move image
            $image->move($folderPath, $imageName);

            // Path database me save karne ke liye
            $imagePath = 'assets/img/servicevehicle/' . $imageName;
        }

        // 3. Create new Service Vehicle
        $vehicle = ServiceVehicle::create([
            'name' => $request->name,
            'number_plate' => $request->number_plate,
            'image' => $imagePath,
        ]);

        // 4. Return response
        return response()->json([
           'success' => true,
            'message' => 'Service Vehicle added successfully.',
            'data' => $vehicle
        ]);
    }
    public function index()
    {
       return view('admin.content.pages.service-vehicle.index');
    }
}
