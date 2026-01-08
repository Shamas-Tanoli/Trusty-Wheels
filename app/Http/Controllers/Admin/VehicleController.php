<?php

namespace App\Http\Controllers\Admin;

use App\Models\Amenity;
use App\Models\Vehicle;
use Illuminate\Support\Str;
use App\Models\VehicleImage;
use Illuminate\Http\Request;
use Intervention\Image\Image;

use App\Jobs\ProcessVehiclePic;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessVehiclePicUpdate;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class VehicleController extends Controller
{
    public function update(Request $request, int $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'vin_nbr' => 'required|string|max:255',
            'lic_plate_nbr' => 'required|string|max:255',
            'description' => 'required|string',
            'short_Description' => 'required|string',
            'rent' => 'required|numeric',
            'make_id' => 'required|exists:makes,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'location_id' => 'required|exists:locations,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'year' => 'required|numeric|digits:4|integer|min:1900|max:' . (date('Y')),
            'mileage' => 'required|integer|min:0',
            'transmission' => 'required|string|max:50',
            'fuel_type' => 'required|string|max:50',
            'door' => 'required|numeric|min:2',
            'seats' => 'required|numeric|min:2|max:7',
            'newImages' => 'nullable|array',
            'newImages.*' => 'mimes:jpg,jpeg,png,webp|max:10240',
            'amenities' => 'required|array',
            'status' => 'required',
            'amenities.*' => 'exists:amenities,id',
        ]);

        // Update vehicle
        $vehicle->update([
            'title' => $validated['title'],
            'vin_nbr' => $validated['vin_nbr'],
            'lic_plate_nbr' => $validated['lic_plate_nbr'],
            'description' => $validated['description'],
            'short_Description' => $validated['short_Description'],
            'rent' => $validated['rent'],
            'make_id' => $validated['make_id'],
            'vehicle_model_id' => $validated['vehicle_model_id'],
            'location_id' => $validated['location_id'],
            'vehicle_type_id' => $validated['vehicle_type_id'],
            'year' => $validated['year'],
            'mileage' => $validated['mileage'],
            'transmission' => $validated['transmission'],
            'fuel_type' => $validated['fuel_type'],
            'door' => $validated['door'],
            'seats' => $validated['seats'],
            'status' => $validated['status'],
        ]);

        $vehicle->vehicleAmenities()->sync($validated['amenities']);

        $vehicle = Vehicle::with('vehicleImages')->findOrFail($id);

        // 🔄 Sorted image order update
        $sortedImages = is_array($request->input('sortedImages'))
            ? $request->input('sortedImages')
            : json_decode($request->input('sortedImages'), true) ?? [];

        foreach ($sortedImages as $imageData) {
            if (!empty($imageData['id'])) {
                VehicleImage::where('id', $imageData['id'])->update(['image_order' => $imageData['order']]);
            }
        }

        // 🗑️ Delete removed images
        $existingImageIds = is_array($request->input('existingImages'))
            ? $request->input('existingImages')
            : json_decode($request->input('existingImages'), true) ?? [];

        $deletedImages = $vehicle->vehicleImages()->whereNotIn('id', $existingImageIds)->get();

        foreach ($deletedImages as $image) {
            $imagePath = public_path('assets/img/vehicle_images/' . $image->image_url);
            if (file_exists($imagePath)) unlink($imagePath);
            $image->delete();
        }

        // ✅ Get last order number
        $lastOrder = $vehicle->vehicleImages()->max('image_order') ?? 0;


        $tempFiles = [];
        if ($request->hasFile('newImages')) {
            foreach ($request->file('newImages') as $index => $file) {
                $tempPath = $file->store('temp');
                $tempFiles[] = $tempPath;
            }
        }

        // Job dispatch with data
        ProcessVehiclePicUpdate::dispatch($vehicle->id, $tempFiles, $sortedImages, $lastOrder);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle updated successfully.'
        ], 200);
    }


    public function create(int $id)
    {

        $amenities = Amenity::all();
        $vehicle = Vehicle::with(['vehicleImages', 'vehicleAmenities'])->findOrFail($id);

        $vehicleImages = $vehicle->vehicleImages->sortBy('image_order')->map(function ($image) {
            $imagePath = public_path('assets/img/vehicle_images/' . $image->image_url);
            return [
                'id' => $image->id,
                'url' => asset("assets/img/vehicle_images/{$image->image_url}"),
                'size' => file_exists($imagePath) ? filesize($imagePath) : 0,
                'image_order' => $image->image_order,
            ];
        });




        return view('admin.content.pages.vehicle.edit', compact('amenities', 'vehicle', 'vehicleImages'));
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $vehicles = Vehicle::with(['make', 'vehicleModel', 'location', 'vehicleImages' => function ($query) {
                $query->where('image_order', 1);
            }])->select('vehicles.*')->orderByDesc('created_at');

            return DataTables::of($vehicles)
                ->addIndexColumn()
                ->addColumn('name', function ($vehicle) {
                    $featuredImage = $vehicle->vehicleImages->where('image_order', 1)->first();
                    $imageUrl = $featuredImage ?
                        asset("assets/img/vehicle_images/" . $featuredImage->image_url) :
                        asset('assets/img/default-featured-img.jpeg');
                    return '<div class="d-flex justify-content-start align-items-center product-name"><div class="avatar-wrapper"><div class="avatar avatar me-4 rounded-2 vehicle-list-featurepic bg-label-secondary"><img src="' . $imageUrl . '" alt="' . $vehicle->title . '"class="rounded-2"></div></div><div class="d-flex flex-column"><h6 class="text-nowrap mb-0">' . ucfirst($vehicle->make->name) . '-' . ucfirst($vehicle->vehicleModel->name) . '</h6><small class="text-truncate d-none d-sm-block">' . Str::upper($vehicle->title) . '</small></div></div>';
                })
                ->addColumn('location', function ($vehicle) {
                    return ucfirst($vehicle->location->name);
                })
                ->addColumn('rent', function ($vehicle) {
                    return  ' £' . $vehicle->rent;
                })
                ->addColumn('status', function ($vehicle) {
                    $statusMap = [
                        'available'     => 'success',
                        'sold'          => 'danger',
                        'arriving_soon' => 'warning',
                    ];

                    $badgeClass = $statusMap[$vehicle->status] ?? 'secondary';

                    return '<span class="badge px-2 bg-label-' . $badgeClass . '">' . ucfirst(str_replace('_', ' ', $vehicle->status)) . '</span>';
                })

                ->addColumn('action', function ($vehicle) {
                    return '<a href="' . route('Listings.vehicle.create', ['id' => $vehicle->id]) . '" class="edit-btn btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1" ><i class="ti ti-edit ti-md"></i></a>
                
                <button data-id="' . $vehicle->id . '"  class=" delete-confirm btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1"  >
                <i class="ti ti-trash ti-md"></i>
                </button>';
                })
                ->filter(function ($vehicle) use ($request) {
                    if ($search = $request->input('search.value')) {
                        $vehicle->where('title', 'like', "%{$search}%");
                    }
                })
                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }

        return view('admin.content.pages.vehicle.list');
    }

    public function add()
    {
        $amenities = Amenity::all();
        return view('admin.content.pages.vehicle.add', compact('amenities'));
    }


    public function store(Request $request)
    {
        // Step 1: Validate input data
        $validated = $request->validate(
            [
                'title' => 'required|string|max:255',
                'vin_nbr' => 'required|string|max:255',
                'lic_plate_nbr' => 'required|string|max:255',
                'description' => 'required|string',
                'short_Description' => 'required|string',

                'rent' => 'required|numeric',
                'make_id' => 'required|exists:makes,id',
                'vehicle_model_id' => 'required|exists:vehicle_models,id',
                'location_id' => 'required|exists:locations,id',
                'vehicle_type_id' => 'required|exists:vehicle_types,id',
                'year' => 'required|numeric|digits:4|integer|min:1900|max:' . (date('Y')),
                'mileage' => 'required|integer|min:0',
                'transmission' => 'required|string|max:50',
                'fuel_type' => 'required|string|max:50',
                'door' => 'required|numeric|min:2',
                'status' => 'required',
                'seats' => 'required|numeric|min:2|max:7',
                'files' => 'required|array|min:1',
                'files.*' => 'mimes:jpg,jpeg,png,webp|max:10240',
                'amenities' => 'required|array',
                'amenities.*' => 'exists:amenities,id',
            ],
            [
                'title.required' => 'The title field is required.',
                'vin_nbr.required' => 'The VIN number is required.',
                'lic_plate_nbr.required' => 'Please provide the vehicle license plate number.',
                'description.required' => 'A description of the vehicle is required.',
                'rent_type.required' => 'Please specify the rent type (e.g., daily, monthly).',
                'rent.required' => 'The rent amount is required and must be a number.',
                'make_id.required' => 'Please select a make for the vehicle.',
                'vehicle_model_id.required' => 'Please select a model for the vehicle.',
                'location_id.required' => 'Please select a location for the vehicle.',
                'vehicle_type_id.required' => 'Please select the vehicle type.',
                'year.required' => 'The vehicle year is required.',
                'year.numeric' => 'Please enter a valid numeric year.',
                'year.digits' => 'The year must be a 4-digit number.',
                'year.min' => 'The year must be 1900 or later.',
                'year.max' => 'The year cannot be in the future.',
                'mileage.required' => 'Mileage is required!',
                'mileage.integer'  => 'Mileage must be a valid number!',
                'mileage.min'      => 'Mileage cannot be less than 0!',
                'transmission.required' => 'Please specify the transmission type (e.g., automatic, manual).',
                'fuel_type.required' => 'Please specify the fuel type (e.g., petrol, diesel).',
                'door.required' => 'Please enter the number of doors in the vehicle.',
                'door.numeric' => 'The number of doors must be a number.',
                'door.min' => 'The vehicle must have at least 2 doors.',
                'seats.required' => 'Please enter the number of seats in the vehicle.',
                'seats.numeric' => 'The number of seats must be a number.',
                'seats.min' => 'The vehicle must have at least 2 seats.',
                'seats.max' => 'The vehicle can have a maximum of 7 seats.',
                'files.required' => 'At least one image  is required.',
                'files.min' => 'Please upload at least one file.',
                'files.*.mimes' => 'Each file must be an image (jpg, jpeg, png, webp).',
                'files.*.max' => 'Each file must be less than 10MB in size.',
                'amenities.required' => 'Please select at least one amenity.',
                'amenities.*.exists' => 'The selected amenity is invalid.',
            ]
        );


        // Step 2: Create a new Vehicle
        $vehicle = Vehicle::create([
            'title' => $validated['title'],
            'vin_nbr' => $validated['vin_nbr'],
            'lic_plate_nbr' => $validated['lic_plate_nbr'],
            'description' => $validated['description'],
            'short_Description' => $validated['short_Description'],

            'rent' => $validated['rent'],
            'make_id' => $validated['make_id'],
            'vehicle_model_id' => $validated['vehicle_model_id'],
            'location_id' => $validated['location_id'],
            'vehicle_type_id' => $validated['vehicle_type_id'],
            'year' => $validated['year'],
            'mileage' => $validated['mileage'],
            'transmission' => $validated['transmission'],
            'fuel_type' => $validated['fuel_type'],
            'door' => $validated['door'],
            'seats' => $validated['seats'],
            'status' => $validated['status'],
        ]);
        // Step 3: Attach Amenities
        $vehicle->vehicleAmenities()
            ->attach($validated['amenities']);
        // Step 4: Store the uploaded files (images) with new naming convention











        if ($request->hasFile('files')) {
            $tempFiles = [];
            foreach ($request->file('files') as $file) {
                $tempPath = $file->store('temp');
                $tempFiles[] = storage_path("app/{$tempPath}");
            }


            ProcessVehiclePic::dispatch($vehicle->id, $tempFiles);
        }






        // Step 5: Return success response
        return response()->json([
            'success' => true,
            'message' => 'Vehicle added successfully'
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        if (DB::table('bookings')->where('vehicle_id', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This vehicle is already booked, so it cannot be deleted.'
            ], 400);
        }

        foreach ($vehicle->vehicleImages as $image) {
            $imagePath = public_path("assets/img/vehicle_images/{$image->image_url}");
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $image->delete();
        }

        $vehicle->vehicleAmenities()->detach();

        $vehicle->delete();
        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully.'
        ], 200);
    }


    public function slectVehicle(Request $request)
    {

        return response()->json(
            Vehicle::select('id', 'title as name')
                ->when(
                    $request->filled('search'),
                    fn($query) =>
                    $query->where('title', 'like', "%{$request->search}%")
                )
                ->limit(10)
                ->get()
        );
    }



    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name); // Naam ko slug mein tabdeel karein

        // Current timestamp ko hasil karein
        $timestamp = now()->timestamp; // ya `time()` bhi use kar sakte hain

        // Slug ke end mein timestamp add karein
        $slug = "{$slug}-{$timestamp}";

        return $slug; // Mufeed (unique) slug wapas karein
    }
}
