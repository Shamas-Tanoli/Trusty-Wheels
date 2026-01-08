@extends('admin/layouts/layoutMaster')

@section('title', 'Edit-Vehicle')

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
'resources/assets/admin/js/vehicle-edit.js'
])
@endsection

@section('content')
<form id="vehicle-add-form">
    <div class="app-ecommerce">


        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 row-gap-4">

            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-0">Edit Vehical</h4>

            </div>
            <div class="d-flex align-content-center flex-wrap gap-4">

                <button id="uploadButton" type="submit" class="btn btn-primary">Update Vehicle</button>

            </div>

        </div>

        <div class="row">

            <!-- First column-->
            <div class="col-12 col-lg-8">
                <!-- Product Information -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-tile mb-0"> Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="input mb-6">
                            <input type="hidden" name="id" id="edit-id" value="{{$vehicle->id }}">
                            <label class="form-label" for="ecommerce-product-name">Title</label>
                            <input type="text" class="form-control" id="ecommerce-product-name"
                                placeholder="Vehicle title" name="title" value="{{$vehicle->title}}"
                                aria-label="Vehicle title">
                        </div>

                        <div class="input mb-6">
                           
                            <label class="form-label" for="short_Description">Short Description</label>
                            <input type="text" class="form-control" id="short_Description"
                                placeholder="Short Description" name="short_Description" value="{{$vehicle->short_Description}}"
                                aria-label="Vehicle title">
                        </div>



                        <div class="row mb-6">

                            <div class="input col">
                                <label class="form-label" for="vin-number">Vin Number</label>
                                <input type="text" class="form-control" id="vin-number" placeholder="Vin Number"
                                    name="vin_nbr" value="{{$vehicle->vin_nbr}}" aria-label="Vin Number">
                            </div>

                            <div class=" input col">
                                <label class="form-label" for="license-plate-number">License Plate Number</label>
                                <input type="text" class="form-control" id="license-plate-number"
                                    placeholder="License Plate Number" name="lic_plate_nbr"
                                    aria-label="License Plate Number" value="{{$vehicle->lic_plate_nbr}}">
                            </div>
                        </div>


                        <!-- Description -->
                        <div>
                            <label class="mb-1">Description</label>
                            <div class=" form-control p-0">
                                <div class="comment-toolbar border-0 border-bottom">
                                    <div class="d-flex justify-content-start">

                                    </div>
                                </div>
                                <div class="comment-editor border-0 pb-6" id="ecommerce-category-description">
                                </div>

                            </div>
                        </div>

                        <div class=" input col">
                            <textarea name="description" id="description"
                                style="display:none;">{{$vehicle->description }}</textarea>
                        </div>
                    </div>
                </div>
                <!-- /Product Information -->
                <!-- Media -->
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">Vehicle Images</h5>

                    </div>
                    <div class="card-body">
                        <div class="dropzone needsclick p-0" id="dropzone-basic" data-images='@json($vehicleImages)'>
                            <div class="dz-message needsclick">
                                <p class="h4 needsclick pt-3 mb-2">Drag and drop your s here</p>
                                <p class="h6 text-muted d-block fw-normal mb-2">or</p>
                                <span class="note needsclick btn btn-sm btn-label-primary" id="btnBrowse">Browse
                                    images</span>
                            </div>
                            <div class="fallback">
                                <input name="file" type="file" />
                            </div>

                            <div id="sortableContainer" class="dz-preview-container"></div>

                        </div>
                        <div id="dropzone-error" class="text-danger mt-2"></div>

                    </div>
                </div>


                <!-- /Media -->
                <!-- amenities -->
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Amenities</h5>
                        <div class="d-flex align-items-end">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="selectAll" />
                                <label class="form-check-label" for="selectAll">
                                    Select All
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input row">
                            @forelse($amenities as $amenity)
                            <div class="mb-6 col-4">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" name="amenities[]" value="{{ $amenity->id }}"
                                        type="checkbox" id="{{ $amenity->name }}" {{
                                        $vehicle->vehicleAmenities->contains($amenity->id) ? 'checked' : '' }} />
                                    <label class="form-check-label" for="{{ $amenity->name }}">
                                        {{ $amenity->name }}
                                    </label>
                                </div>
                            </div>
                            @empty
                            <span class="p-4 note needsclick btn btn-sm btn-label-primary waves-effect">
                                No amenities available. Please add amenities first.
                            </span>
                            @endforelse

                        </div>
                    </div>
                </div>
                <!-- /Variants -->

            </div>
            <!-- /Second column -->

            <!-- Second column -->
            <div class="col-12 col-lg-4">
                <!-- Pricing Card -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Price & Status</h5>
                    </div>
                    <div class="card-body">
                        <!-- Base Price -->

                        <!-- Discounted Price -->
                        <div class="input mb-6">
                            <label class="form-label" for="rent-price">Price</label>
                            <input type="number" class="form-control" id="rent-price" placeholder=" Price" name="rent"
                                aria-label="Rent Amount" value="{{  $vehicle->rent }}">
                        </div>
                        <div class="input mb-6">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-control" id="status" name="status" aria-label="Vehicle Status">
                                <option value="available" {{ $vehicle->status === 'available' ? 'selected' : ''
                                    }}>Available</option>
                                <option value="sold" {{ $vehicle->status === 'sold' ? 'selected' : '' }}>Sold</option>
                                <option value="arriving_soon" {{ $vehicle->status === 'arriving_soon' ? 'selected' : '' }}>Arriving Soon</option>
                            </select>
                        </div>

                    </div>
                </div>
                <!-- /Pricing Card -->
                <!-- detail Card -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail</h5>
                    </div>
                    <div class="card-body">
                        <!-- make -->



                        <div class="input mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="make_id">Make</label>
                            <select id="make_id" class="select2 form-select" data-url="{{ route('select.make') }}"
                                name="make_id" data-placeholder="Select Make">
                                <option value="{{ $vehicle->make_id }}" selected>{{ $vehicle->make->name }}</option>

                            </select>
                        </div>
                        <!-- model -->

                        <div class="input mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="model_id">
                                Model <small>(Select a make to view models)</small>
                            </label>
                            <select name="vehicle_model_id" id="model_id" data-depend="make_id"
                                data-url="{{ route('select.model') }}"
                                data-depend-url="{{ route('select.make.model') }}" class="select2 form-select"
                                data-placeholder="Select Model">

                                <option value="{{ $vehicle->vehicle_model_id }}" selected>
                                    {{ $vehicle->vehicleModel->name }}
                                </option>

                            </select>
                        </div>

                        <!-- vehicle type-->
                        <div class="input mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="vehicle-types">Vehicle Type
                            </label>
                            <select name="vehicle_type_id" id="vehicle-types" class="select2 form-select"
                                data-placeholder="Select Vehicle Type" data-url="{{ route('select.vehicle.types') }}">

                                <option value="{{ $vehicle->vehicle_type_id }}" selected>
                                    {{ $vehicle->vehicleType->name }}
                                </option>
                            </select>
                        </div>
                        <!-- Location -->
                        <div class="input mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="location">Location
                            </label>
                            <select id="location" name="location_id" class="select2 form-select"
                                data-placeholder="Select Location" data-url="{{ route('select.location') }}">
                                <option value="{{ $vehicle->location_id }}" selected>
                                    {{ $vehicle->location->name }}
                                </option>
                            </select>
                        </div>

                    </div>
                </div>
                <!-- /detail Card -->
                <!-- specification Card -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Specification</h5>
                    </div>
                    <div class="card-body">

                        {{-- year --}}
                        <div class="input mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="year">Model Year</label>
                            <input type="number" class="form-control" id="year" value="{{ $vehicle->year }}"
                                placeholder="Model Year" name="year" aria-label="Vehicle Model Year">
                        </div>

                        {{-- milleage --}}
                        <div class="input mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="seats">Milleage</label>
                            <input type="number" value="{{ $vehicle->mileage }}" class="form-control" id="mileage"
                                placeholder="mileage" name="mileage" aria-label="Vehicle mileage">
                        </div>


                        <!-- Transmission -->
                        <div class="input mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="transmission">Transmission</label>
                            <input type="text" class="form-control" id="transmission" placeholder="Transmission"
                                name="transmission" value="{{ $vehicle->transmission }}"
                                aria-label="Vehicle Transmission">
                        </div>

                        <!-- fuel type -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="input mb-6 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="fuel_type">
                                    Fuel Type
                                </label>
                                <input type="text" value="{{ $vehicle->fuel_type }}" class="form-control" id="fuel_type"
                                    placeholder="Fuel Type" name="fuel_type" aria-label="Vehicle Fuel Type ">
                            </div>
                        </div>
                        <!-- door -->
                        <div class="input mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="door">Door
                            </label>
                            <input type="number" value="{{ $vehicle->door }}" class="form-control" id="door"
                                placeholder="door" name="door" aria-label="Vehicle door ">
                        </div>
                        {{--seats --}}
                        <div class="input mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="seats">Seats</label>
                            <input type="number" value="{{ $vehicle->seats }}" class="form-control" id="seats"
                                placeholder="Seats" name="seats" aria-label="Vehicle seats">
                        </div>


                    </div>
                    <!-- Location -->


                </div>
            </div>
            <!-- /detail Card -->
        </div>
        <!-- /Second column -->
    </div>
    </div>

</form>

@endsection