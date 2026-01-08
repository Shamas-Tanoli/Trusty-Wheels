<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;
use App\Models\ServiceVehicle;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ServiceVehicleController extends Model
{
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
