@extends('frontend.layout')
@section('content')
@section('title', $vehicle->title . ' Details')

<!-- Breadcromb Area Start -->
<section class="gauto-breadcromb-area section_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcromb-box">
                   
                    <ul>
                        <li><i class="fa fa-home"></i></li>
                        
                        <li><a href="">Vehicle</a></li>
                        <li><i class="fa fa-angle-right"></i></li>
                        <li>{{ $vehicle->title }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcromb Area End -->

<!-- Car Booking Area Start -->
<section class="gauto-car-booking section_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="car-booking-image">


                    <div id="lightgallery" class="slider owl-carousel" >
                        @foreach ($vehicle->vehicleImages as $index => $image)
                        <div class="item" data-index="{{ $index }}" >
                            <a href="{{ asset('assets/img/vehicle_images/' . $image->image_url) }}"
                                data-src="{{ asset('assets/img/vehicle_images/' . $image->image_url) }}">
                                <img   data-src="{{ asset('assets/img/vehicle_images/' . $image->image_url) }}"
                                    class="owl-lazy" alt="{{ $vehicle->title }}" />

                            </a>
                        </div>
                        @endforeach
                    </div>

                    <div id="sync2" class="navigation-thumbs owl-carousel">
                        @foreach ($vehicle->vehicleImages as $index => $image)
                        <!-- $index added here -->
                        <div class="item" data-index="{{ $index }}">
                            <img data-src="{{ asset('assets/img/vehicle_images/' . $image->image_url) }}"
                                class="owl-lazy" alt="{{ $vehicle->title }}" />
                        </div>
                        @endforeach
                    </div>


                </div>
            </div>
            <div class="col-lg-6">
                <div class="car-booking-right">
                    <h3>{{ $vehicle->title }}</h3>
                    <div class="price-rating">
                        <div class="price-rent">
                            <h4>€{{ $vehicle->rent }}</h4>
                        </div>

                    </div>
                    <p> {{ $vehicle->description }}</p>
                    <div class="car-features clearfix">

                        <ul class="vehicle-info-list">
                            <li><i class="fa fa-car"></i> Make: {{ $vehicle->make->name }}</li>
                            <li><i class="fa fa-cogs"></i> Transmission: {{ $vehicle->transmission }}</li>
                            <li><i class="fa fa-tachometer"></i> Doors: {{ $vehicle->door }}</li>
                        </ul>
                        <ul class="vehicle-info-list">
                            <li><i class="fa fa-eye"></i> Model: {{ $vehicle->vehiclemodel->name }}</li>
                            <li><i class="fa fa-tint"></i> Fuel Type: {{ $vehicle->fuel_type }}</li>
                            <li><i class="fa fa-users"></i> Seats: {{ $vehicle->seats }}</li>
                        </ul>
                        <ul class="vehicle-info-list">
                            <li><i class="fa fa-calendar"></i> Year: {{ $vehicle->year }}</li>
                            <li><i class="fa fa-road"></i> Mileage: {{ $vehicle->mileage }} km</li>
                            <li>
                                <i class="fa fa-info-circle"></i> Status:
                                @if($vehicle->status === 'booked')
                                <span class="badge badge-danger">Booked</span>
                                @else
                                <span class="badge badge-success">Available</span>
                                @endif
                            </li>
                        </ul>



                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="car-booking-right">

                    <div class="car-features clearfix">

                        <ul class="detailistamen">
                            <h3 class="col-12 p-0 mb-0">Extra Feature</h3>
                            @foreach ($vehicle->vehicleAmenities as $amenities )
                            <li><i class="fa fa-check"></i> {{ ucwords($amenities->name) }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mt-3">
                <div class="booking-form-left">
                   
                    <div class="single-booking">
                        <h3 style="margin-bottom: 0">Vehicle Enquiry Now</h3>
                        <form id="reservation-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 d-none">
                                    <p>
                                        <select name="vehicle_id">
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->title }}</option>
                                        </select>
                                    </p>
                                </div>
                                <div class="col-md-6 flatpickersham">
                                    <p>
                                        <input id="" class="" name="name"
                                            placeholder="Enter your full name " type="text">
                                    </p>
                                </div>
                                <div class="col-md-6 flatpickersham">
                                    <p>
                                        <input id="" class="" name="email"
                                            placeholder="Enter your Email " type="email">
                                    </p>
                                </div>
                                <div class="col-md-6 flatpickersham">
                                    <p>
                                        <input id="" class="" name="number"
                                            placeholder="Enter your phone number " type="number">
                                    </p>
                                </div>
                                <div class="col-md-6 flatpickersham">
                                    <p>
                                        <input id="" class="" name="address"
                                            placeholder="Enter your Address " type="text">
                                    </p>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-6">
                                    <div class="action-btn">
                                        <button type="submit" class="gauto-btn color">Enquiry Now!</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                   

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Car Booking Area End -->

@section('page-script')

@vite([
'resources/assets/admin/vendor/libs/flatpickr/flatpickr.scss',
'resources/assets/admin/vendor/libs/flatpickr/flatpickr.js'])


<script src="{{ asset('assets/frontend/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/lightgallery.js') }}"></script>
<script>
    $(document).ready(function(){
      
    var sync1 = $('.slider');
      var sync2 = $('.navigation-thumbs');

      var thumbnailItemClass = '.owl-item';

      var slides = sync1
        .owlCarousel({
          video: true,
          startPosition: 0,
          items: 1,
          loop: false,
          margin: 10,
          autoplay: true,
          autoplayTimeout: 3000,
          autoplayHoverPause: false,
          nav: false,
          dots: true,
          lazyLoad: true
        })
        .on('changed.owl.carousel', syncPosition);

      function syncPosition(el) {
        $owl_slider = $(this).data('owl.carousel');
        var loop = $owl_slider.options.loop;
        

        if (loop) {
          var count = el.item.count - 1;
          var current = Math.round(el.item.index - el.item.count / 2 - 0.5);
          if (current < 0) {
            current = count;
          }
          if (current > count) {
            current = 0;
          }
        } else {
          var current = el.item.index;
        }

        var owl_thumbnail = sync2.data('owl.carousel');
        var itemClass = '.' + owl_thumbnail.options.itemClass;

        var thumbnailCurrentItem = sync2.find(itemClass).removeClass('synced').eq(current);

        thumbnailCurrentItem.addClass('synced');

        if (!thumbnailCurrentItem.hasClass('active')) {
          var duration = 300;
          sync2.trigger('to.owl.carousel', [current, duration, true]);
        }
      }

      var thumbs = sync2
        .owlCarousel({
          startPosition: 0,
          items: 4,
          loop: false,
          margin: 10,
          autoplay: false,
          nav: false,
          dots: false,
          lazyLoad: true,
          onInitialized: function (e) {
            var thumbnailCurrentItem = $(e.target).find(thumbnailItemClass).eq(this._current);
            thumbnailCurrentItem.addClass('synced');
          }
        })
        .on('click', thumbnailItemClass, function (e) {
          e.preventDefault();
          var duration = 300;
          var itemIndex = $(e.target).parents(thumbnailItemClass).index();
          sync1.trigger('to.owl.carousel', [itemIndex, duration, true]);
        })
        .on('changed.owl.carousel', function (el) {
          var number = el.item.index;
          $owl_slider = sync1.data('owl.carousel');
          $owl_slider.to(number, 100, true);
        });
       
        $('#lightgallery').lightGallery({
      selector: '.item a',
      download: true,
      fullScreen: true,
      showCloseIcon: true,subHtmlSelectorRelative: false
      
    });

       
});
    
</script>
@endsection


@endsection