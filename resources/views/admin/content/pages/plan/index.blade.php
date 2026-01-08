@php
$configData = Helper::appClasses();
@endphp

@extends('admin/layouts/layoutMaster')

@section('title', 'Service Time')

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
'resources/assets/admin/js/plans.js',
])

@endsection


@section('content')
<h4>Plans</h4>
{{-- content --}}

<div class="card">
    <div class="card-datatable table-responsive">
        <table class="datatables-permissions table border-top">
            <thead>
                <tr>

                    <th>Serial</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Origin </th>
                    <th>Destination</th>
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
                <div class="text-center mb-6">
                    <h4 class="mb-2">Add New Service Time</h4>
                    {{-- <p>Permissions you may use and assign to your users.</p> --}}
                </div>
                <form id="addPermissionForm" class="row pt-2" onsubmit="return false">


                    <div class="col-12 mb-4">
                        <label for="select2Basicaa" class="form-label">Service Time </label>
                        <select name="servicetime_id" id="select2Basicaa" data-url="{{ route('select.servicetime') }}"
                            class="select2  form-select form-select-lg" data-placeholder="Select Service Time"
                            data-allow-clear="true">

                        </select>
                    </div>


                    <div class="col-12 mb-4">
                        <label class="form-label" for="modalPermissionName"> Plan Name</label>
                        <input type="text" id="modalPermissionName" name="name" class="form-control" placeholder=" Name"
                            autofocus />
                    </div>
                    <div class="col-12 mb-4">
                        <label class="form-label" for="modalPermissionName"> Plan Price</label>
                        <input type="number" id="modalPermissionName" name="price" class="form-control"
                            placeholder=" Price" autofocus />
                    </div>

                    <div class="col-12 mb-4">
                        <label for="select2Basica" class="form-label">Select Origin Area </label>
                        <select name="area_from_id" id="select2Basica" data-url="{{ route('select.towns') }}"
                            class="select2  form-select form-select-lg" data-placeholder="Select  Origin  Area"
                            data-allow-clear="true">

                        </select>
                    </div>

                    <div class="col-12 mb-4">
                        <label for="select2Basic" class="form-label">Select Destination Area </label>
                        <select name="area_to_id" id="select2Basic" data-url="{{ route('select.towns') }}"
                            class="select2  form-select form-select-lg" data-placeholder="Select  Destination  Area"
                            data-allow-clear="true">

                        </select>
                    </div>



                    <div class="col-12 text-center demo-vertical-spacing">
                        <button type="submit" class=" btn btn-primary me-4">Create Plan</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Discard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Add Permission Modal -->





<!-- Edit Permission Modal -->


<div class="modal fade" id="editPlanModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-simple">
    <div class="modal-content p-4">

      <div class="modal-header">
        <h5>Edit Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="editPlanForm" class="row" onsubmit="return false">

         <div class="col-12 mb-4">
                        <label for="select2Basicaaaaa" class="form-label">Service Time </label>
                        <select name="servicetime_id" id="select2Basicaaaaa" data-url="{{ route('select.servicetime') }}"
                            class="select2  form-select form-select-lg" data-placeholder="Select Service Time"
                            data-allow-clear="true">

                        </select>
                    </div>

        <div class="col-12 mb-3">
          <label>Plan Name</label>
          <input type="text" id="edit_name" name="name" class="form-control">
        </div>

        <div class="col-12 mb-3">
          <label>Price</label>
          <input type="number" id="edit_price" name="price" class="form-control">
        </div>

        <div class="col-12 mb-3">
          <label>Area From</label>
          <select id="edit_area_from"  data-placeholder="Select  Destination  Area"
                            data-allow-clear="true"
          data-url="{{ route('select.towns') }}" name="area_from_id" class="select2 form-select"></select>
        </div>

        <div class="col-12 mb-3">
          <label>Area To</label>
          <select id="edit_area_to" 
          data-placeholder="Select  Destination  Area"
                            data-allow-clear="true"
          data-url="{{ route('select.towns') }}"
           name="area_to_id" class="select2 form-select"></select>
        </div>

        <div class="col-12 mb-4">
          <label>Status</label>
          <select id="edit_status" name="status" class="form-select">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        <div class="col-12 text-center">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>

      </form>
    </div>
  </div>
</div>


<!--/ Edit Permission Modal -->



@endsection