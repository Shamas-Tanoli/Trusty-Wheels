@extends('admin/layouts/layoutMaster')

@section('title', 'Add-Vehicle')

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
'resources/assets/admin/js/jobadd.js'
])
@endsection

@section('content')
<form id="jobaddform">
    <div class="app-ecommerce">


        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 row-gap-4">

            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-0">Add job</h4>

            </div>
            <div class="d-flex align-content-center flex-wrap gap-4">
                <button id="uploadButton" type="submit" class="btn btn-primary">Publish Job</button>

            </div>

        </div>

        <div class="row">

    <div class="col-12 col-lg-12">
        <div class="card mb-6">
            <div class="card-header">
                <h5 class="card-tile mb-0">Information</h5>
            </div>
            <div class="card-body">

                <div class="row">
                    <!-- Driver -->
                    <div class="input mb-6 col-4 ecommerce-select2-dropdown">
                        <label class="form-label mb-1" for="driver_id">Driver</label>
                        <select name="driver_id" id="driver_id" data-depend="make_id"
                                data-url="{{ route('select.driver') }}"
                                class="select2 form-select"
                                data-placeholder="Select Driver">
                        </select>
                    </div>

                    <!-- Vehicle -->
                    <div class="input mb-6 col-4 ecommerce-select2-dropdown">
                        <label class="form-label mb-1" for="vehicle_id">Vehicle</label>
                        <select name="vehicle_id" id="vehicle_id"
                                data-url="{{ route('select.servicevehicle') }}"
                                class="select2 form-select"
                                data-placeholder="Select Vehicle">
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="input mb-6 col-4">
                        <label class="form-label" for="ecommerce-product-name">Date</label>
                        <input type="datetime-local" class="form-control" id="ecommerce-product-name"
                               name="date" aria-label="date">
                    </div>
                </div>

                <!-- Passenger Section -->
                <div id="passengers-wrapper">
                    <div class="row passenger-row mb-3">
                        <div class="input  col-4 ecommerce-select2-dropdown">
                            <label class="form-label mb-1">Passenger</label>
                            <select name="passenger_ids[]" class="select2 form-select passenger-select"
                                    data-url="{{ route('select.passenger') }}"
                                    data-placeholder="Select Passenger">
                            </select>
                        </div>
                        <div style="margin-bottom: 5px;" class="col-2 d-flex align-items-end">
                            <button type="button" class="btn btn-success add-passenger-btn">Add</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and Select2 if not already included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize first select2
    $('.passenger-select').select2({
        placeholder: "Select Passenger",
        ajax: {
            url: function () {
                return $(this).data('url');
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.map(function(item) {
                        return {id: item.id, text: item.name};
                    })
                };
            },
            cache: true
        }
    });

    // Add passenger row
    $(document).on('click', '.add-passenger-btn', function() {
        var passengerRow = `
            <div class="row passenger-row mb-3">
                <div class="input  col-4 ecommerce-select2-dropdown">
                    <label class="form-label mb-1">Passenger</label>
                    <select name="passenger_ids[]" class="select2 form-select passenger-select"
                            data-url="{{ route('select.passenger') }}"
                            data-placeholder="Select Passenger">
                    </select>
                </div>
                <div class="col-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-passenger-btn">Remove</button>
                </div>
            </div>
        `;
        $('#passengers-wrapper').append(passengerRow);

        // Initialize select2 for the new select
        $('#passengers-wrapper .passenger-row:last .passenger-select').select2({
            placeholder: "Select Passenger",
            ajax: {
                url: function () {
                    return $(this).data('url');
                },
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.map(function(item) {
                            return {id: item.id, text: item.name};
                        })
                    };
                },
                cache: true
            }
        });
    });

    // Remove passenger row
    $(document).on('click', '.remove-passenger-btn', function() {
        $(this).closest('.passenger-row').remove();
    });
});
</script>

        <!-- /Second column -->
    </div>
    </div>

</form>

@endsection