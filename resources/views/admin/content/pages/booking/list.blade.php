@php
$configData = Helper::appClasses();
@endphp

@extends('admin/layouts/layoutMaster')

@section('title', 'List-Booking')

@section('vendor-style')
@vite([
'resources/assets/admin/vendor/libs/select2/select2.scss',
'resources/assets/admin/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/admin/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
'resources/assets/admin/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
'resources/assets/admin/vendor/libs/@form-validation/form-validation.scss',
'resources/assets/admin/vendor/libs/flatpickr/flatpickr.scss'])
@endsection

@section('vendor-script')
@vite([
'resources/assets/admin/vendor/libs/flatpickr/flatpickr.js',
'resources/assets/admin/vendor/libs/select2/select2.js',
'resources/assets/admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
'resources/assets/admin/vendor/libs/@form-validation/popular.js',
'resources/assets/admin/vendor/libs/jquery/jquery.js',
'resources/assets/admin/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/admin/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
@vite([
'resources/assets/admin/js/forms-selects.js',
'resources/assets/admin/js/booking-list.js',
])

@endsection


@section('content')
<h4>Booking</h4>
{{-- content --}}

<div class="card">
    <div class="card-datatable table-responsive">
        <input type="hidden" id="table-btn-url" value="{{ route('Listings.vehicle.add') }}">
        <table class="datatables-permissions table border-top">

            <thead>
                <tr>
                    {{-- <th>Serial</th> --}}
                    <th>Customer</th>
                    <th>Plan</th>
                    <th>Service</th>
                    <th>Service Time</th>
                    <th>Town</th>
                    <th>Passengers</th>
                    <th>Status</th>
                    <th>Action</th>
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
                <div class="text-center ">
                    <h4 class="">Change Status</h4>
                    {{-- <p>Permissions you may use and assign to your users.</p> --}}
                </div>
                <form id="addPermissionForm" class="row">

    <input type="hidden" name="booking_id" id="bookingid">

    <div class="col-12 mb-4">
        <label class="form-label">Status</label>
        <select id="statussss" class="form-select" name="status" required>
            <option value="" disabled selected>Select Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary me-4">Change Status</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Discard
        </button>
    </div>

</form>

            </div>
        </div>
    </div>
</div>
<!--/ Add Permission Modal -->





<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" aria-hidden="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Edit Booking </h4>
                    {{-- <p>Edit permission as per your requirements.</p> --}}
                </div>

                <form id="editPermissionForm" class="row pt-2" onsubmit="return false">
                    <input type="hidden" name="id" id="bookingid">
                    <div class="col-12 mb-4">
                        <label class="form-label" for="Vehicle">Vehicle </label>
                        <select id="Vehicleedit" class=" select2 form-select" data-url="{{ route('select.vehicle') }}"
                            name="vehicle_id" data-placeholder="Select Vehicle">
                        </select>
                    </div>

                    <div class="col-12 col-md-6  mb-4">
                        <label class="form-label" for="driveredit">Driver </label>
                        <select id="driveredit" class=" select2 form-select" name="driver_id"
                            data-placeholder="Select Driver">
                        </select>
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                        <label class="form-label" for="flatpickrdateedit">Start Date </label>
                        <input type="text" name='start_date' class="form-control flatpickr-date flatpickr-input active"
                            placeholder="YYYY-MM-DD" id="flatpickrdateedit">
                    </div>


                    <div class="col-12 col-md-6 mb-4">
                        <label class="form-label" for="status-edit">Status </label>
                        <select name="status" class=" form-select" id="status-edit">
                        </select>
                    </div>


                    <div class="col-12 col-md-3 mt-2 d-flex justify-content-center align-items-center ">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Edit Permission Modal -->

<!-- Passenger Modal -->
<div class="modal fade" id="passengerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Passengers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="passengerTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Pickup Time</th>
                            <th>Dropoff Time</th>
                            <th>Pickup Location</th>
                            <th>Dropoff Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- AJAX populate -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection