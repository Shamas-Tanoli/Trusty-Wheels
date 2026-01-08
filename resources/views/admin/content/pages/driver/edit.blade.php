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

<!-- ================= ALERTS ================= -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif



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
                    <input type="text" name="name" class="form-control" value="{{ old('name', $driver->user->name) }}" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Father Name</label>
                    <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $driver->father_name) }}" required>
                    @error('father_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $driver->user->email) }}" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="password">
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">CNIC</label>
                    <input type="text" name="cnic" class="form-control" value="{{ old('cnic', $driver->cnic) }}" required>
                    @error('cnic')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contact</label>
                    <input type="text" name="contact" class="form-control" value="{{ old('contact', $driver->contact) }}" required>
                    @error('contact')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Emergency Contact</label>
                    <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $driver->emergency_contact) }}">
                    @error('emergency_contact')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Blood Group</label>
                    <input type="text" name="blood_group" class="form-control" value="{{ old('blood_group', $driver->blood_group) }}">
                    @error('blood_group')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control">{{ old('address', $driver->address) }}</textarea>
                @error('address')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="row">
            <div class="col-6">
                <label class="form-label">City</label>
                <select name="city_id" class="select2 form-select" data-placeholder="Select city" data-url="{{ route('select.cities') }}">
                    <option value="{{ $driver->city_id }}" selected>{{ $driver->city->name }}</option>
                </select>
                @error('city_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
           <div class="col-6">
    <label class="form-label">City</label>
    <select id="verification_status" name="verification_status" class="select2 form-select">
        <option value="" disabled {{ old('verification_status', $driver->verification_status ?? '') == '' ? 'selected' : '' }}>Select Status</option>
        <option value="active" {{ old('verification_status', $driver->verification_status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="in-active" {{ old('verification_status', $driver->verification_status ?? '') == 'in-active' ? 'selected' : '' }}>In-Active</option>
    </select>
    @error('verification_status')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>


            </div>

        </div>
    </div>

    <!-- ================= DOCUMENTS ================= -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Driver Documents</h5>
        </div>

        <div class="card-body">
            @php $documents = $driver->documents ?? null; @endphp

            <!-- CNIC -->
            <div class="mb-3">
                <label class="form-label">CNIC Images</label>
                <input type="file" name="cnic_images[]" class="form-control preview-input" multiple data-preview="cnicPreview">
                <div id="cnicPreview" class="d-flex gap-2 mt-2">
                    @if($documents && $documents->cnic_images)
                        @foreach(json_decode($documents->cnic_images) as $image)
                            <img src="{{ asset($image) }}" alt="CNIC" style="height:80px; border-radius:5px;">
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
                <input type="file" name="license_images[]" class="form-control preview-input" multiple data-preview="licensePreview">
                <div id="licensePreview" class="d-flex gap-2 mt-2">
                    @if($documents && $documents->license_images)
                        @foreach(json_decode($documents->license_images) as $image)
                            <img src="{{ asset($image) }}" alt="License" style="height:80px; border-radius:5px;">
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
                <input type="file" name="profile_image" class="form-control preview-input" data-preview="profilePreview">
                <div id="profilePreview" class="mt-2">
                    @if($documents && $documents->profile_image)
                        <img src="{{ asset($documents->profile_image) }}" alt="Profile" style="height:100px; border-radius:5px;">
                    @endif
                </div>
                @error('profile_image')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Verification -->
            <div class="mb-3">
                <label class="form-label">Verification Image</label>
                <input type="file" name="verification_image" class="form-control preview-input" data-preview="verificationPreview">
                <div id="verificationPreview" class="mt-2">
                    @if($documents && $documents->verification_image)
                        <img src="{{ asset($documents->verification_image) }}" alt="Verification" style="height:100px; border-radius:5px;">
                    @endif
                </div>
                @error('verification_image')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Other Documents -->
            <div class="mb-3">
                <label class="form-label">Other Documents</label>
                <input type="file" name="other_document[]" class="form-control preview-input" multiple data-preview="otherPreview">
                <div id="otherPreview" class="d-flex gap-2 mt-2">
                    @if($documents && $documents->other_document)
                        @foreach(json_decode($documents->other_document) as $image)
                            <img src="{{ asset($image) }}" alt="Other Document" style="height:80px; border-radius:5px;">
                        @endforeach
                    @endif
                </div>
                @error('other_document.*')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

        </div>
    </div>

    <div class="text-end">
        <button type="reset" class="btn btn-secondary">Reset</button>
        <button type="submit" class="btn btn-primary">Save Driver</button>
    </div>

</form>


<script>
    document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.preview-input').forEach(function (input) {

        input.addEventListener('change', function () {

            let previewBox = document.getElementById(this.dataset.preview);
            previewBox.innerHTML = '';

            Array.from(this.files).forEach(function (file) {

                if (!file.type.startsWith('image/')) return;

                let reader = new FileReader();

                reader.onload = function (e) {
                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '80px';
                    img.style.height = '80px';
                    img.style.objectFit = 'cover';
                    img.className = 'rounded border';

                    previewBox.appendChild(img);
                };

                reader.readAsDataURL(file);
            });

        });

    });

});
</script>


@endsection