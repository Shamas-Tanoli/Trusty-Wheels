@extends('frontend.layout')
@section('content')
@section('title', 'Home')
<!-- Slider Area Start -->
<section class="gauto-slider-area fix">
   <div class="gauto-slide owl-carousel">
      <div class="gauto-main-slide slide-item-1">
         <div class="gauto-main-caption" style="background:none;">
            <div class="gauto-caption-cell">

            </div>
         </div>
      </div>
      <div class="gauto-main-slide slide-item-2">
         <div class="gauto-main-caption" style="background:none;">
            <div class="gauto-caption-cell">

            </div>
         </div>
      </div>
   </div>
</section>
<!-- Slider Area End -->

<!-- Find Area Start -->
<section class="gauto-find-area">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="find-box col-md-12">
               <div class="row row ">
                  <div class="col-md-4">
                     <div class="find-text">
                        <h3>Search Your Best Cars here.</h3>
                     </div>
                  </div>
                  <div class="col-md-8">
                     <div class="find-form">
                        <form action="" method="GET" id="searchhomepage">
                           <div class="row">
                              <div class="col-md-4">
                                 <p>
                                    <select id="locationSelect" name="location_id">
                                       <option value="">Select Location</option>
                                    </select>
                                 </p>
                              </div>
                              <div class="col-md-4">
                                 <p>
                                    <select id="makeSelect" name="make_id">
                                       <option value="">Select Make</option>
                                    </select>
                                 </p>
                              </div>
                              <div class="col-md-4">
                                 <p>
                                    <select id="modelSelect" name="model_id">
                                       <option value="">Select Model</option>
                                    </select>
                                 </p>
                              </div>
                           </div>
                           <div class="row justify-content-center">
                              <div class="col-md-4">
                                 <p>
                                    <button type="reset" class="gauto-theme-btn">Reset Filter</button>
                                 </p>
                              </div>
                              <div class="col-md-4">
                                 <p>
                                    <button type="submit" class="gauto-theme-btn">Find Car</button>
                                 </p>
                              </div>
                           </div>
                        </form>

                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- Find Area End -->

<!-- About Area Start -->
<section class="gauto-about-area section_70">
   <div class="container">
      <div class="row">
         <div class="col-lg-6">
            <div class="about-left">
               <h4>about us</h4>
               <h2>Welcome to HISPEED Car Sales</h2>
               <h5>The Easiest Way to Find Your Next Car</h5>
               <br>
               <p>We are your premier used cars dealer in the east midlands. Your trusted car dealership, specialising
                  in importing top-quality vehicles from japan. We pride ourselves on offering a diverse selection of
                  reliable and stylish cars to suits every need and budget. Our commitment to excellence and customer
                  satisfaction ensures a smooth and enjoyable car buying experience. At HISPEED CAR SALES we bring 30
                  years of expertise in sourcing the finest vehicles from Japanese auctions directly to east midlands
                  and across UK. We go the extra mile to ensure your peace of mind with every purchase, We sell our each
                  vehicle with 12 months warranty and 12 months MOT. </p>
               <div class="about-list">
                  <ul>
                     <li><i class="fa fa-check"></i>We are a trusted name</li>
                     <li><i class="fa fa-check"></i>we deal in have all brands</li>
                     <li><i class="fa fa-check"></i>have a larger stock of vehicles</li>
                     <li><i class="fa fa-check"></i>we are at worldwide locations</li>
                  </ul>
               </div>
               {{-- <div class="about-signature">
                  <div class="signature-left">

                     <img src="{{ asset('assets/frontend/img/signature.png') }}" alt="signature" />
                  </div>
                  <div class="signature-right">
                     <h3>Robertho Garcia</h3>
                     <p>President</p>
                  </div>
               </div> --}}
            </div>
         </div>
         <div class="col-lg-6">
            <div class="about-right">
               <img src="{{ asset("assets/frontend/img/about.png")}}" alt=" car" />
            </div>
         </div>
      </div>
   </div>
</section>
<!-- About Area End -->


