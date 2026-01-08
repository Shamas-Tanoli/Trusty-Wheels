@php
$configData = Helper::appClasses();
@endphp

@extends('admin/layouts/layoutMaster')

@section('title', 'Amenity')

@section('vendor-style')
@vite(['resources/assets/admin/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/admin/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
'resources/assets/admin/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
'resources/assets/admin/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
'resources/assets/admin/vendor/libs/@form-validation/popular.js',
'resources/assets/admin/vendor/libs/jquery/jquery.js',
'resources/assets/admin/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/admin/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
@vite(['resources/assets/admin/js/amenity-table.js',
'resources/assets/admin/js/amenity-add.js',
'resources/assets/admin/js/amenity-edit.js'])

@endsection


@section('content')
<h4>Vehicle Check</h4>
{{-- content --}}

<div class="card">
    <div class="card-datatable table-responsive">
        <table class="datatables-permissions table border-top">
            <thead>
                <tr>

                    <th>Serial</th>
                    <th>Vehicle Check</th>
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
                    <h4 class="mb-2">Add New Amenity</h4>
                    {{-- <p>Permissions you may use and assign to your users.</p> --}}
                </div>
                <form id="addPermissionForm" class="row pt-2"  onsubmit="return false">
                    <div class="col-12 mb-4">
                        <label class="form-label" for="modalPermissionName">Amenity Name</label>
                        <input type="text" id="modalPermissionName" name="name" class="form-control"
                            placeholder="Amenity Name" autofocus />
                    </div>

                    <div class="col-12 text-center demo-vertical-spacing">
                        <button type="submit" class=" btn btn-primary me-4">Create Amenity</button>
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
<div class="modal fade" id="editPermissionModal" aria-hidden="false" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered modal-simple">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-6">
            <h4 class="mb-2">Edit Amenity</h4>
            {{-- <p>Edit permission as per your requirements.</p> --}}
          </div>
          
          <form id="editPermissionForm" class="row pt-2" onsubmit="return false">
            <div class="col-sm-9">
              <label class="form-label" for="editname">Amenity Name</label>
              <input type="text" id="editname" name="name" class="form-control" placeholder="Amenity Name" tabindex="-1" />
            </div>
            <div class="col-sm-3 mb-4">
              <label class="form-label invisible d-none d-sm-inline-block">Button</label>
              <button type="submit" class="btn btn-primary mt-1 mt-sm-0">Update</button>
            </div>
            
          </form>
        </div>
      </div>
    </div>
  </div>
<!--/ Edit Permission Modal -->



@endsection