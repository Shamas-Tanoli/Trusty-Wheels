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
'resources/assets/admin/js/vehicle-add.js'
])
@endsection

@section('content')
<form id="vehicle-add-form">
    <div class="app-ecommerce">


        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 row-gap-4">

            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-0">Add job</h4>

            </div>
            <div class="d-flex align-content-center flex-wrap gap-4">
                <button id="resetbtn" type="reset" class="btn btn-label-secondary waves-effect">Reset</button>
                <button id="uploadButton" type="submit" class="btn btn-primary">Publish Job</button>

            </div>

        </div>

        <div class="row">

            <!-- First column-->
            <div class="col-12 col-lg-12">
                <!-- Product Information -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-tile mb-0"> Information</h5>
                    </div>
                    <div class="card-body">
                       <div class="row">
                        <div class="input mb-6 col-4  ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="model_id">
                                Driver  
                            </label>
                            <select name="vehicle_model_id" id="model_id" data-depend="make_id"
                                data-url="{{ route('select.model') }}"
                                data-depend-url="{{ route('select.make.model') }}" class="select2 form-select"
                                data-placeholder="Select Model">
                            </select>
                        </div>

                        <div class="input mb-6 col-4  ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="model_id">
                                Vehicle  
                            </label>
                            <select name="vehicle_model_id" id="model_id" data-depend="make_id"
                                data-url="{{ route('select.model') }}"
                                data-depend-url="{{ route('select.make.model') }}" class="select2 form-select"
                                data-placeholder="Select Model">
                            </select>
                        </div>

                        <div class="input mb-6 col-4">
                            <label class="form-label" for="ecommerce-product-name">Date</label>
                            <input type="date" class="form-control" id="ecommerce-product-name"
                                placeholder="Short Description" name="short_Description" aria-label="Vehicle title">
                        </div>

                    </div>

                       <div class="input mb-6 col-4  ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="model_id">
                                Passenger    
                            </label>
                            <select name="vehicle_model_id" id="passenger_id" data-depend="make_id"
                                data-url="{{ route('select.model') }}"
                                data-depend-url="{{ route('select.make.model') }}" class="select2 form-select"
                                data-placeholder="Select Model">
                            </select>
                        </div>


                       

                        
                    </div>
                </div>
                <!-- /Product Information -->
                <!-- Media -->
              


                

            </div>
            <!-- /Second column -->

            <!-- Second column -->
           
            <!-- /detail Card -->
        </div>
        <!-- /Second column -->
    </div>
    </div>

</form>

@endsection