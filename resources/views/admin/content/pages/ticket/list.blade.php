@extends('admin/layouts/layoutMaster')

@section('title', 'User Management - Crud App')

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
'resources/assets/admin/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/admin/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
'resources/assets/admin/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
'resources/assets/admin/vendor/libs/select2/select2.scss',
'resources/assets/admin/vendor/libs/@form-validation/form-validation.scss',
'resources/assets/admin/vendor/libs/animate-css/animate.scss',
'resources/assets/admin/vendor/libs/sweetalert2/sweetalert2.scss',
'resources/assets/admin/vendor/libs/dropzone/dropzone.scss',
'resources/assets/admin/vendor/libs/flatpickr/flatpickr.scss'
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
'resources/assets/admin/vendor/libs/flatpickr/flatpickr.js',
'resources/assets/admin/vendor/libs/moment/moment.js',
'resources/assets/admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
'resources/assets/admin/vendor/libs/select2/select2.js',
'resources/assets/admin/vendor/libs/@form-validation/popular.js',
'resources/assets/admin/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/admin/vendor/libs/@form-validation/auto-focus.js',
'resources/assets/admin/vendor/libs/cleavejs/cleave.js',
'resources/assets/admin/vendor/libs/cleavejs/cleave-phone.js',
'resources/assets/admin/vendor/libs/sweetalert2/sweetalert2.js',
'resources/assets/admin/vendor/libs/dropzone/dropzone.js',
])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite(['resources/assets/admin/js/ticket.js',
'resources/assets/admin/js/forms-selects.js'])
@endsection

@section('content')




<!-- Users List Table -->
<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between">
        <h5 class="card-title mb-0">Tickets</h5>

        <button class="col-2 btn btn-secondary add-new btn-primary waves-effect waves-light" tabindex="0"
            aria-controls="DataTables_Table_0" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasAddUser"><span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                    class="d-none d-sm-inline-block">Add Ticket</span></span></button>

    </div>


    <div class="card-datatable table-responsive">
        <table class="datatables-users table">
            <thead class="border-top">
                <tr>

                    <th>Serial</th>
                    <th>Booking</th>
                    <th>Amount</th>
                    <th>Reason</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>


    <!-- Offcanvas to add new user -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
        <div class="offcanvas-header border-bottom">
            <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add Ticket</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
            <form id="ticketaddform" onsubmit="return false" class="add-new-user pt-0" id="addNewUserForm">
                <input type="hidden" name="form_mode" id="form_mode" value="add">
                <input type="hidden" name="id" id="ticket_id">
                <div class="mb-6">
                    <label class="form-label" for="booking_id">Booking Vehicle</label>
                    <select name="booking_id" id="booking_id" data-url="{{ route('select.booking') }}"
                        class="select2  form-select form-select-lg" data-placeholder="Select Booked Vehicle">
                    </select>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="amount">Amount</label>
                    <input type="number" id="aamount" class="form-control" placeholder="amount" aria-label="amount"
                        name="amount" />
                </div>
                <div class="mb-6">
                    <label class="form-label" for="reason">Reason</label>
                    <input type="text" id="reason" class="form-control " placeholder="Reason" aria-label="Ticket Reason"
                        name="reason" />
                </div>
                <div class="mb-6">
                    <label class="form-label" for="datetime">Date Time</label>
                    <input type="text" name="date_time" class="form-control flatpickr-date flatpickr-input active"
                        placeholder="Y-M-D H:M" id="flatpickr-datetime" readonly="readonly">
                </div>

                <div class="mb-6">

                    <div class="custom-dropzone needsclick p-0" id="fileupload">
                        <div class="dz-message dz-message-custom needsclick">
                            <p class="h4 needsclick pt-3 mb-2">Drag and drop your File here</p>
                            <p class="h6 text-muted d-block fw-normal mb-2">or</p>
                            <span class="note needsclick btn btn-sm btn-label-primary" id="btnBrowse">Browse
                                File</span>
                        </div>
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>



                    </div>
                </div>




                <button type="submit" class="btn btn-primary me-3 data-submit">Submit</button>
                <button  id="cancelButton" type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Cancel</button>
            </form>
        </div>
    </div>
</div>
@endsection