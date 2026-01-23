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
@vite(['resources/assets/admin/js/promo-codes.js'])
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center">
    <h4 class="mb-4">Discount</h4>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPromoModal">
        Add Discount
    </button>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table datatables-promo">
            <thead>
                <tr>
                    
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Usage</th>
                    <th>Status</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="modal fade" id="addPromoModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addPromoForm">
                @csrf
                <div class="modal-header">
                    <h5>Add Discount</h5>
                </div>

                <div class="modal-body row g-3">

                   

                    <div class="col-12 ">
                        <label for="" class="form-label">Code Type </label>
                        <select name="type" class="form-select">
                            <option  value="" selected disabled>Type</option>
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed</option>
                        </select>

                    </div>
                    <div class="col-12 ">
                        <label for="select2Basicaa" class="form-label">Value</label>
                        <input name="value" type="number" class="form-control" placeholder="50">

                    </div>
                    

                    
                    <div class="col-12 ">
                        <label for="select2Basicaa" class="form-label">Person</label>
                        <input name="usage_limit" type="number" class="form-control" placeholder="10 Person">
                    </div>

                    <div class="col-12 ">
                        <label for="" class="form-label">Active Status </label>
                        <select name="is_active" class="form-select">
                            <option  value="" selected disabled>Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editPromoModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editPromoForm">
                
                @csrf
                <div class="modal-header">
                    <h5>Add Promo Code</h5>
                </div>

                <div class="modal-body row g-3">
<input type="hidden" name="id">
                    <div class="col-12 ">
                        <label for="" class="form-label">Promo code </label>
                        <input name="code" class="form-control" placeholder="SAVE10">
                    </div>

                    <div class="col-12 ">
                        <label for="" class="form-label">Code Type </label>
                        <select name="type" class="form-select">
                            <option  value="" selected disabled>Type</option>
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed</option>
                        </select>

                    </div>
                    <div class="col-12 ">
                        <label for="select2Basicaa" class="form-label">Value</label>
                        <input name="value" type="number" class="form-control" placeholder="50">

                    </div>
                    <div class="col-12 ">
                        <label for="select2Basicaa" class="form-label">Start Date</label>
                        <input name="start_date" type="date" class="form-control">
                    </div>

                    <div class="col-12 ">
                        <label for="select2Basicaa" class="form-label">End Date</label>
                        <input name="end_date" type="date" class="form-control">
                    </div>
                    <div class="col-12 ">
                        <label for="select2Basicaa" class="form-label">Usage Limit</label>
                        <input name="usage_limit" type="number" class="form-control" placeholder="10 Person">
                    </div>

                    <div class="col-12 ">
                        <label for="" class="form-label">Active Status </label>
                        <select name="is_active" class="form-select">
                            <option  value="" selected disabled>Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Update</button>
                </div>
            
            </form>
        </div>
    </div>
</div>



@endsection