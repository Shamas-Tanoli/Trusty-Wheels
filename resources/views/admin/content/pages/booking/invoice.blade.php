@extends('admin/layouts/layoutMaster')

@section('title', 'Add - Invoice')

@section('vendor-style')
@vite('resources/assets/admin/vendor/libs/flatpickr/flatpickr.scss')
@endsection

@section('page-style')
@vite('resources/assets/admin/vendor/scss/pages/app-invoice.scss')
@endsection

@section('vendor-script')
@vite([
'resources/assets/admin/vendor/libs/flatpickr/flatpickr.js',
'resources/assets/admin/vendor/libs/cleavejs/cleave.js',
'resources/assets/admin/vendor/libs/cleavejs/cleave-phone.js',
'resources/assets/admin/vendor/libs/jquery-repeater/jquery-repeater.js'
])
@endsection

@section('page-script')
@vite([
'resources/assets/admin/js/booking-invoice.js',
])
@endsection

@section('content')
<form id="invoiceformcustom" onsubmit="return false">
  <div class="row invoice-add">
    <!-- Invoice Add-->

    <div class="col-lg-9 col-12 mb-lg-0 mb-6">
      <div class="card invoice-preview-card p-sm-12 p-6">
        <div class="card-body  rounded p-0">
          <div class="d-flex flex-wrap flex-column flex-sm-row justify-content-between text-heading">
            <div class="mb-md-0 mb-6">
              <div class="d-flex svg-illustration mb-6 gap-2 align-items-center">
                <div class="app-brand-logo demo">@include('admin._partials.macros',["height"=>100])</div>
                <span class="app-brand-text fw-bold fs-4 ms-50">
                  {{ config('variables.templateName') }}
                </span>
              </div>

              @php
              $statusColors = [
              'Pending' => 'warning',
              'Booked' => 'success',
              'Completed' => 'primary',
              'Canceled' => 'danger',
              ];
              @endphp

              <p class="mb-2">Status:
                <span class="badge px-2 bg-label-{{ $statusColors[$booking->status] ?? 'secondary' }}">
                  {{ ucfirst($booking->status) }}
                </span>
              </p>


            </div>
            <div class="col-md-5 col-8 pe-0 ps-0 ps-md-2">
              <dl class="row mb-0">
                <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-end">
                  <span class="h5 text-capitalize mb-0 text-nowrap">Booking</span>
                </dt>
                <dd class="col-sm-7">
                  <div class="input-group input-group-merge disabled">
                    <span class="input-group-text">#</span>
                    <input type="text" class="form-control" readonly name="id" value="{{ $booking->id }}"
                      id="booking_id" />
                  </div>
                </dd>
                <dt class="col-sm-5 mb-2 d-md-flex align-items-center justify-content-end">
                  <span class="fw-normal">Start Date:</span>
                </dt>
                <dd class="col-sm-7">
                  <input type="text" class="form-control invoice-date" readonly value="{{ $booking->start_date}}"
                    id="startdate" placeholder="DD-MM-YYYY" />
                </dd>
                <dt class="col-sm-5 d-md-flex align-items-center justify-content-end">
                  <span class="fw-normal">End Date:</span>
                </dt>
                <dd class="col-sm-7 mb-0">
                  <input type="text" name="end_date" id="end_date"
                    class="form-control flatpickr-date flatpickr-input active" placeholder="YYYY-MM-DD"
                    id="flatpickr-date" readonly="readonly">
                </dd>
              </dl>
            </div>
          </div>
        </div>

        <div class="card-body pb-0 pt-0 px-0">

          <div class="row">
            <div class="col-md-6 col-sm-5 col-12 mb-sm-0 mb-6">
              <h6 class="mb-0">Driver: {{ ucfirst($booking->driver->name) }}</h6>
              <h6>Vehicle: {{ ucfirst($booking->vehicle->title) }}</h6>
            </div>
          </div>
        </div>
        <hr class="mt-0 mb-6">
        <div class="card-body pt-0 px-0 pb-0">
          <div class="source-item">
            <div class="mb-4" data-repeater-list="group-a">
              <div class="repeater-wrapper pt-0 pt-md-9" data-repeater-item>
                <div class="d-flex border rounded position-relative pe-0">
                  <div class="row w-100 p-6">
                    <div class="col-md-3 col-12 mb-md-0 mb-4">
                      <p class="h6 repeater-title">Rent</p>
                      <input name="rent_amount" readonly id="totalrent" type="text" class="form-control invoice-item-price mb-5"
                        placeholder="N/A" />
                    </div>
                    <div class="col-md-3 col-12 mb-md-0 mb-4">
                      <p class="h6 repeater-title">Tickets</p>
                      <input id="ticketamount" readonly type="text" class="form-control invoice-item-price mb-5"
                        value="{{ $booking->ticket_amount }}" placeholder="1.12" />
                    </div>
                    <div class="col-md-3 col-12 mb-md-0 mb-4">
                      <p class="h6 repeater-title">Discount</p>
                      <input name="discount_amount" type="number" id="discount" class="form-control invoice-item-qty" placeholder="0" />
                    </div>
                    <div class="col-md-3 col-12 mb-md-0 mb-4">
                      <p class="h6 repeater-title">Paid</p>
                      <input type="number" name="paid_amount" id="paid_amount" class="form-control invoice-item-qty" placeholder="0" />
                    </div>

                  </div>

                </div>
              </div>
            </div>

          </div>
        </div>



        <div class="card-body d-flex pt-0 justify-content-between px-0">
          <div class="col-md-6 mb-md-0 mb-4">
            <div class="col-8 d-flex align-items-center mb-2">
              <label for="mileage" class="me-2 fw-medium text-heading">Milleage:</label>
              <input name="old_milleage" value="{{ $booking->vehicle->mileage }}" type="number"   class="form-control" id="mileage" placeholder="Milleage">
             
            </div>

            <div class="col-8 d-flex align-items-center gap-3 mb-2">
              <label for="mileage" class="me-2 fw-medium text-heading">Return:</label>
              <input  type="number" name="mileage" class="form-control" id="mileage" placeholder="Milleage">
            </div>
           
          
          
          </div>



          <div class="invoice-calculations">

            <div class="d-flex justify-content-between mb-2">
              <span class="w-px-100">Total days:</span>
              <span id="totaldays" class="fw-medium text-heading">N/A</span>
            </div>


            <div class="d-flex justify-content-between mb-2">
              <span class="w-px-100">Per day:</span>
              <span class="fw-medium text-heading">{{ $booking->vehicle->rent }}</span>
            </div>

            <div class="d-flex justify-content-between mb-2">
              <span class="w-px-100">Tickets:</span>
              <span class="fw-medium text-heading">{{ $booking->ticket_amount }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="w-px-100">Rent:</span>
              <span id='rent1' class="fw-medium text-heading">N/A</span>
            </div>



            <div class="d-flex justify-content-between mb-2">
              <span class="w-px-100">Discount:</span>
              <span id="discountcheck" class="fw-medium text-heading">0</span>
            </div>



            <hr />
            <div class="d-flex justify-content-between">
              <span class="w-px-100">Total:</span>
              <span id="grandtotal" class="fw-medium text-heading">£</span>
            </div>

            <div class="d-flex justify-content-between">
              <span class="w-px-100">Paid:</span>
              <span id="paidcheck" class="fw-medium text-heading">0</span>
            </div>

            <div class="d-flex justify-content-between">
              <span class="w-px-100">Remaining:</span>
              <input type="hidden" name="remaining_amount" id="remaining_amount">
              <span id="remaning" class="fw-medium text-heading">£</span>
            </div>

          </div>



        </div>
        <hr class="my-0">
        <div class="card-body px-0">

          <div class="row">
            <div class="col-12">
              <div>
                <label for="note" class="text-heading mb-1 fw-medium">Note:</label>
                <textarea readonly class="form-control resize-none" rows="2" id="note"
                  placeholder="Invoice note">It was a pleasure working with you. We hope you will keep us in mind for future Drive.       Thank You!</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /Invoice Add-->

    <!-- Invoice Actions -->
    <div class="col-lg-3 col-12 invoice-actions">
      <div class="card mb-6">
        <div class="card-body">
          <button type="submit" class="btn btn-primary d-grid w-100 mb-4" data-bs-toggle="offcanvas"
            data-bs-target="#sendInvoiceOffcanvas">
            <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                class="ti ti-send ti-xs me-2"></i>Save</span>
          </button>

        </div>
      </div>
      <div>




      </div>
    </div>


    <!-- /Invoice Actions -->
  </div>

</form>

<!-- /Offcanvas -->
@endsection