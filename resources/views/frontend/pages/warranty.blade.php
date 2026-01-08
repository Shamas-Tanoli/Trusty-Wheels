

@extends('frontend.layout')
@section('content')
@section('title', 'Car warranty')
<!-- Breadcromb Area Start -->
<section class="gauto-breadcromb-area section_70">
    <div class="container">
       <div class="row">
          <div class="col-md-12">
             <div class="breadcromb-box">
                <h3>Car warranty</h3>
                <ul>
                   <li><i class="fa fa-home"></i></li>
                   <li><a href="{{ route('home') }}">Home</a></li>
                   <li><i class="fa fa-angle-right"></i></li>
                   <li>Car warranty</li>
                </ul>
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- Breadcromb Area End -->



 
   <section class="about-page-area section_70">
    <div class="container">
       <div class="row">
          <div class="col-lg-6">
             <div class="about-page-left">
                <h4>Car warranty</h4>
                <h3>Welcome to HISPEED Car Sales</h3>
                <p>Our comprehensive warranty gives you peace of mind against expensive repairs.
                  Almost all mechanical and electrical faults up to current market value^.Hybrid and electric vehicles.Parts not normally covered, including diagnostics, working materials and parts replaced in pairs, such as coil springs and shock absorbers.In-car entertainment and remote key fobs.No limits on the number of claims made up to current market value.Access to a nationwide approved repair network.Free A Basic Breakdown cover for vehicles used for personal use only
                </p>
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




 <section class="gauto-contact-area section_70">
    <div class="container">
       <div class="row">
          <div class="col-lg-12">
             <div class="contact-left">
                <h3>Get in touch</h3>
                <form>
                   <div class="row">
                      <div class="col-md-6">
                         <div class="single-contact-field">
                            <input type="text" placeholder="Your Name">
                         </div>
                      </div>
                      <div class="col-md-6">
                         <div class="single-contact-field">
                            <input type="email" placeholder="Email Address">
                         </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-md-6">
                         <div class="single-contact-field">
                            <input type="text" placeholder="Subject">
                         </div>
                      </div>
                      <div class="col-md-6">
                         <div class="single-contact-field">
                            <input type="tel" placeholder="Phone Number">
                         </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-md-12">
                         <div class="single-contact-field">
                            <textarea placeholder="Write here your message"></textarea>
                         </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-md-12">
                         <div class="single-contact-field">
                            <button type="submit" class="gauto-theme-btn"><i class="fa fa-paper-plane"></i> Send Message</button>
                         </div>
                      </div>
                   </div>
                </form>
             </div>
          </div>
          
       </div>
    </div>
 </section>
 


 

@endsection