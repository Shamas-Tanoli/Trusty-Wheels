@extends('frontend.layout')
@section('content')
@section('title', 'About')
<!-- Breadcromb Area Start -->
<section class="gauto-breadcromb-area section_70">
    <div class="container">
       <div class="row">
          <div class="col-md-12">
             <div class="breadcromb-box">
                <h3>About Us</h3>
                <ul>
                   <li><i class="fa fa-home"></i></li>
                   <li><a href="{{ route('home') }}">Home</a></li>
                   <li><i class="fa fa-angle-right"></i></li>
                   <li>About Us</li>
                </ul>
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- Breadcromb Area End -->

   <!-- About Page Area Start -->
   <section class="about-page-area section_70">
    <div class="container">
       <div class="row">
          <div class="col-lg-6">
             <div class="about-page-left">
                <h4>About Us</h4>
                <h3>Welcome to HISPEED Car Sales</h3>
                <p>We are your premier used cars dealer in the east midlands. Your trusted car dealership, specialising in importing top-quality vehicles from japan. We pride ourselves on offering a diverse selection of reliable and stylish cars to suits every need and budget. Our commitment to excellence and customer satisfaction ensures a smooth and enjoyable car buying experience. At HISPEED CAR SALES we bring 30 years of expertise in sourcing the finest vehicles from Japanese auctions directly to east midlands and across UK. We go the extra mile to ensure your peace of mind with every purchase, We sell our each vehicle with 12 months warranty and 12 months MOT.</p>
                <div class="about-page-call">
                   <div class="page-call-icon">
                      <i class="fa fa-phone"></i>
                   </div>
                   <div class="call-info">
                      <p>Need any Help?</p>
                      <h4> <a href="tel:01163194355"> 01163194355</a></h4>
                   </div>
                </div>
             </div>
          </div>
          <div class="col-lg-6">
             <div class="about-page-right">
                <img src="{{ asset('assets/frontend/img/about-page.jpg') }}" alt="about page" />
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- About Page Area End -->

 <!-- About Promo Area Start -->
 <section class="gauto-about-promo section_70">
    <div class="container">
       <div class="row">
          <div class="col-md-12">
             <div class="about-promo-text">
                <h3>We are proud of our business. <span>Buy Car</span> Now!</h3>
             </div>
          </div>
       </div>
       <div class="row">
          <div class="col-md-12">
             <div class="about-promo-image">
                <img src="{{ asset('assets/frontend/img/cars.png') }}" alt="about promo" />
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- About Promo Area End -->


 <!-- Promo Area Start -->
 <section class="gauto-promo-area  mb-5">
    <div class="container">
       <div class="row">
          <div class="col-md-6">
             <div class="promo-box-left">
                <img src="{{ asset('assets/frontend/img/toyota-offer-2.png') }}" alt="promo car" />
             </div>
          </div>
          <div class="col-md-6">
             <div class="promo-box-right">
                <h3>
Find your perfect car with trusted deals and unbeatable prices.</h3>
                <a href="javascript:void(0)" class="gauto-btn">view Listing</a>
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- Promo Area End -->


 

@endsection