@extends('admin/layouts/layoutMaster')
namespace App\Http\Controllers\Admin;

@section('title', 'Edit Driver')

@section('vendor-style')
@vite([
'resources/assets/admin/vendor/libs/quill/typography.scss',
'resources/assets/admin/vendor/libs/@form-validation/form-validation.scss',
'resources/assets/admin/vendor/libs/quill/editor.scss',
'resources/assets/admin/vendor/libs/select2/select2.scss',
'resources/assets/admin/vendor/libs/dropzone/dropzone.scss',
'resources/assets/admin/vendor/libs/flatpickr/flatpickr.scss'
])
@endsection

@section('vendor-script')
@vite([
'resources/assets/admin/vendor/libs/quill/quill.js',
'resources/assets/admin/vendor/libs/select2/select2.js',
'resources/assets/admin/vendor/libs/dropzone/dropzone.js',
'resources/assets/admin/vendor/libs/jquery-repeater/jquery-repeater.js',
'resources/assets/admin/vendor/libs/flatpickr/flatpickr.js',
'resources/assets/admin/vendor/libs/sortablejs/sortable.js',
'resources/assets/admin/vendor/libs/@form-validation/popular.js',
'resources/assets/admin/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/admin/vendor/libs/@form-validation/auto-focus.js',

])
@endsection

@section('page-script')
@vite([
'resources/assets/admin/js/forms-selects.js'
])
@endsection

@section('content')




<form id="driver-add-form" method="POST" enctype="multipart/form-data" action="{{ route('driver.update') }}">
    @csrf
    <input type="hidden" name="driver_id" value="{{ $driver->id }}">

    <!-- ================= DRIVER INFO ================= -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Edit Driver Information</h5>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Driver Name</label>
                    <input type="text" name="name" readonly class="form-control"
                        value="{{ old('name', $driver->user->name) }}" required>
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Father Name</label>
                    <input type="text" name="father_name" readonly class="form-control"
                        value="{{ old('father_name', $driver->father_name) }}" required>
                    @error('father_name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" readonly class="form-control"
                        value="{{ old('email', $driver->user->email) }}" required>
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">CNIC</label>
                    <input type="text" name="cnic" readonly class="form-control"
                        value="{{ old('cnic', $driver->cnic) }}" required>
                    @error('cnic')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contact</label>
                    <input type="text" name="contact" readonly class="form-control"
                        value="{{ old('contact', $driver->contact) }}" required>
                    @error('contact')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Emergency Contact</label>
                    <input type="text" name="emergency_contact" readonly class="form-control"
                        value="{{ old('emergency_contact', $driver->emergency_contact) }}">
                    @error('emergency_contact')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Blood Group</label>
                    <input type="text" name="blood_group" readonly class="form-control"
                        value="{{ old('blood_group', $driver->blood_group) }}">
                    @error('blood_group')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="" readonly class="form-control"
                    value="{{ old('blood_group', $driver->address) }}">


            </div>
<div class="row">
            <div class="col-6">
                <label class="form-label">City</label>

                <input type="text" name="" readonly class="form-control"
                    value="{{ old('blood_group', $driver->city->name) }}">

                
            </div>
            <div class="col-6">
                <label class="form-label">Status</label>

                <input type="text" name="" readonly class="form-control"
                    value="{{ old('status', $driver->verification_status) }}">

                
            </div>
            </div>

        </div>
    </div>

    <!-- Include GLightbox CSS in your layout head -->
    <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet" />



    <div class="card mb-4">
    <div class="card-header">
        <h5>Driver Documents</h5>
    </div>

    <div class="card-body">
        @php $documents = $driver->documents ?? null; @endphp

        <!-- CNIC -->
        <div class="mb-3">
            <label class="form-label">CNIC Images</label>
            <div id="cnicPreview" class="d-flex flex-wrap gap-3 mt-3">
                @if($documents && $documents->cnic_images)
                @foreach(json_decode($documents->cnic_images) as $image)
                <a href="{{ asset($image) }}" class="glightbox" data-glightbox="title: CNIC Image">
                    <img src="{{ asset($image) }}" alt="CNIC"
                        style="height:120px; width:auto; border-radius:8px; cursor:pointer;">
                </a>
                @endforeach
                @endif
            </div>
            @error('cnic_images.*')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- License -->
        <div class="mb-3">
            <label class="form-label">License Images</label>
            <div id="licensePreview" class="d-flex flex-wrap gap-3 mt-3">
                @if($documents && $documents->license_images)
                @foreach(json_decode($documents->license_images) as $image)
                <a href="{{ asset($image) }}" class="glightbox" data-glightbox="title: License Image">
                    <img src="{{ asset($image) }}" alt="License"
                        style="height:120px; width:auto; border-radius:8px; cursor:pointer;">
                </a>
                @endforeach
                @endif
            </div>
            @error('license_images.*')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Profile -->
        <div class="mb-3">
            <label class="form-label">Profile Image</label>
            <div id="profilePreview" class="mt-3">
                @if($documents && $documents->profile_image)
                <a href="{{ asset($documents->profile_image) }}" class="glightbox"
                    data-glightbox="title: Profile Image">
                    <img src="{{ asset($documents->profile_image) }}" alt="Profile"
                        style="height:150px; width:auto; border-radius:8px; cursor:pointer;">
                </a>
                @endif
            </div>
            @error('profile_image')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Verification -->
        <div class="mb-3">
            <label class="form-label">Verification Image</label>
            <div id="verificationPreview" class="mt-3">
                @if($documents && $documents->verification_image)
                <a href="{{ asset($documents->verification_image) }}" class="glightbox"
                    data-glightbox="title: Verification Image">
                    <img src="{{ asset($documents->verification_image) }}" alt="Verification"
                        style="height:150px; width:auto; border-radius:8px; cursor:pointer;">
                </a>
                @endif
            </div>
            @error('verification_image')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Other Documents -->
        <div class="mb-3">
            <label class="form-label">Other Documents</label>
            <div id="otherPreview" class="d-flex flex-wrap gap-3 mt-3">
                @if($documents && $documents->other_document)
                @foreach(json_decode($documents->other_document) as $image)
                <a href="{{ asset($image) }}" class="glightbox" data-glightbox="title: Other Document">
                    <img src="{{ asset($image) }}" alt="Other Document"
                        style="height:120px; width:auto; border-radius:8px; cursor:pointer;">
                </a>
                @endforeach
                @endif
            </div>
            @error('other_document.*')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>


    <!-- Include GLightbox JS at the end of body -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        zoomable: true,
    });
    </script>


  

</form>





@endsection