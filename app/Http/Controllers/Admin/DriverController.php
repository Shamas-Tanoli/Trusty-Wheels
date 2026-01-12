<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Town;
use App\Models\User;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\Enquiry;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DriverDocument;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function detail($id)
    {
        $driver = Driver::with(['user', 'city', 'documents'])->findOrFail($id);

        return view('admin.content.pages.driver.singledetail', compact('driver'));
    }



    public function update(Request $request)
    {
        $driver = Driver::with('user', 'documents')->findOrFail($request->driver_id);

        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'verification_status' => 'required',
            'father_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $driver->user->id,
            'cnic' => 'required|string|max:20',
            'contact' => 'required|string|max:20',

           
            'password' => 'nullable|string|min:6',

            'city_id' => 'nullable|exists:cities,id',

            'profile_image' => 'nullable|image|max:2048',
            'verification_image' => 'nullable|image|max:2048',

            'cnic_images.*' => 'nullable|image|max:2048',
            'license_images.*' => 'nullable|image|max:2048',
            'other_document.*' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            /* ===================== 1️⃣ UPDATE USER ===================== */
            $user = $driver->user;
            $user->name  = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                // if (!Hash::check($request->old_password, $user->password)) {
                //     return redirect()->back()
                //         ->withErrors(['old_password' => 'Old password is incorrect'])
                //         ->withInput();
                // }
                $user->password = Hash::make($request->password);
            }

            $user->save();

            /* ===================== 2️⃣ UPDATE DRIVER ===================== */
            $driver->father_name       = $request->father_name;
            $driver->cnic              = $request->cnic;
            $driver->contact           = $request->contact;
            $driver->emergency_contact = $request->emergency_contact;
            $driver->blood_group       = $request->blood_group;
            $driver->address           = $request->address;
            $driver->city_id           = $request->city_id;
            $driver->verification_status           = $request->verification_status;
            $driver->save();

            /* ===================== 3️⃣ UPDATE DOCUMENTS ===================== */
            $documents = $driver->documents ?? new DriverDocument();
            $documents->driver_id = $driver->id;

            $uploadFiles = function ($field, $oldData = null, $multiple = false) use ($request) {
                if (!$request->hasFile($field)) return $oldData;

                if ($multiple) {
                    // Delete old files
                    if (!empty($oldData)) {
                        foreach ($oldData as $old) {
                            $oldPath = public_path($old);
                            if (file_exists($oldPath)) unlink($oldPath);
                        }
                    }

                    $newFiles = [];
                    foreach ($request->file($field) as $file) {
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path("drivers/{$field}"), $filename);
                        $newFiles[] = "drivers/{$field}/$filename";
                    }
                    return $newFiles;
                }

                // Delete old single file
                if (!empty($oldData)) {
                    $oldPath = public_path($oldData);
                    if (file_exists($oldPath)) unlink($oldPath);
                }

                $file = $request->file($field);
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path("drivers/{$field}"), $filename);
                return "drivers/{$field}/$filename";
            };

            // Multiple images
            $documents->cnic_images = json_encode(
                $uploadFiles('cnic_images', json_decode($documents->cnic_images ?? '[]', true), true)
            );

            $documents->license_images = json_encode(
                $uploadFiles('license_images', json_decode($documents->license_images ?? '[]', true), true)
            );

            $documents->other_document = json_encode(
                $uploadFiles('other_document', json_decode($documents->other_document ?? '[]', true), true)
            );

            // Single images
            $documents->profile_image = $uploadFiles('profile_image', $documents->profile_image);
            $documents->verification_image = $uploadFiles('verification_image', $documents->verification_image);

            $documents->save();

            DB::commit();

            return redirect()->back()->with('success', 'Driver updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])
                ->withInput();
        }
    }




    public function edit(Driver $driver)
    {
        $driver->load(['user', 'city', 'documents']);


        return view('admin.content.pages.driver.edit', compact('driver'));
    }


    public function list(Request $request)
    {
        if ($request->ajax()) {

            $drivers = Driver::with(['user:id,name,email', 'city', 'documents'])
                ->select('drivers.*')
                ->orderByDesc('created_at');

            return DataTables::of($drivers)
                ->addIndexColumn()

                // 🔹 Name + Profile Image (Vehicle style)
                ->addColumn('name', function ($driver) {

                    $image = $driver->documents?->profile_image
                        ? asset($driver->documents->profile_image)
                        : asset('assets/img/default-avatar.png');

                    $name  = ucfirst($driver->user->name ?? 'N/A');
                    $email = $driver->cnic ?? '';

                    return '
                <div class="d-flex align-items-center">
                <a class="d-flex" href="' . route('driver.detail', $driver->id) . '">
                    <div class="avatar avatar me-3">
                        <img src="' . $image . '" class="rounded-circle">
                    </div>
                    <div class="d-flex flex-column">
                        <h6 class="mb-0">' . $name . '</h6>
                        <small class="text-muted">' . $email . '</small>
                    </div>
                    </a>
                </div>';
                })

                ->addColumn('email', function ($driver) {
                    return $driver->user->email ?? 'N/A';
                })

                ->addColumn('contact', function ($driver) {
                    return $driver->contact;
                })

                ->addColumn('status', function ($driver) {

                    $statusMap = [
                        'in-active'  => 'warning',
                        'active' => 'success',
                      
                    ];

                    $badge = $statusMap[$driver->verification_status] ?? 'secondary';

                    return '<span class="badge bg-label-' . $badge . '">'
                        . ucfirst($driver->verification_status) .
                        '</span>';
                })

                ->addColumn('action', function ($driver) {
                    return '
                 <a href="' . route('driver.edit', $driver->id) . '"
                   class="btn btn-icon btn-text-secondary  rounded-pill me-1">
                    <i class="ti ti-edit ti-md"></i>
                </a>

                <button data-id="' . $driver->id . '"
                    class="delete-confirm btn btn-icon btn-text-secondary rounded-pill">
                    <i class="ti ti-trash ti-md"></i>
                </button>';
                })

                ->filter(function ($query) use ($request) {
                    if ($search = $request->input('search.value')) {
                        $query->whereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                    }
                })

                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }
    }


    public function listVew()
    {
        return view('admin.content.pages.driver.list');
    }



    public function create()
    {
        $cities = City::all();
        return view('admin.content.pages.driver.index', compact('cities'));
    }


    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            // 🔹 Create Driver
            $user = User::create([
                'name'              => $request->name,
                'role'       => 'driver',
                'email'              => $request->email,
                'password'           => Hash::make($request->password),
            ]);

            $driver = Driver::create([
                'user_id'              => $user->id,
                'city_id'              => $request->city_id,
                'father_name'       => $request->father_name,
                'cnic'              => $request->cnic,
                'contact'           => $request->contact,
                'emergency_contact' => $request->emergency_contact,
                'blood_group'       => $request->blood_group,
                'address'           => $request->address,
                'city_id'           => $request->city_id,
                'verification_status' => $request->verification_status,
            ]);

            // 🔹 Image directory
            $path = public_path('assets/drivers');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            // 🔹 Helper function for multiple images
            $uploadMultiple = function ($files) use ($path) {
                $paths = [];
                foreach ($files as $file) {
                    $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($path, $name);
                    $paths[] = 'assets/drivers/' . $name;
                }
                return $paths;
            };

            // 🔹 Helper function for single image
            $uploadSingle = function ($file) use ($path) {
                $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $name);
                return 'assets/drivers/' . $name;
            };

            // 🔹 Prepare documents data
            $documents = [
                'driver_id' => $driver->id,
            ];

            if ($request->hasFile('cnic_images')) {
                $documents['cnic_images'] = json_encode(
                    $uploadMultiple($request->file('cnic_images'))
                );
            }

            if ($request->hasFile('license_images')) {
                $documents['license_images'] = json_encode(
                    $uploadMultiple($request->file('license_images'))
                );
            }

            if ($request->hasFile('profile_image')) {
                $documents['profile_image'] = $uploadSingle(
                    $request->file('profile_image')
                );
            }

            if ($request->hasFile('verification_image')) {
                $documents['verification_image'] = $uploadSingle(
                    $request->file('verification_image')
                );
            }

            if ($request->hasFile('other_document')) {
                $documents['other_document'] = json_encode(
                    $uploadMultiple($request->file('other_document'))
                );
            }

            // 🔹 Save documents
            DriverDocument::create($documents);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Driver added successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {


        $driver = Driver::findOrFail($id);

        DB::beginTransaction();

        try {
            // 🔹 Delete associated user
            $user = $driver->user;
            if ($user) {
                $user->delete();
            }

            // 🔹 Delete driver documents and files
            $documents = $driver->documents;
            if ($documents) {
                $documentFields = [
                    'profile_image',
                    'verification_image',
                    'cnic_images',
                    'license_images',
                    'other_document'
                ];

                foreach ($documentFields as $field) {
                    $data = $documents->$field;

                    if (in_array($field, ['cnic_images', 'license_images', 'other_document'])) {
                        // Multiple images
                        $images = json_decode($data, true) ?? [];
                        foreach ($images as $imagePath) {
                            $fullPath = public_path($imagePath);
                            if (file_exists($fullPath)) {
                                unlink($fullPath);
                            }
                        }
                    } else {
                        // Single image
                        if (!empty($data)) {
                            $fullPath = public_path($data);
                            if (file_exists($fullPath)) {
                                unlink($fullPath);
                            }
                        }
                    }
                }

                $documents->delete();
            }

            // 🔹 Delete driver
            $driver->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Driver deleted successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


 public function getdriver(Request $request)
{
    return response()->json(
        Driver::with('user')
            ->select('id', 'user_id')
            ->when(
                $request->filled('search'),
                fn($query) =>
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                })
            )
            ->limit(20)
            ->get()
            ->map(function ($driver) {
                return [
                    'id' => $driver->id,              // driver table id
                    'name' => $driver->user->name,    // user table name
                ];
            })
    );
}


}
