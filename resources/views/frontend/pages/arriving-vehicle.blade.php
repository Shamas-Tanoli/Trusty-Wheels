@extends('frontend.layout')
@section('content')
@section('title', 'Vehicle list')
<!-- Breadcromb Area Start -->
<section class="gauto-breadcromb-area section_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcromb-box">
                    <h3>Car Listing</h3>
                    <ul>
                        <li><i class="fa fa-home"></i></li>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>car listing</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcromb Area End -->

<!-- Car Listing Area Start -->
<section class="gauto-car-listing section_70 pt-0">
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-12 p-0">
                <div class="car-list-left">
                    <div class="sidebar-widget">
                        <button id="searchButton" class="gauto-btn custombtn">Find Car</button>
                        <form id="searchForm">
                            <div >
                                <select id="locationSelect" name="location_id">
                                    <option value="">Select Location</option>
                                </select>
                            </div>
                            <div>
                                <select id="makeSelect" name="make_id">
                                    <option value="">Select Make</option>
                                </select>
                            </div>
                            <div>
                                <select id="modelSelect" name="model_id">
                                    <option value="">Select Model</option>
                                </select>
                           </div>
                           

                           <div>
                                <button type="reset" class=" gauto-theme-btn">Reset Filter</button>
                                
                           </div>
                           <div>
                               
                                <button type="submit" class="gauto-theme-btn">Find Car</button>
                           </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="car-listing-right">

                    <div class="car-grid-list">
                        <div id="vehicle-list" class="row">

                            @include('frontend.components.singlevehicle',['vehicles'=>$vehicles])





                        </div>
                        <div id="loader" style="display: none;" class="text-center pt-5">

                            <div class="spinner"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Car Listing Area End -->

@section('page-script')
<script>
    $(document).ready(function () {
        function fetchVehicles(url, data = {}) {
            $('#loader').show();
            $('#vehicle-list, #paginate').hide();

            $.ajax({
                url: url,
                method: "GET",
                data: data,
                success: function (response) {
                    $('#loader').hide();
                        $('#vehicle-list').html(response).show();         
                },
                error: function () {
                    $('#loader').hide();
                    alert("Something went wrong, please try again.");
                }
            });
        }

        $('#searchForm').on('submit', function (e) {
            e.preventDefault();
            fetchVehicles("{{ route('arriving.vehicle') }}", $(this).serialize());
        });

        $(document).on('click', '#paginate .pagination li a', function (e) {
            e.preventDefault();
            let pageUrl = $(this).attr('href');
            fetchVehicles(pageUrl);
        });
    });
</script>
@endsection

@endsection