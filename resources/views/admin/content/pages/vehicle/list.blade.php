@php
$configData = Helper::appClasses();
@endphp

@extends('admin/layouts/layoutMaster')

@section('title', 'List-Vehicle')

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
'resources/assets/admin/js/vehicle-list.js',
])

@endsection


@section('content')
<h4>Vehicle</h4>
{{-- content --}}

<div class="card">
    <div class="card-datatable table-responsive">
        <input type="hidden" id="table-btn-url" value="{{ route('Listings.vehicle.add') }}">
        <table class="datatables-permissions table border-top">

            <thead>
                <tr>
                    <th>Serial</th>
                    <th>name</th>
                    <th>location</th>
                    <th>Price</th>
                    <th>status</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>












@endsection