<!-- Service Area Start -->
<section class="gauto-service-area section_70" style=" overflow: hidden;">
   <div class="">
      <div class="row">
         <div class="col-md-12">
            <div class="site-heading">
               <h4>see our</h4>
               <h2>Latest Vehicle</h2>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="service-slider owl-carousel">
               @forelse ($vehicles as $vehicle)
               <div class="">
                  <div class="single-offers">
                     <div class="offer-image">
                        <a href="{{ route('vehicle.detail',['id'=>$vehicle->id ]) }}">
                           <img
                              src="{{ asset('assets/img/vehicle_images/' . $vehicle->vehicleImages->first()->image_url) }}"
                              alt="{{ $vehicle->title }}" />
                        </a>
                     </div>
                     <div class="offer-text">
                        <h3 style="margin-bottom: 0"> {{ $vehicle->year }} {{
                           \Illuminate\Support\Str::limit($vehicle->title, 35) }} </h3>
                        <p>{{ \Illuminate\Support\Str::limit($vehicle->short_Description, 70) }}</p>


                        <ul class="card_ul">
                           <li><i class="fa fa-car"></i> {{ $vehicle->make->name }}</li>
                           <li><i class="fa fa-cogs"></i> {{ $vehicle->transmission }}</li>

                           <li><i class="fa fa-eye"></i> {{ $vehicle->vehiclemodel->name }}</li>
                           <li><i class="fa fa-tint"></i> {{ $vehicle->fuel_type }}</li>


                           <li><i class="fa fa-road"></i> {{ $vehicle->mileage }} Milles</li>
                           {{-- <li>
                              <i class="fa fa-info-circle"></i> Status:
                              @if($vehicle->status === 'booked')
                              <span class="badge badge-danger">Booked</span>
                              @else
                              <span class="badge badge-success">Available</span>
                              @endif
                           </li> --}}
                        </ul>
                        <div class="offer-action">
                           {{-- <a href="{{ route('vehicle.detail',['id'=>$vehicle->id ]) }}" class="offer-btn-1">{{
                              $vehicle->status }}</a> --}}
                           <a style="font-size:12px" href="{{ route('vehicle.detail',['id'=>$vehicle->id ]) }}"
                              class="offer-btn-2">£{{ number_format($vehicle->rent, 0) }} - Details </a>
                        </div>
                     </div>
                  </div>
               </div>
               @empty
               <div class="w-100 text-center py-5">
                  <h3>No Vehicles Found</h3>
               </div>
               @endforelse
            </div>
         </div>
      </div>
   </div>
</section>
<!-- Service Area End -->


<!-- Promo Area Start -->
<section class="gauto-promo-area">
   <div class="container">
      <div class="row">
         <div class="col-md-6">
            <div class="promo-box-left">
               <img src="{{ asset('assets/frontend/img/toyota-offer-2.png') }}" alt="promo car" />
            </div>
         </div>
         <div class="col-md-6">
            <div class="promo-box-right">
               <h3>Find your perfect car with trusted deals and unbeatable prices</h3>
               <a href="javascript:void(0)" class="gauto-btn">View Listing</a>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- Promo Area End -->


<!-- Offers Area Start -->
<section class="gauto-offers-area section_70">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="site-heading">
               <h4>Come with</h4>
               <h2> Arriving Soon</h2>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="offer-tabs">
               <ul class="nav nav-tabs" id="offerTab" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab"
                        aria-controls="all" aria-selected="true">All Makes</a>
                  </li>

                  @foreach($makes as $make)
                  <li class="nav-item">
                     <a class="nav-link" id="{{ strtolower($make) }}-tab" data-toggle="tab"
                        href="#{{ strtolower($make) }}" role="tab" aria-controls="{{ strtolower($make) }}"
                        aria-selected="false">
                        {{ ucfirst($make) }}
                     </a>
                  </li>
                  @endforeach

               </ul>
               <div class="tab-content" id="offerTabContent">
                  <!-- All Tab Start -->
                  <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                     <div class="row">
                        @forelse ($arrivingVehicles as $vehicle)
                        <div class="col-md-4 ">
                           <div class="single-offers">
                              <div class="offer-image">
                                 <a href="{{ route('vehicle.detail',['id'=>$vehicle->id ]) }}">
                                    <img
                                       src="{{ asset('assets/img/vehicle_images/' . $vehicle->vehicleImages->first()->image_url) }}"
                                       alt="{{ $vehicle->title }}" />
                                 </a>
                              </div>
                              <div class="offer-text">
                                 <h3 style="margin-bottom: 0"> {{ $vehicle->year }} {{
                                    \Illuminate\Support\Str::limit($vehicle->title, 35) }} </h3>
                                 <p>{{ \Illuminate\Support\Str::limit($vehicle->short_Description, 70) }}</p>
                                 <ul class="card_ul">
                                    <li><i class="fa fa-car"></i> {{ $vehicle->make->name }}</li>
                                    <li><i class="fa fa-cogs"></i> {{ $vehicle->transmission }}</li>

                                    <li><i class="fa fa-eye"></i> {{ $vehicle->vehiclemodel->name }}</li>
                                    <li><i class="fa fa-tint"></i> {{ $vehicle->fuel_type }}</li>
                                    <li><i class="fa fa-dashboard"></i>{{$vehicle->mileage}} Milles</li>



                                 </ul>
                                 <div class="offer-action">

                                    <a href="{{ route('vehicle.detail',['id'=>$vehicle->id ]) }}"
                                       class="offer-btn-2">£{{ number_format($vehicle->rent, 0) }} - Details </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @empty
                        <div class="w-100 text-center py-5">
                           <h3>No Vehicles Found</h3>
                        </div>
                        @endforelse
                     </div>
                  </div>
                  @foreach($makes as $make)
                  <div class="tab-pane fade" id="{{ strtolower($make) }}" role="tabpanel"
                     aria-labelledby="{{ strtolower($make) }}-tab">
                     <div class="row">
                        @php
                        $filteredVehicles = $arrivingVehicles->filter(fn($vehicle) => strtolower($vehicle->make->name)
                        === strtolower($make));
                        @endphp

                        @forelse($filteredVehicles as $vehicle)
                        <div class="col-md-4 ">
                           <div class="single-offers">
                              <div class="offer-image">
                                 <a href="{{ route('vehicle.detail',['id'=>$vehicle->id ]) }}">
                                    <img
                                       src="{{ asset('assets/img/vehicle_images/' . $vehicle->vehicleImages->first()->image_url) }}"
                                       alt="{{ $vehicle->title }}" />
                                 </a>
                              </div>
                              <div class="offer-text">
                                 <h3 style="margin-bottom: 0"> {{ $vehicle->year }} {{
                                    \Illuminate\Support\Str::limit($vehicle->title, 35) }} </h3>
                                 <p>{{ \Illuminate\Support\Str::limit($vehicle->short_Description, 70) }}</p>
                                 <ul class="card_ul">
                                    <li><i class="fa fa-car"></i> {{ $vehicle->make->name }}</li>
                                    <li><i class="fa fa-cogs"></i> {{ $vehicle->transmission }}</li>

                                    <li><i class="fa fa-eye"></i> {{ $vehicle->vehiclemodel->name }}</li>
                                    <li><i class="fa fa-tint"></i> {{ $vehicle->fuel_type }}</li>
                                    <li><i class="fa fa-dashboard"></i>{{$vehicle->mileage}} Milles</li>



                                 </ul>
                                 <div class="offer-action">

                                    <a href="{{ route('vehicle.detail',['id'=>$vehicle->id ]) }}"
                                       class="offer-btn-2">£{{ number_format($vehicle->rent, 0) }} - Details </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @empty
                        <div class="w-100 text-center py-5">
                           <h3>No Vehicles Found for {{ ucfirst($make) }}</h3>
                        </div>
                        @endforelse
                     </div>
                  </div>
                  @endforeach
               </div>

               <!-- All Tab End -->
            </div>
         </div>
