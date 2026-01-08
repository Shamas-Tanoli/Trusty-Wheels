@extends('admin/layouts/layoutMaster')

@section('title', 'Driver')

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
'resources/assets/admin/js/forms-selects.js',
'resources/assets/admin/js/driveradd.js'
])
@endsection

@section('content')
<form id="driver-add-form" enctype="multipart/form-data">
    <div class="card mb-4">
        <div class="card-header">
            <h5>Driver Information</h5>
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Driver Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Father Name</label>
                    <input type="text" name="father_name" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                
                <div class="col-md-6 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">CNIC</label>
                    <input type="text" name="cnic" class="form-control" placeholder="xxxxx-xxxxxxx-x" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contact</label>
                    <input type="text" name="contact" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Emergency Contact</label>
                    <input type="text" name="emergency_contact" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Blood Group</label>
                    <input type="text" name="blood_group" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control"></textarea>
            </div>

            <div class="row mb-3">
                

                <div class="col-md-6">
                    <label class="form-label">City </label>
                   



                    <select id="city_id" class=" select2 form-select" data-url="{{ route('select.cities') }}"
                                name="city_id" data-placeholder="Select City">
                            </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Verification Status</label>
                   



                    <select id="verification_status" name="verification_status" class=" select2 form-select" >
                        <option value="" disabled selected>Select Status</option>
                        <option value="active">Active</option>
                        <option value="in-active">In-Active</option>
                            </select>
                </div>
            </div>

        </div>
    </div>

    <!-- Documents -->
    <div class="card">
        <div class="card-header">
            <h5>Driver Documents</h5>
        </div>
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">CNIC Images</label>
                <input type="file" name="cnic_images[]" class="form-control preview-input" multiple data-preview="cnicPreview">
                <div id="cnicPreview" class="d-flex gap-2 mt-2"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">License Images</label>
                <input type="file" name="license_images[]" class="form-control preview-input" multiple data-preview="licensePreview">
                <div id="licensePreview" class="d-flex gap-2 mt-2"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Profile Image</label>
                <input type="file" name="profile_image" class="form-control preview-input" data-preview="profilePreview">
                <div id="profilePreview" class="mt-2"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Verification Image</label>
                <input type="file" name="verification_image" class="form-control preview-input" data-preview="verificationPreview">
                <div id="verificationPreview" class="mt-2"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Other Documents</label>
                <input type="file" name="other_document[]" class="form-control preview-input" multiple data-preview="otherPreview">
                <div id="otherPreview" class="d-flex gap-2 mt-2"></div>
            </div>

        </div>
    </div>

    <div class="mt-3 text-end">
        <button type="reset" class="btn btn-secondary">Reset</button>
        <button type="submit" class="btn btn-primary">Save Driver</button>
    </div>
</form>
<script>
document.querySelectorAll('.preview-input').forEach(input => {
    input.addEventListener('change', function () {
        let previewId = this.dataset.preview;
        let previewBox = document.getElementById(previewId);
        previewBox.innerHTML = '';

        Array.from(this.files).forEach(file => {
            let reader = new FileReader();
            reader.onload = function (e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '80px';
                img.style.height = '80px';
                img.style.objectFit = 'cover';
                img.classList.add('rounded', 'border');
                previewBox.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
});
</script>

@endsection