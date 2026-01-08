@extends('frontend.layout')
@section('content')
@section('title', 'Parts Exchange')
<!-- Breadcromb Area Start -->
<section class="gauto-breadcromb-area section_70">
    <div class="container">
       <div class="row">
          <div class="col-md-12">
             <div class="breadcromb-box">
                <h3>Parts Exchange</h3>
                <ul>
                   <li><i class="fa fa-home"></i></li>
                   <li><a href="{{ route('home') }}">Home</a></li>
                   <li><i class="fa fa-angle-right"></i></li>
                   <li>Parts Exchange</li>
                </ul>
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- Breadcromb Area End -->



   <!-- About Page Area Start -->
   <section class="about-page-area " style="padding: 40px 0 0 0;">
    <div class="container">
       <div class="row">
          <div class="col-lg-12">
             <ul class="d-md-flex justify-content-center " style="gap:10px">
               <li style="
    display: flex;
    align-items: center;
">
                  <svg data-gui="atds-icon-icon-tick" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="-2 -5 24 24" fill="#242D3D" class="atds-icon-svg"><title>Icon Tick</title><path d="M6 13.439L0 7.43902L1.42 6.01902L6.69 11.299L17.98 -0.000976562L19.4 1.41902L7.4 13.419C7.2157 13.6049 6.96584 13.7112 6.70408 13.7149C6.44232 13.7187 6.18953 13.6196 6 13.439Z"></path></svg>
                  Real time part-ex valuation
               </li>
               <li style="
    display: flex;
    align-items: center;
">
                  <svg data-gui="atds-icon-icon-tick" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="-2 -5 24 24" fill="#242D3D" class="atds-icon-svg"><title>Icon Tick</title><path d="M6 13.439L0 7.43902L1.42 6.01902L6.69 11.299L17.98 -0.000976562L19.4 1.41902L7.4 13.419C7.2157 13.6049 6.96584 13.7112 6.70408 13.7149C6.44232 13.7187 6.18953 13.6196 6 13.439Z"></path></svg>
                  Understand your cost of change
               </li>
               <li style="
    display: flex;
    align-items: center;
">
                  <svg data-gui="atds-icon-icon-tick" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="-2 -5 24 24" fill="#242D3D" class="atds-icon-svg"><title>Icon Tick</title><path d="M6 13.439L0 7.43902L1.42 6.01902L6.69 11.299L17.98 -0.000976562L19.4 1.41902L7.4 13.419C7.2157 13.6049 6.96584 13.7112 6.70408 13.7149C6.44232 13.7187 6.18953 13.6196 6 13.439Z"></path></svg>
                  Feel more confident
               </li>
             </ul>
          </div>
          
       </div>
    </div>
 </section>
   <section class="about-page-area section_70">
    <div class="container">
       <div class="row">
          <div class="col-lg-6">
             <div class="about-page-left">
                <h4>Parts Exchange</h4>
                <h3>Welcome to HISPEED Car Sales</h3>
                <p>Your part-exchange valuation will be slightly less than what you would get if you sold your car privately or if you were buying the same vehicle. This is because the dealer will be buying your car from you to resell it, meaning they'll incur costs for stocking and preparation.

They will take these costs into account when offering you a price. Bear in mind that you may get a better deal on the car you're buying if you part-exchange with a dealer. Our valuations play back what's in the market, and because the used-car market changes quickly, we update our valuations every day.</p>
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