</section>
<!-- Offers Area End -->


<!-- Testimonial Area Start -->
<section class="gauto-testimonial-area section_70">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="site-heading">
               <h4>Some words</h4>
               <h2>testimonial</h2>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="testimonial-slider owl-carousel">
               <div class="single-testimonial">
                  <div class="testimonial-text">
                     <p>"Dorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusm tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo consequat adipisicing elit."</p>
                     <div class="testimonial-meta">
                        <div class="client-image">

                           <img src="{{ asset('assets/frontend/img/testimonial.jpg') }}" alt="testimonial" />
                        </div>
                        <div class="client-info">
                           <h3>Marco Ghaly</h3>
                           <p>Customer</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="single-testimonial">
                  <div class="testimonial-text">
                     <p>"Forem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusm tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo consequat adipisicing elit."</p>
                     <div class="testimonial-meta">
                        <div class="client-image">
                           <img src="{{ asset('assets/frontend/img/testimonial.jpg') }}" alt="testimonial" />
                        </div>
                        <div class="client-info">
                           <h3>Sherief Arafa</h3>
                           <p>Customer</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="single-testimonial">
                  <div class="testimonial-text">
                     <p>"Dorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusm tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo consequat adipisicing elit."</p>
                     <div class="testimonial-meta">
                        <div class="client-image">
                           <img src="{{ asset('assets/frontend/img/testimonial.jpg') }}" alt="testimonial" />
                        </div>
                        <div class="client-info">
                           <h3>Marco Ghaly</h3>
                           <p>Customer</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="single-testimonial">
                  <div class="testimonial-text">
                     <p>"Dorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusm tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo consequat adipisicing elit."</p>
                     <div class="testimonial-meta">
                        <div class="client-image">
                           <img src="{{ asset('assets/frontend/img/testimonial.jpg') }}" alt="testimonial" />
                        </div>
                        <div class="client-info">
                           <h3>Marco Ghaly</h3>
                           <p>Customer</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- Testimonial Area End -->


<!-- Call Area Start -->
<section class="gauto-call-area section_70">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="call-box">
               <div class="call-box-inner">
                  <h2>Sell a Car <span>Quickly</span> and Easily with Us </h2>
                  <p>It is really easy to sell your car!.</p>
                  <div class="call-number">
                     <div class="call-icon">
                        <i class="fa fa-phone"></i>
                     </div>
                     <div class="call-text">
                        <p>need any help?</p>
                        <h4><a href="#">01163194355</a></h4>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- Call Area End -->



@endsection