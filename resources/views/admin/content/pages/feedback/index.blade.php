@php
$configData = Helper::appClasses();
@endphp

@extends('admin/layouts/layoutMaster')

@section('title', 'feedback')

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
'resources/assets/admin/js/customer-feedback.js',
])

@endsection


@section('content')
<h4>feedback</h4>
{{-- content --}}

<div class="card">
    <div class="card-datatable table-responsive">
        <input type="hidden" id="table-btn-url" value="{{ route('Listings.vehicle.add') }}">
        <table class="datatables-permissions table border-top">

            <thead>
                <tr>
                    <th>Serial</th>
                    <th>Name</th>
                    <th>Message</th>
                    <th>Rating</th>
                    <th>Status</th>
                    
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>






            </div>
        </div>
    </div>
</div>
<!--/ Add Permission Modal -->









@endsection