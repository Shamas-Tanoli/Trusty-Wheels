@php
$configData = Helper::appClasses();
@endphp

@extends('admin/layouts/layoutMaster')

@section('title', 'Service Vehicle')

@section('vendor-style')
@vite(['resources/assets/admin/vendor/libs/select2/select2.scss',
'resources/assets/admin/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/admin/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
'resources/assets/admin/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
'resources/assets/admin/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/admin/vendor/libs/select2/select2.js',
'resources/assets/admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
'resources/assets/admin/vendor/libs/@form-validation/popular.js',
'resources/assets/admin/vendor/libs/jquery/jquery.js',
'resources/assets/admin/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/admin/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
@vite([
'resources/assets/admin/js/forms-selects.js',
'resources/assets/admin/js/servicevehicle.js',
])

@endsection


@section('content')
<h4>Service Vehicle</h4>
{{-- content --}}

<div class="card">
    <div class="card-datatable table-responsive">
        <table class="datatables-vehicles table border-top">
            <thead>
                <tr>

                    <th>Serial</th>
                    <th>Name</th>
                    <th>Reg Number</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Add New Service Vehicle</h4>

                </div>
                <form id="addPermissionForm" class="row pt-2" onsubmit="return false">


                    <div class="col-12 mb-4">
                        <label class="form-label" for="modalPermissionName"> Vehicle Name</label>
                        <input type="text" id="modalPermissionName" name="name" class="form-control"
                            placeholder="Vehicle Name" autofocus />
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label" for="modalPermissionName"> Registration Number</label>
                        <input type="text" id="modalPermissionName" name="number_plate" class="form-control"
                            placeholder=" Registration Number" autofocus />
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label" for="vehicleImage">Vehicle Image</label>
                        <input type="file" id="vehicleImage" name="image" class="form-control" accept="image/*"
                            onchange="previewVehicleImage(event)" />
                        <div class="mt-2" style="
                    display: flex;
                    justify-content: center;
                ">
                            <img id="vehicleImagePreview" src="" alt="Image Preview"
                                style="max-width: 200px; display: none; border-radius: 8px;">
                        </div>
                    </div>


                    <div class="col-12 text-center demo-vertical-spacing">
                        <button type="submit" class=" btn btn-primary me-4">Create Vehicle</button>
                        <button type="reset" id="discardBtn" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Discard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Add Permission Modal -->

<script>
    // Make sure the function is globally accessible
    function previewVehicleImage(event) {
        const input = event.target;
        const preview = document.getElementById('vehicleImagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
     const discardBtn = document.getElementById('discardBtn');
    const vehicleInput = document.getElementById('vehicleImage');
    const vehiclePreview = document.getElementById('vehicleImagePreview');

    discardBtn.addEventListener('click', function() {
        // Clear the file input
        vehicleInput.value = '';
        // Hide the preview
        vehiclePreview.src = '';
        vehiclePreview.style.display = 'none';
    });
</script>



<!-- Edit Permission Modal -->


<div class="modal fade" id="editPermissionModal" aria-hidden="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content p-0">
            <div class="modal-body">
                <button type="button" class="btn-close btn-pinned custom-cross-position" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Edit service Time </h4>

                </div>

                <form id="editPermissionForm" class="row pt-2 justify-content-center" onsubmit="return false">

                    <div class="col-10 mb-4">
                        <label for="slectmake" class="form-label">Select service</label>
                        <select name="service_id" id="slectmake" data-url="{{ route('select.service') }}"
                            class="select2 form-select form-select-lg" data-allow-clear="true">
                        </select>
                    </div>

                    <div class="col-10 mb-4">
                        <label class="form-label" for="editname">Service Timing</label>
                        <input type="text" id="editname" name="timing" class="form-control" placeholder="Service Timing"
                            tabindex="-1" />
                    </div>
                    <div class="col-12 text-center demo-vertical-spacing">

                        <button type="submit" class="btn btn-primary mt-1 mt-sm-0">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!--/ Edit Permission Modal -->



@endsection