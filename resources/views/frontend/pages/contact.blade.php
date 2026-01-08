@extends('frontend.layout')
@section('content')
@section('title', 'Contact')
<!-- Breadcromb Area Start -->
<section class="gauto-breadcromb-area section_70">
    <div class="container">
       <div class="row">
          <div class="col-md-12">
             <div class="breadcromb-box">
                <h3>Contact Us</h3>
                <ul>
                   <li><i class="fa fa-home"></i></li>
                   <li><a href="{{ route('home') }}">Home</a></li>
                   <li><i class="fa fa-angle-right"></i></li>
                   <li>Contact Us</li>
                </ul>
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- Breadcromb Area End -->


  <!-- Contact Area Start -->
  <section class="gauto-contact-area section_70">
    <div class="container">
       <div class="row">
          <div class="col-lg-7">
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
          <div class="col-lg-5">
             <div class="contact-right">
                <h3>Contact information</h3>
                <div class="contact-details">
                   <p><i class="fa fa-map-marker"></i>UNIT 3 11 WOODGATE LEICESTER LE35GH</p>
                   <div class="single-contact-btn">
                      <h4>Email Us</h4>
                      <a href="mailto:sales@hispeedcarsales.co.uk">sales@hispeedcarsales.co.uk</a>
                   </div>
                   <div class="single-contact-btn">
                      <h4>Call Us</h4>
                      <a href="tel:01163194355">01163194355</a>
                   </div>
                   <div class="social-links-contact">
                      <h4>Follow Us:</h4>
                      <ul>
                         <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                         <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                         <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                         <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                         <li><a href="#"><i class="fa fa-skype"></i></a></li>
                         <li><a href="#"><i class="fa fa-vimeo"></i></a></li>
                      </ul>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- Contact Area End -->

@endsection